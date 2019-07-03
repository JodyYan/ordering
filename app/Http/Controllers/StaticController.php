<?php

namespace App\Http\Controllers;

use DB;
use App\Order;
use App\Member;
use Illuminate\Http\Request;

class StaticController extends Controller
{
    public function orderCount()
    {
        $startDate=request()->get('start_date');
        $endDate=request()->get('end_date');
        $groupId=request()->get('group_id');
        $query=Order::whereDate('menu_date', '>=', $startDate)
            ->whereDate('menu_date', '<=', $endDate);
        if ($groupId !== null) {
            $query->whereHas('member', function ($findGroup) use ($groupId)
            {
                $findGroup->where('group_id', $groupId);
            });
        }

        $totalPrice=(clone $query)->select(DB::raw("SUM(menu_price * quantity) as total_price"))
            ->value('total_price');
        $paid=(clone $query)->where('paid', 1)
            ->select(DB::raw("SUM(menu_price * quantity) as paid"))
            ->value('paid');
        $unpaid=(clone $query)->where('paid', 0)
            ->select(DB::raw("SUM(menu_price * quantity) as unpaid"))
            ->value('unpaid');
        $personalPaid=(clone $query)
            ->join('members', 'orders.user_id', '=', 'members.id')
            ->select('members.name', 'user_id', DB::raw("SUM(menu_price * quantity) as person_paid"), DB::raw("SUM(orders.paid) > 0 as payment_status"))
            ->groupBy('user_id')
            ->get();

        return [
            'statistic' => [
                'total_price' => $totalPrice,
                'paid' => $paid,
                'unpaid' => $unpaid,
            ],

            'list' => $personalPaid,
        ];
    }

    public function menuCount()
    {
        $date=request()->get('date');
        $groupId=request()->get('group_id');
        $query=Order::whereDate('menu_date', $date);
        if ($groupId !== null) {
            $query->whereHas('member', function ($findGroup) use ($groupId)
            {
                $findGroup->where('group_id', $groupId);
            });
        }
        $result=(clone $query)
            ->select('menu_name', DB::raw("SUM(menu_price * quantity) as total_price"), DB::raw("SUM(quantity) as total_amount"))
            ->groupBy('menu_name')
            ->get();

        $list=(clone $query)
            ->join('members', 'orders.user_id', '=', 'members.id')
            ->select('members.name', 'orders.menu_name', 'orders.quantity', 'orders.user_rice', 'orders.user_vegetable', 'orders.note')
            ->get();

        $ricies=(clone $query)
            ->select('user_rice', 'quantity')
            ->get();
        $totalRice=0;
        $exchange=[
            null => 0,
            1 => 1,
            2 =>0.5,
            3 => 0.33,
            4 => 0.66,
            5 => 0.25,
            6 => 0,
            7 => 1.5
        ];
        foreach ($ricies as $rice) {
            $totalRice+=$exchange[$rice->user_rice] * $rice->quantity;
        }
        return [
            'total_rice' => $totalRice,
            'statistic' => $result,
            'list' => $list,
        ];
    }

    public function personalCount()
    {
        $startDate=request()->get('start_date');
        $endDate=request()->get('end_date');
        $token=request()->bearerToken();
        $member=Member::where('api_token', $token)->first();
        $query=Order::whereDate('menu_date', '>=', $startDate)
            ->whereDate('menu_date', '<=', $endDate)
            ->where('user_id', $member->id);

        $totalPrice=(clone $query)->select(DB::raw("SUM(menu_price * quantity) as total_price"))
            ->value('total_price');
        $paymentStatus=(clone $query)->select(DB::raw("SUM(orders.paid) > 0 as payment_status"))
            ->value('payment_status');
        $personalPaid=(clone $query)
            ->select('menu_name', 'menu_price', 'menu_date', 'user_rice', 'user_vegetable', 'flavor_choice', 'note')
            ->get();

        return [
            'total_price' => $totalPrice,
            'payment_status' => $paymentStatus,
            'list' => $personalPaid,
        ];
    }
}
