<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SalesLimit;
use App\User;
use Illuminate\Http\Request;

class SalesLimitController extends Controller
{
    public function index()
    {
        $limits = SalesLimit::whereNull('user_id')->get();

        $salesLimits = SalesLimit::whereNotNull('user_id')->get();

        return view('sales-limit.index', compact('limits', 'salesLimits'));

    }

    public function update(Request $request)
    {
        $limitIds = $request->limit_ids;

        if($limitIds) {
            foreach ($limitIds as $key => $limitId) {
                $salesLimit = SalesLimit::findOrFail($limitId);
                $salesLimit->quiniela = $request->quiniela[$key];
                $salesLimit->pale = $request->pale[$key];
                $salesLimit->super_pale = $request->super_pale[$key];
                $salesLimit->tripleta = $request->tripleta[$key];
                $salesLimit->save();
            }
        }

        $sellerIds = $request->seller_ids;

        if (!$request->has('seller_ids'))
            SalesLimit::whereNotNull('user_id')->delete();

        if($sellerIds) {
            SalesLimit::whereNotNull('user_id')->whereNotIn('user_id', $sellerIds)->delete();

            foreach ($sellerIds as $key => $sellerId) {
                $seller = User::findOrFail($sellerId);

                $userLimit = $seller->sales_limit;

                if(!$userLimit) {
                    $userLimit = new SalesLimit();
                    $userLimit->user_id = $sellerId;
                }
                $userLimit->quiniela = $request->quiniela_seller[$key];
                $userLimit->pale = $request->pale_seller[$key];
                $userLimit->super_pale = $request->super_pale_seller[$key];
                $userLimit->tripleta = $request->tripleta_seller[$key];
                $userLimit->save();
            }
        }

        return redirect('home');
    }
}
