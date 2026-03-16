<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\TeacherProfile;
use App\Services\NotificationService;

class AssignmentService
{
    public function generate(int $assignedBy): array
    {
        Assignment::where('rationale', '!=', 'manual_override')->delete();

        $subjects = Subject::all();
        $schedules = Schedule::all();
        $teachers = TeacherProfile::with(['availabilities', 'assignments', 'user'])->get();
        $results = [];
        $scheduleIndex = 0;

        $assignedSubjectCodes = Assignment::whereIn('rationale', ['expertise_match', 'availability'])
            ->with('subject')
            ->get()
            ->pluck('subject.code')
            ->toArray();

        $teacherScheduleMap = [];

        foreach ($subjects as $subject) {
            if (!empty($subject->prerequisites)) {
                $prereqs = array_map('trim', explode(',', $subject->prerequisites));
                foreach ($prereqs as $prereq) {
                    if (!in_array($prereq, $assignedSubjectCodes)) {
                        continue 2;
                    }
                }
            }

            $schedule = $schedules->get($scheduleIndex);
            if (!$schedule) break;
            $scheduleIndex++;

            $expertiseMatches = $teachers->filter(function ($teacher) use ($subject) {
                $expertiseList = array_map('trim', explode('|', strtolower($teacher->expertise_areas)));
                $subjectName = strtolower($subject->name);
                $subjectCode = strtolower($subject->code);

                foreach ($expertiseList as $expertise) {
                    if (str_contains($subjectName, $expertise) || str_contains($subjectCode, $expertise)) {
                        return true;
                    }
                }
                return false;
            });

            $availableExpertise = $expertiseMatches->filter(function ($teacher) use ($schedule, $teacherScheduleMap) {
                return $this->isAvailable($teacher, $schedule) &&
                    $this->hasNoConflict($teacher->id, $schedule->id, $teacherScheduleMap);
            });

            $availableOnly = $teachers->filter(function ($teacher) use ($schedule, $teacherScheduleMap) {
                return $this->isAvailable($teacher, $schedule) &&
                    $this->hasNoConflict($teacher->id, $schedule->id, $teacherScheduleMap);
            });

            $rationale = 'expertise_match';
            $selectedTeacher = $availableExpertise->first();

            if (!$selectedTeacher) {
                $selectedTeacher = $availableOnly->first();
                $rationale = 'availability';
            }

            if (!$selectedTeacher) continue;

            $currentUnits = $selectedTeacher->assignments()->sum('total_units');
            $newTotal = $currentUnits + $subject->units;
            $isOverloaded = $newTotal > $selectedTeacher->max_units;

            $assignment = Assignment::create([
                'teacher_profile_id' => $selectedTeacher->id,
                'subject_id' => $subject->id,
                'schedule_id' => $schedule->id,
                'total_units' => $subject->units,
                'rationale' => $rationale,
                'is_overloaded' => $isOverloaded,
                'assigned_by' => $assignedBy,
            ]);

            // Send notification to teacher
            NotificationService::send(
                $selectedTeacher->user->id,
                'New Subject Assigned',
                "You have been assigned to teach {$subject->name} ({$subject->code}) on {$schedule->day} {$schedule->time_start} - {$schedule->time_end} at {$schedule->room}."
            );

            // Send overload notification
            if ($isOverloaded) {
                NotificationService::send(
                    $selectedTeacher->user->id,
                    'Overload Warning',
                    "Your total units have exceeded the maximum limit after being assigned {$subject->name}. Please contact your Program Chair."
                );
            }

            $teacherScheduleMap[$selectedTeacher->id][] = $schedule->id;
            $assignedSubjectCodes[] = $subject->code;
            $results[] = $assignment;
        }

        return $results;
    }

    private function isAvailable(TeacherProfile $teacher, Schedule $schedule): bool
    {
        return $teacher->availabilities->contains(function ($availability) use ($schedule) {
            return $availability->day === $schedule->day &&
                $availability->time_start <= $schedule->time_start &&
                $availability->time_end >= $schedule->time_end;
        });
    }

    private function hasNoConflict(int $teacherId, int $scheduleId, array $teacherScheduleMap): bool
    {
        if (!isset($teacherScheduleMap[$teacherId])) {
            return true;
        }
        return !in_array($scheduleId, $teacherScheduleMap[$teacherId]);
    }
}