<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Enums\ScheduleDay;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScheduleRequest;
use App\Http\Resources\Admin\ScheduleResource;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Level;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Throwable;

class ScheduleController extends Controller
{

    public static function middleware()
    {
        return [];
    }
    public function index()
    {
        $schedules = Schedule::query()
            ->select(['schedules.id',  'schedules.level_id', 'schedules.classroom_id', 'schedules.course_id', 'schedules.start_time', 'schedules.end_time', 'schedules.day_of_week', 'schedules.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['classroom', 'course', 'level'])
            ->paginate(request()->load ?? 10);




        return inertia('Admin/Schedules/Index', [
            'page_setting' => [
                'title' => 'Jadwal',
                'subtitle' => 'Menampilkan semua data Jadwal yang tersedia di Sekolah ini'
            ],
            'schedules' => ScheduleResource::collection($schedules)->additional([
                'meta' => [
                    'has_pages' => $schedules->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10
            ]
        ]);
    }

    public function create()
    {
        return inertia('Admin/Schedules/Create', [
            'page_setting' => [
                'title' => 'Tambah Jadwal',
                'subtitle' => 'Buat Jadwal baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.schedules.store')
            ],
            'levels' => Level::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'courses' => Course::query()->select(['id', 'name', 'level_id'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
                'level_id' => $item->level_id,
            ]),
            'classrooms' => Classroom::query()->select(['id', 'name', 'level_id'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
                'level_id' => $item->level_id,
            ]),
            'days' => ScheduleDay::options(),
        ]);
    }

    public function store(ScheduleRequest $request)
    {

        try {
            Schedule::create([
                'level_id' => $request->level_id,
                'course_id' => $request->course_id,
                'classroom_id' => $request->classroom_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'day_of_week' => $request->day_of_week,

            ]);
            flashMessage(MessageType::CREATED->message('Jadwal'));
            return to_route('admin.schedules.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.schedules.index');
        }
    }

    public function Edit(Schedule $schedule)
    {
        return inertia('Admin/Schedules/Edit', [
            'page_setting' => [
                'title' => 'Edit Jadwal',
                'subtitle' => 'Edit Jadwal disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.schedules.update', $schedule)
            ],
            'schedule' => $schedule,
            'levels' => Level::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'courses' => Course::query()->select(['id', 'name', 'level_id'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
                'level_id' => $item->level_id,
            ]),
            'classrooms' => Classroom::query()->select(['id', 'name', 'level_id'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
                'level_id' => $item->level_id,
            ]),
            'days' => ScheduleDay::options(),
        ]);
    }

    public function update(ScheduleRequest $request, Schedule $schedule)
    {
        try {
            $schedule->update([
                'level_id' => $request->level_id,
                'course_id' => $request->course_id,
                'classroom_id' => $request->classroom_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'day_of_week' => $request->day_of_week,

            ]);
            flashMessage(MessageType::UPDATED->message('Jadwal'));
            return to_route('admin.schedules.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.schedules.index');
        }
    }

    public function destroy(Schedule $schedule)
    {
        try {
            // $schedule->studyPlans()->detach($schedule);

            $schedule->delete();
            flashMessage(MessageType::DELETED->message('Jadwal'));
            return to_route('admin.schedules.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.schedules.index');
        }
    }
}
