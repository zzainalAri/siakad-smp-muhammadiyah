<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FacultyRequest;
use App\Http\Resources\Admin\FacultyResource;
use App\Models\Faculty;
use App\Traits\HasFile;
use Illuminate\Http\Request;
use Throwable;

class FacultyController extends Controller
{
    use HasFile;

    public function index()
    {
        $faculties = Faculty::query()
            ->select(['id', 'name', 'code', 'logo', 'slug', 'created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);

        return inertia('Admin/Faculties/Index', [
            'page_setting' => [
                'title' => 'Fakultas',
                'subtitle' => 'Menampilkan semua data fakultas yang tersedia pada universitas ini'
            ],
            'faculties' => FacultyResource::collection($faculties)->additional([
                'meta' => [
                    'has_pages' => $faculties->hasPages(),
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
        return inertia('Admin/Faculties/Create', [
            'page_setting' => [
                'title' => 'Tambah Fakultas',
                'subtitle' => 'Buat fakultas baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.faculties.store')
            ]
        ]);
    }

    public function store(FacultyRequest $request)
    {
        try {
            Faculty::create([
                'name' =>  $request->name,
                'code' => str()->random(6),
                'logo' => $this->upload_file($request, 'logo', 'faculties'),
            ]);

            flashMessage(MessageType::CREATED->message('Fakultas'));
            return to_route('admin.faculties.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.faculties.index');
        }
    }
    public function Edit(Faculty $faculty)
    {
        return inertia('Admin/Faculties/Edit', [
            'page_setting' => [
                'title' => 'Edit Fakultas',
                'subtitle' => 'Edit fakultas di sini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.faculties.update', $faculty)
            ],
            'faculty' => $faculty,
        ]);
    }

    public function update(FacultyRequest $request, Faculty $faculty)
    {
        try {
            $faculty->update([
                'name' =>  $request->name,
                'logo' => $this->update_file($request, $faculty, 'logo', 'faculties'),
            ]);

            flashMessage(MessageType::UPDATED->message('Fakultas'));
            return to_route('admin.faculties.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.faculties.index');
        }
    }

    public function destroy(Faculty $faculty)
    {
        try {
            $this->delete_file($faculty, 'logo');

            $faculty->delete();
            flashMessage(MessageType::DELETED->message('Fakultas'));
            return to_route('admin.faculties.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.faculties.index');
        }
    }
}
