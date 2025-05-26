<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DepartementRequest;
use App\Http\Resources\Admin\DepartementResource;
use App\Models\Departement;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Throwable;

class DepartementController extends Controller
{
    public function index()
    {
        $departements = Departement::query()
            ->select(['id', 'name', 'faculty_id', 'code', 'slug', 'created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with('faculty')
            ->paginate(request()->load ?? 10);

        return inertia('Admin/Departements/Index', [
            'page_setting' => [
                'title' => 'Program Study',
                'subtitle' => 'Menampilkan semua data Program Study yang tersedia pada universitas ini'
            ],
            'departements' => DepartementResource::collection($departements)->additional([
                'meta' => [
                    'has_pages' => $departements->hasPages(),
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
        return inertia('Admin/Departements/Create', [
            'page_setting' => [
                'title' => 'Buat Program Studi',
                'subtitle' => 'Buat program baru baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.departements.store')
            ],
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ])
        ]);
    }

    public function store(DepartementRequest $request)
    {
        try {
            Departement::create([
                'faculty_id' => $request->faculty_id,
                'name' =>  $request->name,
                'code' => str()->random(6),
            ]);

            flashMessage(MessageType::CREATED->message('Program Studi'));
            return to_route('admin.departements.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.departements.index');
        }
    }

    public function Edit(Departement $departement)
    {
        return inertia('Admin/Departements/Edit', [
            'page_setting' => [
                'title' => 'Edit Program Studi',
                'subtitle' => 'Edit Program Studi di sini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.departements.update', $departement)
            ],
            'departement' => $departement->load('faculty'),
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ])
        ]);
    }

    public function update(DepartementRequest $request, Departement $departement)
    {
        try {
            $departement->update([
                'name' =>  $request->name,
                'faculty_id' => $request->faculty_id,

            ]);

            flashMessage(MessageType::UPDATED->message('Program Studi'));
            return to_route('admin.departements.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.departements.index');
        }
    }

    public function destroy(Departement $departement)
    {
        try {

            $departement->delete();
            flashMessage(MessageType::DELETED->message('Program Studi'));
            return to_route('admin.departements.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.departements.index');
        }
    }
}
