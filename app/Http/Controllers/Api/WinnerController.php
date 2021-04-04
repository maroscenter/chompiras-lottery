<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\MovementHistory;
use App\Winner;
use Illuminate\Http\Request;

class WinnerController extends Controller
{
    public function paid($id, Request $request)
    {
        $winner = Winner::find($id);
        $user = $request->user();

        if (!$winner) {
            $data['success'] = false;
            $data['error_message'] = "No existe el premio con id $id";
            return $data;
        }

        if ($winner->user_id != $user->id) {
            $data['success'] = false;
            $data['error_message'] = "No puede pagar el premio #$id";
            return $data;
        }

        if ($winner->paid) {
            $data['success'] = false;
            $data['error_message'] = "El premio #$id ya fue pagado";
            return $data;
        }

        $winner->update([
            'paid' => true
        ]);

        //movement
        MovementHistory::create([
            'description' => "Premio #$id pagado",
            'amount' => $winner->reward,
            'user_id' => $user->id
        ]);

        //balance sheets
        $user->balance += $winner->reward;
        $user->save();

        $data['success'] = true;
        return $data;
    }
}
