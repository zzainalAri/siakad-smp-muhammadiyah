<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OperatorRequest;
use App\Http\Resources\Admin\OperatorResource;
use App\Models\Departement;
use App\Models\Faculty;
use App\Models\Operator;
use App\Models\User;
use App\Traits\HasFile;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class OperatorController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('validateDepartement', only: ['store', 'update']),
        ];
    }


    use HasFile;
    public function index()
    {
        $operators = Operator::query()
            ->select(['operators.id', 'operators.employee_number', 'operators.faculty_id', 'operators.departement_id', 'operators.user_id', 'operators.created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->with(['user', 'faculty', 'departement'])
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', fn($query) =>  $query->where('name', 'Operator'));
            })
            ->paginate(request()->load ?? 10);




        return inertia('Admin/Operators/Index', [
            'page_setting' => [
                'title' => 'Operator',
                'subtitle' => 'Menampilkan semua data Operator yang tersedia pada universitas ini'
            ],
            'operators' => OperatorResource::collection($operators)->additional([
                'meta' => [
                    'has_pages' => $operators->hasPages(),
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
        return inertia('Admin/Operators/Create', [
            'page_setting' => [
                'title' => 'Tambah Operator',
                'subtitle' => 'Buat Operator baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.operators.store')
            ],
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'departements' => Departement::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    }

    public function store(OperatorRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'avatar' => $this->upload_file($request, 'avatar', 'operators'),
            ]);

            $user->operator()->create([
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'employee_number' => $request->employee_number,

            ]);


            DB::commit();
            $user->assignRole('Operator');

            flashMessage(MessageType::CREATED->message('Operator'));
            return to_route('admin.operators.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.operators.index');
        }
    }

    public function edit(Operator $operator)
    {
        return inertia('Admin/Operators/Edit', [
            'page_setting' => [
                'title' => 'Edit Operator',
                'subtitle' => 'Edit Operator disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.operators.update', $operator)
            ],
            'operator' => $operator->load(['user']),
            'faculties' => Faculty::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'departements' => Departement::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
        ]);
    }

    public function update(OperatorRequest $request, Operator $operator)
    {
        DB::beginTransaction();
        try {

            $operator->update([
                'faculty_id' => $request->faculty_id,
                'departement_id' => $request->departement_id,
                'employee_number' => $request->employee_number,

            ]);

            $operator->user()->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $operator->user->password,
                'avatar' => $this->update_file($request, $operator->user, 'avatar', 'operators'),
            ]);



            DB::commit();

            flashMessage(MessageType::UPDATED->message('Operator'));
            return to_route('admin.operators.index');
        } catch (Throwable $e) {
            DB::rollBack();
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.operators.index');
        }
    }


    public function destroy(Operator $operator)
    {
        try {
            $this->delete_file($operator->user, 'avatar');

            $operator->delete();
            flashMessage(MessageType::DELETED->message('Operator'));
            return to_route('admin.operators.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.operators.index');
        }
    }
}
