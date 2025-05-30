<?php

namespace App\Http\Controllers\Operator;

use App\Enums\MessageType;
use App\Enums\ScheduleDay;
use App\Http\Controllers\Controller;
use App\Http\Requests\Operator\ScheduleOperatorRequest;
use App\Http\Resources\Operator\ScheduleOperatorResource;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Throwable;

class ScheduleOperatorController extends Controller
{
    public function index()
    {
        $schedules = Schedule::query()
            ->select(['schedules.id',  'schedules.faculty_id','schedules.classroom_id', 'schedules.course_id', 'schedules.start_time', 'schedules.end_time', 'schedules.academic_year_id', 'schedules.day_of_week', 'schedules.quote', 'schedules.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->where('schedules.faculty_id', auth()->user()->operator->faculty_id)
            ->with(['classroom', 'course', 'faculty', 'academicYear'])
            ->paginate(request()->load ?? 10);

        $faculty_name = auth()->user()->operator->faculty->name;


        return inertia('Operators/Schedules/Index', [
            'page_setting' => [
                'title' => 'Jadwal',
                'subtitle' => "Menampilkan Jadwal yang ada di {$faculty_name} "
            ],
            'schedules' => ScheduleOperatorResource::collection($schedules)->additional([
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
        return inertia('Operators/Schedules/Create', [
            'page_setting' => [
                'title' => 'Tambah Jadwal',
                'subtitle' => 'Buat Jadwal baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('operators.schedules.store')
            ],
            'courses' => Course::query()->select(['id', 'name'])
                ->where('faculty_id', auth()->user()->operator->faculty_id)
                ->orderBy('name')->get()->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
            'classrooms' => Classroom::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'days' => ScheduleDay::options(),
        ]);
    }

    public function store(ScheduleOperatorRequest $request)
    {

        try {
            Schedule::create([
                'faculty_id' => auth()->user()->operator->faculty_id,
                'course_id' => $request->course_id,
                'classroom_id' => $request->classroom_id,
                'academic_year_id' => activeAcademicYear()->id,
                'quote' => $request->quote,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'day_of_week' => $request->day_of_week,

            ]);
            flashMessage(MessageType::CREATED->message('Jadwal'));
            return to_route('operators.schedules.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.schedules.index');
        }
    }

    public function Edit(Schedule $schedule)
    {
        return inertia('Operators/Schedules/Edit', [
            'page_setting' => [
                'title' => 'Edit Jadwal',
                'subtitle' => 'Edit Jadwal disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('operators.schedules.update', $schedule)
            ],
            'schedule' => $schedule,
            'courses' => Course::query()->select(['id', 'name'])
                ->where('faculty_id', auth()->user()->operator->faculty_id)
                ->orderBy('name')->get()->map(fn($item) => [
                    'value' => $item->id,
                    'label' => $item->name,
                ]),
            'classrooms' => Classroom::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'days' => ScheduleDay::options(),
        ]);
    }

    public function update(ScheduleOperatorRequest $request, Schedule $schedule)
    {
        try {
            $schedule->update([
                'faculty_id' => auth()->user()->operator->faculty_id,
                'course_id' => $request->course_id,
                'classroom_id' => $request->classroom_id,
                'academic_year_id' => activeAcademicYear()->id,
                'quote' => $request->quote,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'day_of_week' => $request->day_of_week,

            ]);
            flashMessage(MessageType::UPDATED->message('Jadwal'));
            return to_route('operators.schedules.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.schedules.index');
        }
    }

    public function destroy(Schedule $schedule)
    {
        try {

            $schedule->delete();
            flashMessage(MessageType::DELETED->message('Jadwal'));
            return to_route('operators.schedules.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('operators.schedules.index');
        }
    }
}
