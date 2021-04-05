<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SalesLimit;
use App\SalesPlayLimit;
use App\User;
use Illuminate\Http\Request;

class SalesLimitController extends Controller
{
    public function index()
    {
        $limits = SalesLimit::whereNull('user_id')->get();

        $salesLimits = SalesLimit::whereNotNull('user_id')->get();

        $globalPlayLimits = SalesPlayLimit::where('type', SalesPlayLimit::GLOBAL)->get();
        $sellerPlayLimits = SalesPlayLimit::where('type', SalesPlayLimit::SELLER)->get();
        $ticketPlayLimits = SalesPlayLimit::where('type', SalesPlayLimit::TICKET)->get();

        return view('admin.sales-limit.index', compact(
            'limits', 'salesLimits', 'globalPlayLimits', 'sellerPlayLimits',
            'ticketPlayLimits'
        ));
    }

    public function update(Request $request)
    {
        $limitIds = $request->limit_ids;

        if ($limitIds) {
            foreach ($limitIds as $key => $limitId) {
                $salesLimit = SalesLimit::findOrFail($limitId);
                $salesLimit->update([
                    'quiniela' => $request->quiniela[$key],
                    'pale' => $request->pale[$key],
                    'super_pale' => $request->super_pale[$key],
                    'tripleta' => $request->tripleta[$key],
                ]);
            }
        }

        $sellerIds = $request->seller_ids;
        if ($sellerIds) {
            SalesLimit::whereNotNull('user_id')
                ->whereNotIn('user_id', $sellerIds)
                ->delete();

            foreach ($sellerIds as $key => $sellerId) {
                SalesLimit::updateOrCreate(
                    ['user_id' => $sellerId],
                    [
                        'quiniela' => $request->quiniela_seller[$key],
                        'pale' => $request->pale_seller[$key],
                        'super_pale' => $request->super_pale_seller[$key],
                        'tripleta' => $request->tripleta_seller[$key]
                    ]
                );
            }
        } else
            SalesLimit::whereNotNull('user_id')->delete();

        //limit play
        //global
        $globalPlayLimitIds = $request->global_play_limit_ids;
        if ($globalPlayLimitIds) {
            SalesPlayLimit::where('type', SalesPlayLimit::GLOBAL)
                ->whereNotIn('id', $globalPlayLimitIds)
                ->delete();

            foreach ($globalPlayLimitIds as $key => $globalPlayLimitId)
                SalesPlayLimit::updateOrCreate(
                    ['id' => $globalPlayLimitId],
                    [
                        'number' => $request->global_numbers[$key],
                        'points' => $request->global_points[$key],
                        'type' => SalesPlayLimit::GLOBAL
                    ]
                );

        } else
            SalesPlayLimit::where('type', SalesPlayLimit::GLOBAL)
                ->delete();

        //seller
        $sellerPlayLimitIds = $request->seller_play_limit_ids;
        if ($sellerPlayLimitIds) {
            SalesPlayLimit::where('type', SalesPlayLimit::SELLER)
                ->whereNotIn('id', $sellerPlayLimitIds)
                ->delete();

            foreach ($sellerPlayLimitIds as $key => $sellerPlayLimitId)
                SalesPlayLimit::updateOrCreate(
                    ['id' => $sellerPlayLimitId],
                    [
                        'number' => $request->seller_numbers[$key],
                        'points' => $request->seller_points[$key],
                        'type' => SalesPlayLimit::SELLER
                    ]
                );

        } else
            SalesPlayLimit::where('type', SalesPlayLimit::SELLER)
                ->delete();

        //ticket
        $ticketPlayLimitIds = $request->ticket_play_limit_ids;
        if ($ticketPlayLimitIds) {
            SalesPlayLimit::where('type', SalesPlayLimit::TICKET)
                ->whereNotIn('id', $ticketPlayLimitIds)
                ->delete();

            foreach ($ticketPlayLimitIds as $key => $ticketPlayLimitId)
                SalesPlayLimit::updateOrCreate(
                    ['id' => $ticketPlayLimitId],
                    [
                        'number' => $request->ticket_numbers[$key],
                        'points' => $request->ticket_points[$key],
                        'type' => SalesPlayLimit::TICKET
                    ]
                );

        } else
            SalesPlayLimit::where('type', SalesPlayLimit::TICKET)
                ->delete();

        return redirect('home');
    }
}
