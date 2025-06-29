<?php

namespace App\Http\Controllers;

use App\Enums\FeeStatus;
use App\Models\Fee;
use Exception;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $request->fee_code,
                'gross_amount' => $request->gross_amount
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
            ],

        ];


        try {
            Fee::updateOrCreate([
                'student_id' => auth()->user()->student?->id,
                'academic_year_id' => activeAcademicYear()->id,
                'semester' => auth()->user()->student->semester
            ], [
                'fee_code' => $request->fee_code,
                'student_id' => auth()->user()->student?->id,
                'semester' => auth()->user()->student?->semester,
                'fee_group_id' => auth()->user()->student?->fee_group_id,
                'academic_year_id' => activeAcademicYear()->id,
            ]);

            $snapToken = Snap::getSnapToken($params);
            return response()->json([
                'snapToken' => $snapToken,

            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $signatureKey = signatureMidtrans($request->order_id, $request->status_code, $request->gross_amount, $serverKey);

        if ($request->signature_key !== $signatureKey) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        $fee = Fee::query()
            ->where('fee_code', $request->order_id)
            ->first();


        if (!$fee) {
            return response()->json([
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }

        switch ($request->transaction_status) {
            case 'settlement':
                $fee->status = FeeStatus::SUCCESS->value;
                $fee->save();

                return response()->json([
                    'message' => 'Berhasil melakukan pembayaran'
                ]);
                break;
            case 'capture':
                $fee->status = FeeStatus::SUCCESS->value;
                $fee->save();

                return response()->json([
                    'message' => 'Berhasil melakukan pembayaran'
                ]);
                break;
            case 'pending':
                $fee->status = FeeStatus::PENDING->value;
                $fee->save();

                return response()->json([
                    'message' => 'Pembayaran Tertunda'
                ]);
                break;
            case 'expire':
                $fee->status = FeeStatus::FAILED->value;
                $fee->save();

                return response()->json([
                    'message' => 'Pembayaran Kadaluarsa'
                ]);
                break;
            case 'cancel':
                $fee->status = FeeStatus::FAILED->value;
                $fee->save();

                return response()->json([
                    'message' => 'Berhasil Pembayaran Dibatalkan'
                ]);
                break;
            default:
                return response()->json([
                    'message' => 'Status Transaksi tidak diketahui'
                ], 400);
        };
    }

    public function success()
    {
        return inertia('Payments/Success');
    }
}
