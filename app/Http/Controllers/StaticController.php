<?php

namespace App\Http\Controllers;

use DB;
use App\Order;
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

            'list' => [
                $personalPaid,
            ]
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
            ->select('members.name', 'orders.menu_name', 'orders.user_rice', 'orders.user_vegetable', 'orders.note')
            ->get();

        return [
            'statistic' => $result,
            'list' => $list,
        ];
    }
}
