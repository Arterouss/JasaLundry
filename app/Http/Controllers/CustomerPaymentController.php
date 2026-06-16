<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Midtrans\Config;
use Midtrans\Snap;

class CustomerPaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Config::$is3ds = env('MIDTRANS_IS_3DS');
    }

    /**
     * Menampilkan halaman ringkasan order
     */
    public function checkout(Order $order)
    {
        if ($order->payment_status === 'paid') {
            return redirect()->route('customer.dashboard')->with('success', 'Pesanan ini sudah lunas.');
        }

        $order->load(['service']);
        $user = Auth::user(); 

        // Mengarah ke file customer/pembayaran.blade.php kamu
        return view('customer.pembayaran', compact('order', 'user'));
    }

    /**
     * Menggenerate Token Midtrans via AJAX Fetch
     */
    public function processPayment(Order $order)
    {
        $params = [
            'transaction_details' => [
                'order_id' => 'LDR-' . $order->id . '-' . time(),
                'gross_amount' => (int) $order->grand_total, // PERBAIKAN: Kolom database yang benar
            ],
            'customer_details' => [
                'first_name' => $order->customer->name ?? $order->walk_in_name ?? 'Pelanggan',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json([
                'status' => 'success',
                'token' => $snapToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Jalur sukses redirect untuk mengubah status lunas
     */
    public function paymentSuccessRedirect(Order $order)
    {
        $order->update([
            'payment_status' => 'paid'
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Pembayaran Anda berhasil terkonfirmasi!');
    }
}