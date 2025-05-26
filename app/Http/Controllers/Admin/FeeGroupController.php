<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeeGroupRequest;
use App\Http\Resources\Admin\FeeGroupResource;
use App\Models\FeeGroup;
use Illuminate\Http\Request;
use Throwable;

class FeeGroupController extends Controller
{
    public function index()
    {
        $feeGroups = FeeGroup::query()
            ->select(['id', 'group', 'amount', 'created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);

        return inertia('Admin/FeeGroups/Index', [
            'page_setting' => [
                'title' => 'Golongan UKT',
                'subtitle' => 'Menampilkan semua data Golongan UKT yang tersedia pada universitas ini'
            ],
            'feeGroups' => FeeGroupResource::collection($feeGroups)->additional([
                'meta' => [
                    'has_pages' => $feeGroups->hasPages(),
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
        return inertia('Admin/FeeGroups/Create', [
            'page_setting' => [
                'title' => 'Tambah Golongan UKT',
                'subtitle' => 'Buat Golongan UKT baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('admin.fee-groups.store')
            ]
        ]);
    }

    public function store(FeeGroupRequest $request)
    {
        try {
            FeeGroup::create([
                'group' =>  $request->group,
                'amount' => $request->amount,
            ]);

            flashMessage(MessageType::CREATED->message('Golongan UKT'));
            return to_route('admin.fee-groups.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.fee-groups.index');
        }
    }

    public function edit(FeeGroup $feeGroup)
    {
        return inertia('Admin/FeeGroups/Edit', [
            'page_setting' => [
                'title' => 'Edit Golongan UKT',
                'subtitle' => 'Edit Golongan UKT disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('admin.fee-groups.update', $feeGroup)
            ],
            'feeGroup' => $feeGroup,
        ]);
    }

    public function update(FeeGroup $feeGroup, FeeGroupRequest $request)
    {
        try {
            $feeGroup->update([
                'group' =>  $request->group,
                'amount' => $request->amount,
            ]);

            flashMessage(MessageType::UPDATED->message('Golongan UKT'));
            return to_route('admin.fee-groups.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.fee-groups.index');
        }
    }


    public function destroy(FeeGroup $feeGroup)
    {
        try {

            $feeGroup->delete();
            flashMessage(MessageType::DELETED->message('Golongan UKT'));
            return to_route('admin.fee-groups.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.fee-groups.index');
        }
    }
}
