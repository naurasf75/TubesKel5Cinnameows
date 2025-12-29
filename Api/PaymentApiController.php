<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentApiController extends Controller
{
    public function callback(Request $request)
    {
        return response()->json([
            'message' => 'Payment callback received',
            'data' => $request->all()
        ]);
    }
}
