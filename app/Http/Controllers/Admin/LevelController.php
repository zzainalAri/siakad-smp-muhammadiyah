<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LevelRequest;
use App\Http\Resources\Admin\LevelResource;
use App\Models\Level;
use Illuminate\Http\Request;
use Throwable;

class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::query()
            ->select(['id', 'name', 'slug', 'created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);

        return inertia('Admin/Levels/Index', [
            'page_setting' => [
                'title' => 'Tingkat',
                'subtitle' => 'Menampilkan semua data tingkat (kelas 7, 8, 9)',
            ],
            'levels' => LevelResource::collection($levels)->additional([
                'meta' => [
                    'has_pages' => $levels->hasPages(),
                ],
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ],
        ]);
    }

    public function create()
    {
        return inertia('Admin/Levels/Create', [
            'page_setting' => [
                'title' => 'Tambah Tingkat',
                'subtitle' => 'Buat tingkat baru ',
                'method' => 'POST',
                'action' => route('admin.levels.store'),
            ],
            'levelOptions' => [
                ['value' => 'Kelas 7', 'label' => 'Kelas 7'],
                ['value' => 'Kelas 8', 'label' => 'Kelas 8'],
                ['value' => 'Kelas 9', 'label' => 'Kelas 9'],
            ]
        ]);
    }

    public function store(LevelRequest $request)
    {
        try {
            Level::create([
                'name' => $request->name,
                'slug' => \Str::slug($request->name),
            ]);

            flashMessage(MessageType::CREATED->message('Tingkat'));
            return to_route('admin.levels.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.levels.index');
        }
    }

    public function edit(Level $level)
    {
        return inertia('Admin/Levels/Edit', [
            'page_setting' => [
                'title' => 'Edit Tingkat',
                'subtitle' => 'Ubah data tingkat. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.levels.update', $level),
            ],
            'level' => $level,
            'levelOptions' => [
                ['value' => 'Kelas 7', 'label' => 'Kelas 7'],
                ['value' => 'Kelas 8', 'label' => 'Kelas 8'],
                ['value' => 'Kelas 9', 'label' => 'Kelas 9'],
            ]
        ]);
    }

    public function update(LevelRequest $request, Level $level)
    {
        try {
            $level->update([
                'name' => $request->name,
                'slug' => \Str::slug($request->name),
            ]);

            flashMessage(MessageType::UPDATED->message('Tingkat'));
            return to_route('admin.levels.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.levels.index');
        }
    }

    public function destroy(Level $level)
    {
        try {
            if (Level::count() <= 3) {
                flashMessage('Minimal 3 tingkat harus tersedia.', 'error');
                return to_route('admin.levels.index');
            }

            $level->delete();

            flashMessage(MessageType::DELETED->message('Tingkat'));
            return to_route('admin.levels.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.levels.index');
        }
    }
}