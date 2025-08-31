<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HasFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Throwable;

class UserController extends Controller
{
    use HasFile;

    public function index()
    {
        $users = User::query()
            ->whereHas('roles', function ($query) {
                $query->whereNotIn('name', ['Admin', 'Teacher', 'Student']);
            })
            ->with(['roles'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->latest()
            ->paginate(request()->load ?? 10)
            ->withQueryString();

        return inertia('Admin/Users/Index', [
            'page_setting' => [
                'title' => 'Pengguna Lainnya',
                'subtitle' => 'Menampilkan semua data pengguna Selain Admin, Guru, dan Siswa',
            ],
            'users' => UserResource::collection($users)->additional([
                'meta' => [
                    'has_pages' => $users->hasPages(),
                ]
            ]),
            'state' => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ]
        ]);
    }

    public function create()
    {
        $roles = Role::query()->select(['name'])->whereNotIn('name', ['Admin', 'Teacher', 'Student'])->orderBy('name')->get()->map(fn($item) => [
            'value' => $item->name,
            'label' => $item->name
        ]);

        return inertia('Admin/Users/Create', [
            'page_setting' => [
                'title' => 'Tambah Pengguna',
                'subtitle' => 'Buat pengguna baru di sini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.users.store'),
            ],
            'roles' => $roles,
        ]);
    }

    public function store(UserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar' => $this->upload_file($request, 'avatar', 'users'),
            ]);

            $user->assignRole($request->role);

            flashMessage(MessageType::CREATED->message('Pengguna'));

            return to_route('admin.users.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return back();
        }
    }

    public function edit(User $user)
    {
        $roles = Role::query()->select(['name'])->whereNotIn('name', ['Admin', 'Teacher', 'Student'])->orderBy('name')->get()->map(fn($item) => [
            'value' => $item->name,
            'label' => $item->name
        ]);
        return inertia('Admin/Users/Edit', [
            'page_setting' => [
                'title' => 'Edit Pengguna',
                'subtitle' => 'Edit Pengguna di sini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.users.update', $user)
            ],
            'user' => $user->load('roles'),
            'role' => $user->getRoleNames()->first(),
            'roles' => $roles,

        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        try {
            if ($request->password) {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'avatar' => $this->update_file($request, $user, 'avatar', 'users'),

                ]);
            } else {

                $user->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'avatar' => $this->update_file($request, $user, 'avatar', 'users'),

                ]);
            }


            $user->syncRoles($request->role);

            flashMessage(MessageType::CREATED->message('Pengguna'));

            return to_route('admin.users.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return back();
        }
    }

    public function destroy(User $user)
    {
        try {

            $this->delete_file($user, 'avatar');
            $user->delete();

            flashMessage(MessageType::DELETED->message('Pengguna'));

            return to_route('admin.users.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.users.index');
        }
    }
}
