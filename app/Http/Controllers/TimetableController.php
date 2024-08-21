<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Timetable;
use App\Models\TeacherSubjectClass;
use App\Models\ClassSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TimetableController extends Controller
{
    public function index()
    {
        $timetables = Timetable::with(['classroom', 'teacher', 'subject'])->get();
        $classrooms = Classroom::all(); // Fetch classrooms for the dropdown
        $teachers = Teacher::all(); // Fetch teachers for the view

        return view('timetables.index', compact('timetables', 'classrooms', 'teachers'));
    }
    public function show(Timetable $timetable)
    {
        $classrooms = Classroom::all();
        $teachers = Teacher::all();
        $subjects = Subject::all();

        return view('timetables.show', compact('timetable', 'classrooms', 'teachers', 'subjects'));
    }

    public function create()
    {
        $classrooms = Classroom::all();
        $teachers = Teacher::all();
        $subjects = Subject::all();

        return view('timetables.create', compact('classrooms', 'teachers', 'subjects'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'classroom_id' => 'required|exists:classrooms,id',
                'teacher_id' => 'required|exists:teachers,id',
                'subject_id' => 'required|exists:subjects,id',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
                'day_of_week' => 'required|string',
            ]);

            if ($this->checkForConflicts($request)) {
                return redirect()->back()->withErrors(['conflict_error' => 'There is a scheduling conflict. Please adjust the timetable.']);
            }

            $timetable = Timetable::create($request->all());

            // Check if classroom_id is set and redirect accordingly
            if ($request->has('classroom_id')) {
                return redirect()->route('timetables.index', ['classroom_id' => $request->classroom_id])
                                 ->with('success', 'Timetable created successfully.');
            } else {
                return redirect()->route('timetables.index')
                                 ->with('success', 'Timetable created successfully.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }


    public function edit(Timetable $timetable)
    {
        $classrooms = Classroom::all();
        $teachers = Teacher::all();
        $subjects = Subject::all();

        return view('timetables.edit', compact('timetable', 'classrooms', 'teachers', 'subjects'));
    }

    public function update(Request $request, Timetable $timetable)
    {
        try {
            $request->validate([
                'classroom_id' => 'required|exists:classrooms,id',
                'teacher_id' => 'required|exists:teachers,id',
                'subject_id' => 'required|exists:subjects,id',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
                'day_of_week' => 'required|string',
            ]);

            if ($this->checkForConflicts($request, $timetable->id)) {
                return redirect()->back()->withErrors(['conflict_error' => 'There is a scheduling conflict. Please adjust the timetable.']);
            }

            $timetable->update($request->all());

            // Check if classroom_id is set and redirect accordingly
            if ($request->has('classroom_id')) {
                return redirect()->route('timetables.index', ['classroom_id' => $request->classroom_id])
                                 ->with('success', 'Timetable updated successfully.');
            } else {
                return redirect()->route('timetables.index')
                                 ->with('success', 'Timetable updated successfully.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    public function destroy(Timetable $timetable)
    {
        $timetable->delete();

        return redirect()->route('timetables.index')->with('success', 'Timetable deleted successfully.');
    }

    private function checkForConflicts($request, $ignoreTimetableId = null)
    {
        // Check for time conflicts for teachers and classrooms
        $conflictingTimetable = Timetable::where('day_of_week', $request->day_of_week)
            ->where(function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
                });
            })
            ->where(function($query) use ($request, $ignoreTimetableId) {
                $query->where('teacher_id', $request->teacher_id)
                      ->orWhere('classroom_id', $request->classroom_id);

                if ($ignoreTimetableId) {
                    $query->where('id', '!=', $ignoreTimetableId);
                }
            })
            ->exists();

        if ($conflictingTimetable) {
            throw new \Exception("Time conflict detected for teacher or classroom on {$request->day_of_week} from {$request->start_time} to {$request->end_time}.");
        }

        // Check if the teacher is overbooked
        $totalSessions = Timetable::where('teacher_id', $request->teacher_id)->count();
        $teacherMaxSessions = TeacherSubjectClass::where('teacher_id', $request->teacher_id)
            ->sum('weekly_sessions');

        if ($totalSessions >= $teacherMaxSessions) {
            throw new \Exception("Teacher with ID {$request->teacher_id} is overbooked.");
        }

        // Ensure no class is assigned more than one teacher at the same time
        $conflictingClassTimetable = Timetable::where('classroom_id', $request->classroom_id)
            ->where('day_of_week', $request->day_of_week)
            ->where(function($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })
            ->where('teacher_id', '!=', $request->teacher_id)
            ->exists();

        if ($conflictingClassTimetable) {
            throw new \Exception("Classroom with ID {$request->classroom_id} has another teacher assigned during this time.");
        }

        // Check if the class has the required sessions, ignoring the current session being edited
        $classRequiredSessions = ClassSubject::where('classroom_id', $request->classroom_id)
            ->where('subject_id', $request->subject_id)
            ->sum('required_sessions');

        $currentClassSessions = Timetable::where('classroom_id', $request->classroom_id)
            ->where('subject_id', $request->subject_id)
            ->where(function($query) use ($ignoreTimetableId) {
                if ($ignoreTimetableId) {
                    $query->where('id', '!=', $ignoreTimetableId);
                }
            })
            ->count();

        if ($currentClassSessions >= $classRequiredSessions) {
            throw new \Exception("Subject with ID {$request->subject_id} has reached the maximum number of required sessions for classroom {$request->classroom_id}.");
        }

        // Ensure the teacher is only assigned to the subject they are supposed to teach
        $teacherSubjectAssignment = TeacherSubjectClass::where('teacher_id', $request->teacher_id)
            ->where('subject_id', $request->subject_id)
            ->exists();

        if (!$teacherSubjectAssignment) {
            throw new \Exception("Teacher with ID {$request->teacher_id} is not assigned to teach subject with ID {$request->subject_id}.");
        }

        return false;
    }



    public function teacherSchedule(Request $request)
    {
        $teacherId = $request->input('teacherId');

        if (!$teacherId) {
            return redirect()->route('timetables.index')->with('error', 'Please select a teacher.');
        }

        $teacher = Teacher::findOrFail($teacherId);
        $timetables = Timetable::with(['classroom', 'subject'])
                                ->where('teacher_id', $teacherId)
                                ->get();

        return view('timetables.teacherSchedule', compact('teacher', 'timetables'));
    }




}
