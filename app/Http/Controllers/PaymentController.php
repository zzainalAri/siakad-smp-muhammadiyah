<?php

namespace App\Http\Controllers;

use App\Enums\FeeStatus;
use App\Enums\PaymentStatus;
use App\Models\Fee;
use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                'order_id' => $request->order_id,
                'gross_amount' => $request->gross_amount
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
            ],

        ];


        try {
            Payment::updateOrCreate([
                'student_id' => Auth::user()->student?->id,
                'fee_id' => $request->fee_id,
            ], [
                'transaction_code' => $request->order_id,
                'student_id' => Auth::user()->student?->id,
                'amount_paid' => $request->gross_amount,
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
        $signatureKey = signatureMidtrans(
            $request->order_id,
            $request->status_code,
            $request->gross_amount,
            $serverKey
        );

        if ($request->signature_key !== $signatureKey) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        $payment = Payment::query()
            ->where('transaction_code', $request->order_id)
            ->first();


        if (!$payment) {
            return response()->json([
                'message' => 'Pembayaran tidak ditemukan'
            ], 404);
        }

        switch ($request->transaction_status) {
            case 'settlement':
                $payment->status = PaymentStatus::SUCCESS->value;
                $payment->fee->update(['status' => FeeStatus::PAID->value]);
                $payment->payment_date = now();
                $payment->save();



                return response()->json([
                    'message' => 'Berhasil melakukan pembayaran'
                ]);
                break;
            case 'capture':
                $payment->status = PaymentStatus::SUCCESS->value;
                $payment->fee->update(['status' => FeeStatus::PAID->value]);
                $payment->payment_date = now();
                $payment->save();


                return response()->json([
                    'message' => 'Berhasil melakukan pembayaran'
                ]);
                break;
            case 'pending':
                $payment->status = PaymentStatus::PENDING->value;
                $payment->fee->update(['status' => FeeStatus::UNPAID->value]);
                $payment->save();



                return response()->json([
                    'message' => 'Pembayaran Tertunda'
                ]);
                break;
            case 'expire':
                $payment->status = PaymentStatus::FAILED->value;
                $payment->fee->update(['status' => FeeStatus::UNPAID->value]);
                $payment->save();




                return response()->json([
                    'message' => 'Pembayaran Kadaluarsa'
                ]);
                break;
            case 'cancel':
                $payment->status = PaymentStatus::FAILED->value;
                $payment->fee->update(['status' => FeeStatus::UNPAID->value]);
                $payment->save();



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
