<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\MovementHistory;
use App\User;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function update($id, Request $request)
    {
        // Get params
//        $user = $request->user();
        $amount = $request->input('amount');
        $type = $request->input('type');

        $user = User::withTrashed()->findOrFail($id);

        if (!$amount) {
            $data['success'] = false;
            $data['error_message'] = "Es necesario ingresar un monto";
            return $data;
        }

        if (!is_numeric($amount)) {
            $data['success'] = false;
            $data['error_message'] = "Ingrese un valor numérico";
            return $data;
        }

        MovementHistory::create([
            'amount' => $amount,
            'type' => $type,
            'user_id' => $id
        ]);

        if ($type == 2)
            $user->balance -= $amount;
        else
            $user->balance += $amount;

        $user->save();

        $data['success'] = true;
        return $data;
    }
}
