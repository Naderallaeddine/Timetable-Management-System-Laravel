<?php
namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Timetable;
use App\Models\TeacherSubjectClass;
use App\Models\ClassSubject;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class TimetableGenerationController extends Controller
{
    public function generateAll()
    {
        Log::info('Starting timetable generation for all classrooms.');

        // Fetch all classrooms
        $classrooms = Classroom::all();

        foreach ($classrooms as $classroom) {
            Log::info('Processing classroom: ' . $classroom->name);
            $this->generateTimetableForClass($classroom->id);
        }

        Log::info('Finished timetable generation for all classrooms.');

        return redirect()->route('timetables.index')->with('success', 'Schedules for all classrooms generated successfully.');
    }

    // public function generateTimetable(Request $request)
    // {
    //     $request->validate([
    //         'classroom_id' => 'required|exists:classrooms,id',
    //     ]);

    //     $this->generateTimetableForClass($request->classroom_id);

    //     return redirect()->route('timetables.index')->with('success', 'Timetable generated successfully for the selected classroom.');
    // }


    private function generateTimetableForClass($classroomId)
    {
        Log::info('Generating timetable for classroom: ' . $classroomId);

        $classroom = Classroom::find($classroomId);
        $subjects = ClassSubject::where('classroom_id', $classroomId)->get();

        foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day) {
            $startTime = "08:00:00";

            for ($i = 0; $i < 5; $i++) {
                $endTime = date("H:i:s", strtotime('+1 hour', strtotime($startTime)));

                foreach ($subjects as $classSubject) {
                    $subject = $classSubject->subject;
                    $teacherId = $this->findAvailableTeacher($classroomId, $subject->id, $day, $startTime, $endTime);

                    if ($teacherId && $this->needsMoreSessions($classroomId, $subject->id)) {
                        $request = new Request([
                            'classroom_id' => $classroomId,
                            'teacher_id' => $teacherId,
                            'subject_id' => $subject->id,
                            'day_of_week' => $day,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                        ]);

                        if (!$this->checkForConflicts($request)) {
                            Timetable::create($request->all());
                            Log::info("Created timetable entry: Classroom $classroomId, Subject {$subject->id}, Teacher $teacherId, Day $day, Time $startTime - $endTime");
                            break; // Move to the next time slot after assigning a subject
                        } else {
                            Log::warning("Conflict detected for Teacher $teacherId in Classroom $classroomId on $day at $startTime - $endTime");
                        }
                    }
                }

                $startTime = $endTime;
            }
        }
    }

    private function checkForConflicts($request, $ignoreTimetableId = null)
    {
        $conflictingTimetable = Timetable::where('day_of_week', $request->day_of_week)
            ->where(function($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
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
            return true;
        }

        $totalSessions = Timetable::where('teacher_id', $request->teacher_id)
            ->when($ignoreTimetableId, function($query) use ($ignoreTimetableId) {
                return $query->where('id', '!=', $ignoreTimetableId);
            })
            ->count();

        $teacherMaxSessions = TeacherSubjectClass::where('teacher_id', $request->teacher_id)
            ->sum('weekly_sessions');

        if ($totalSessions >= $teacherMaxSessions) {
            return true;
        }

        $conflictingClassTimetable = Timetable::where('classroom_id', $request->classroom_id)
            ->where('day_of_week', $request->day_of_week)
            ->where(function($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })
            ->where('teacher_id', '!=', $request->teacher_id)
            ->exists();

        if ($conflictingClassTimetable) {
            return true;
        }

        $classRequiredSessions = ClassSubject::where('classroom_id', $request->classroom_id)
            ->where('subject_id', $request->subject_id)
            ->sum('required_sessions');
        $currentClassSessions = Timetable::where('classroom_id', $request->classroom_id)
            ->where('subject_id', $request->subject_id)
            ->when($ignoreTimetableId, function($query) use ($ignoreTimetableId) {
                return $query->where('id', '!=', $ignoreTimetableId);
            })
            ->count();

        if ($currentClassSessions >= $classRequiredSessions) {
            return true;
        }

        $teacherSubjectAssignment = TeacherSubjectClass::where('teacher_id', $request->teacher_id)
            ->where('subject_id', $request->subject_id)
            ->exists();

        if (!$teacherSubjectAssignment) {
            return true;
        }

        return false;
    }

    private function findAvailableTeacher($classroomId, $subjectId, $day, $startTime, $endTime)
    {
        $availableTeachers = TeacherSubjectClass::where('subject_id', $subjectId)
            ->pluck('teacher_id')
            ->toArray();

        foreach ($availableTeachers as $teacherId) {
            $conflict = Timetable::where('teacher_id', $teacherId)
                ->where('day_of_week', $day)
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                })
                ->exists();

            if (!$conflict) {
                return $teacherId;
            }
        }

        return null;
    }

    private function needsMoreSessions($classroomId, $subjectId)
    {
        $requiredSessions = ClassSubject::where('classroom_id', $classroomId)
            ->where('subject_id', $subjectId)
            ->sum('required_sessions');

        $currentSessions = Timetable::where('classroom_id', $classroomId)
            ->where('subject_id', $subjectId)
            ->count();

        return $currentSessions < $requiredSessions;
    }


    public function exportPDF()
    {

        $timetables = TimeTable::all();
        $classrooms = Classroom::all();

        $pdf = Pdf::loadView('timetables.timetables_pdf', ['timetables' => $timetables, 'classrooms' => $classrooms])
        ->setPaper('a4', 'landscape');



        return $pdf->download('timetables.pdf');
    }


}


