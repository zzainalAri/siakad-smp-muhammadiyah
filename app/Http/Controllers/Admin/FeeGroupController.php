<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MessageType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeeGroupRequest;
use App\Http\Resources\Admin\FeeGroupResource;
use App\Models\FeeGroup;
use App\Models\Level;
use Illuminate\Http\Request;
use Throwable;

class FeeGroupController extends Controller
{
    public function index()
    {
        $feeGroups = FeeGroup::query()
            ->select(['fee_groups.id', 'fee_groups.level_id', 'fee_groups.amount', 'fee_groups.created_at'])
            ->filter(request()->only(['search']))
            ->with(['level'])
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);

        return inertia('Admin/FeeGroups/Index', [
            'page_setting' => [
                'title' => 'Pengaturan',
                'subtitle' => 'Menampilkan semua data pengaturan spp yang tersedia pada Sekolah ini'
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



    public function edit(FeeGroup $feeGroup)
    {
        return inertia('Admin/FeeGroups/Edit', [
            'page_setting' => [
                'title' => 'Edit Pengaturan SPP',
                'subtitle' => 'Edit Pengaturan SPP disini. Pengaturan SPP yang sudah diedit akan diterapkan pada tahun ajaran baru',
                'method' => 'PUT',
                'action' => route('admin.fee-groups.update', $feeGroup)
            ],
            'levels' => Level::query()->select(['id', 'name'])->orderBy('name')->get()->map(fn($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ]),
            'feeGroup' => $feeGroup,
        ]);
    }

    public function update(FeeGroup $feeGroup, FeeGroupRequest $request)
    {
        try {
            $feeGroup->update([
                'level_id' =>  $request->level_id,
                'amount' => $request->amount,
            ]);

            flashMessage(MessageType::UPDATED->message('Pengaturan SPP'));
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
            flashMessage(MessageType::DELETED->message('Pengaturan SPP'));
            return to_route('admin.fee-groups.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('admin.fee-groups.index');
        }
    }
}
