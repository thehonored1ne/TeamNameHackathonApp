<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\TeacherProfile;
use App\Models\Availability;
use App\Models\Notification;
use App\Models\TeacherRequest;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index()
    {
        $teacherProfile = TeacherProfile::where('user_id', Auth::id())->first();

        if (!$teacherProfile) {
            return view('teacher.dashboard', [
                'assignments' => collect(),
                'totalSubjects' => 0,
                'totalUnits' => 0,
                'isOverloaded' => false,
            ]);
        }

        $assignments = Assignment::with(['subject', 'schedule'])
            ->where('teacher_profile_id', $teacherProfile->id)
            ->get();

        $totalUnits = $assignments->sum('total_units');
        $totalSubjects = $assignments->count();
        $isOverloaded = $totalUnits > $teacherProfile->max_units;

        return view('teacher.dashboard', compact(
            'assignments',
            'totalSubjects',
            'totalUnits',
            'isOverloaded'
        ));
    }

    public function exportSchedule()
    {
        $teacherProfile = TeacherProfile::where('user_id', Auth::id())->first();

        if (!$teacherProfile) {
            return back()->with('error', 'No profile found.');
        }

        $assignments = Assignment::with(['subject', 'schedule'])
            ->where('teacher_profile_id', $teacherProfile->id)
            ->get();

        $filename = 'my_schedule_' . now()->format('Y_m_d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($assignments, $teacherProfile) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Teacher', Auth::user()->name]);
            fputcsv($file, ['Total Units', $assignments->sum('total_units')]);
            fputcsv($file, ['Max Units', $teacherProfile->max_units]);
            fputcsv($file, ['Status', $assignments->sum('total_units') > $teacherProfile->max_units ? 'Overloaded' : 'Normal']);
            fputcsv($file, []);

            fputcsv($file, ['Subject Code', 'Subject Name', 'Units', 'Day', 'Time', 'Room', 'Rationale']);

            foreach ($assignments as $assignment) {
                fputcsv($file, [
                    $assignment->subject->code,
                    $assignment->subject->name,
                    $assignment->total_units,
                    $assignment->schedule->day,
                    $assignment->schedule->time_start . ' - ' . $assignment->schedule->time_end,
                    $assignment->schedule->room,
                    str_replace('_', ' ', $assignment->rationale),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function notifications()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('teacher.notifications', compact('notifications'));
    }

    public function markAsRead($id)
    {
        Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->update(['is_read' => true]);

        return back();
    }

    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function requests()
    {
        $requests = TeacherRequest::where('teacher_id', Auth::id())
            ->latest()
            ->get();

        return view('teacher.requests', compact('requests'));
    }

    public function sendRequest(Request $request)
    {
        $request->validate([
            'type' => 'required|in:change_subject,unavailability,general_message,load_reduction',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        TeacherRequest::create([
            'teacher_id' => Auth::id(),
            'type' => $request->type,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        $chair = \App\Models\User::where('role', 'program_chair')->first();
        if ($chair) {
            \App\Services\NotificationService::send(
                $chair->id,
                'New Request from ' . Auth::user()->name,
                Auth::user()->name . ' sent a ' . str_replace('_', ' ', $request->type) . ' request: ' . $request->subject
            );
        }

        return back()->with('success', 'Request sent successfully.');
    }

    public function teachingProfile()
    {
        $teacherProfile = TeacherProfile::where('user_id', Auth::id())
            ->with('availabilities')
            ->first();

        return view('teacher.teaching_profile', compact('teacherProfile'));
    }

    public function updateTeachingProfile(Request $request)
    {
        $request->validate([
            'expertise_areas' => 'required|string',
            'max_units' => 'required|integer|min:1|max:30',
        ]);

        $teacherProfile = TeacherProfile::where('user_id', Auth::id())->first();

        $teacherProfile->update([
            'expertise_areas' => $request->expertise_areas,
            'max_units' => $request->max_units,
        ]);

        // Update availabilities
        Availability::where('teacher_profile_id', $teacherProfile->id)->delete();

        if ($request->has('days')) {
            foreach ($request->days as $index => $day) {
                if (!empty($day)) {
                    Availability::create([
                        'teacher_profile_id' => $teacherProfile->id,
                        'day' => $day,
                        'time_start' => $request->time_starts[$index],
                        'time_end' => $request->time_ends[$index],
                    ]);
                }
            }
        }

        return back()->with('success', 'Teaching profile updated successfully.');
    }
}