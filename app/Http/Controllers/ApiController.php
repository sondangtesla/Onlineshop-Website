<?php

namespace App\Http\Controllers;

use App\Models\Coba;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function payment_handler(Request $request)
    {
        $signature_key = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . env('MIDTRANS_SERVER_KEY'));

        if($signature_key != $request->signature_key)
        {
            return abort(404);
        }

        $order = Coba::where('order_id', $request->order_id)->first();
        $order->update(['status_message'=>$request->transaction_status, 'status_code'=>200]);
        return "Pembayaran kamu berhasil";
    }
}
