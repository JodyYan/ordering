<?php

namespace App\Http\Controllers;

use App\Member;
use App\Group;
use App\Menu;
use App\Flavor;
use App\Order;
use App\Http\Requests\Orderdata;
use App\Http\Requests\OrderUpdate;
use App\Http\Requests\Paid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\OrderException;

class OrderController extends Controller
{
    public function getMenus()
    {
        $token=request()->bearerToken();
        $member=Member::where('api_token', $token)->first();
        $menuDate=request()->get('menu_date');
        $menus=Menu::where(function ($query) use ($member){
            $query->where('group_id', $member->group_id)
            ->orWhere('group_id', null);
        })
            ->whereDate('menu_date', $menuDate)
            ->with('flavors')
            ->get();

        return $menus;
    }

    public function store(Orderdata $request)
    {
        $token=request()->bearerToken();
        $member=Member::where('api_token', $token)->first();
        $datas=$request->validated();
        $result=[];
        try {
            $result=DB::transaction(function () use ($datas, $member) {
                foreach ($datas['menuArray'] as $data) {
                    $menu=Menu::find($data['menu_id']);
                    if (isset($data['flavor_id'])) {
                        $flavor=Flavor::find($data['flavor_id']);
                        if ($flavor->menu_id != $menu->id) {
                            throw new OrderException(['error' => 'This flavor not belong the menu.'], 422);
                        }
                    }

                    if ($menu->group_id !== null && $menu->group_id != $member->group_id) {
                        throw new OrderException(['error' => 'error menu'], 422);
                    }

                    if ($menu->quantity_limit !== null && $data['quantity'] > $menu->quantity_limit) {
                        throw new OrderException(['error' => 'This item only remain ' . $menu->quantity_limit . '.'], 422);
                    }


                    if ($menu->flavors->count() != 0 && !isset($data['flavor_id'])) {
                        throw new OrderException(['error' => 'Please choose a flavor.'], 422);
                    }

                    $data['user_id']=$member->id;
                    $data['menu_name']=$menu->name;
                    $data['menu_price']=$menu->price;
                    $data['menu_date']=$menu->menu_date;

                    if (isset($data['flavor_id'])) {
                        $data['flavor_choice']=$flavor->choice;
                    }

                    if ($menu->quantity_limit !== null) {
                        $menu->quantity_limit = $menu->quantity_limit - $data['quantity'];
                        $menu->save();
                    }

                    unset($data['flavor_id']);
                    $result[]=Order::create($data);
                }
                return $result;
            });
        } catch (Exception $e) {
            Log::critical('Log message'. $e->getMessage());
            return $e->getMessage();
        }


        return $result;
    }

    public function show()
    {
        $token=request()->bearerToken();
        $startDate=request()->get('start_date');
        $endDate=request()->get('end_date');
        $member=Member::where('api_token', $token)->first();
        $orders=Order::where('user_id', $member->id)
            ->whereDate('menu_date', '>=', $startDate)
            ->whereDate('menu_date', '<=', $endDate)
            ->get();

        return $orders;
    }

    public function update(Order $order, OrderUpdate $request)
    {
        $token=request()->bearerToken();
        $member=Member::where('api_token', $token)->first();
        if ($member->id != $order->user_id) {
            return response(['error' => 'Please choose true order.'], 422);
        }

        $data=$request->validated();

        if (isset($data['flavor_id'])) {
            $flavor=Flavor::find($data['flavor_id']);
            if ($flavor->menu_id != $order->menu_id) {
                return response(['error' => 'This flavor not belong the menu.'], 422);
            }
        }

        $menu=Menu::find($order->menu_id);

        if (isset($data['quantity']) && $menu->quantity_limit !== null) {
            $dq=$data['quantity'];
            $mql=$menu->quantity_limit;
            $oq=$order->quantity;
            $disparity=$dq-$oq;

            if ($data['quantity'] < $order->quantity) {
                $mql=$mql-$disparity;
                $menu->quantity_limit=$mql;
                $menu->save();
            }

            if ($dq > $oq) {

                if ($mql > $disparity) {
                    $mql=$mql-$dq;
                    $menu->quantity_limit=$mql;
                    $menu->save();
                }

                if ($mql < $disparity) {
                    return response(['error' => 'This item only remain ' . $menu->quantity_limit . '.'], 422);
                }
            }
        }

        if (isset($data['flavor_id'])) {
            $data['flavor_choice']=$flavor->choice;
        }


        unset($data['flavor_id']);
        $order->update($data);
        return $order;
    }

    public function destroy(Order $order)
    {
        $token=request()->bearerToken();
        $member=Member::where('api_token', $token)->first();
        if ($member->id != $order->user_id) {
            return response(['error' => 'Please choose true order.'], 422);
        }

        $menu=Menu::find($order->menu_id);

        if ($menu->quantity_limit !== null) {
            $mql=$menu->quantity_limit;
            $oq=$order->quantity;
            $mql=$mql+$oq;
            $menu->quantity_limit=$mql;
            $menu->save();
        }

        $order->delete();
        return 'already delete';
    }

    public function paidUpdate(Paid $request)
    {
        $data=$request->validated();
        $orders=Order::whereDate('menu_date', '>=', $data['start_date'])
            ->whereDate('menu_date', '<=', $data['end_date'])
            ->whereIn('user_id', $data['member_id'])
            ->get();
        foreach ($orders as $order) {
            $order->paid =! $order->paid;
            $order->save();
        }
        return 'ok';
    }
}
