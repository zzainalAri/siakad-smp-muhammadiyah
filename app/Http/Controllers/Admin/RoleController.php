<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Http\Resources\Admin\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Throwable;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::query()
            ->select(['id', 'name', 'guard_name', 'created_at'])
            ->when(request()->search ?? null, function ($query, $search) {
                $query->whereAny([
                    'name',
                    'guard_name',
                ], 'REGEXP', $search);
            })
            ->when(request()->field ?? null &&  request()->direction ?? null, fn($query) => $query->orderBy(request()->field, request()->direction))
            ->paginate(request()->load ?? 10);

        return inertia('Admin/Roles/Index', [
            'page_setting' => [
                'title' => 'Peran',
                'subtitle' => 'Menampilkan semua data Peran yang tersedia pada universitas ini'
            ],
            'roles' => RoleResource::collection($roles)->additional([
                'meta' => [
                    'has_pages' => $roles->hasPages(),
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
        return inertia('Admin/Roles/Create', [
            'page_setting' => [
                'title' => 'Tambah Peran',
                'subtitle' => 'Buat Peran baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.roles.store')
            ]
        ]);
    }

    public function store(RoleRequest $request)
    {
        try {
            Role::create([
                'name' =>  $request->name,
            ]);

            flashMessage(MessageType::CREATED->message('Role'));
            return to_route('admin.roles.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.roles.index');
        }
    }

    public function edit(Role $role)
    {
        return inertia('Admin/Roles/Edit', [
            'page_setting' => [
                'title' => 'Edit Peran',
                'subtitle' => 'Edit Peran disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.roles.update', $role)
            ],
            'role' => $role,
        ]);
    }

    public function update(RoleRequest $request, Role $role)
    {
        try {
            $role->update([
                'name' =>  $request->name,
            ]);

            flashMessage(MessageType::UPDATED->message('Role'));
            return to_route('admin.roles.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.roles.index');
        }
    }

    public function destroy(Role $role)
    {
        try {

            $role->delete();
            flashMessage(MessageType::DELETED->message('Peran'));
            return to_route('admin.roles.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.roles.index');
        }
    }
}
