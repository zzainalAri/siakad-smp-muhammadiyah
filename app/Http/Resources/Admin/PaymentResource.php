<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_code' => $this->transaction_code,
            'payment_date' => $this->payment_date,
            'amount_paid' => $this->amount_paid,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'fee' => $this->whenLoaded('fee', [
                'id' => $this->fee?->id,
                'fee_code' => $this->fee?->fee_code,
                'status' => $this->fee?->status,
                'semester' => $this->fee?->academicYear?->semester,
                'billing_date' => $this->fee?->billing_date,
                'academic_year' => $this->fee?->academicYear?->name,
            ]),
        ];
    }
}
