<?php

namespace App\Http\Controllers;

use App\Member;
use App\Group;
use App\Menu;
use App\Flavor;
use App\Order;
use App\Http\Requests\Orderdata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function getMenus()
    {
        $token=request()->bearerToken();
        $member=Member::where('api_token', $token)->first();
        $menuDate=request()->get('menu_date');
        $menus=Menu::where('group_id', $member->group_id)->whereDate('menu_date', $menuDate)->with('flavors')->get();

        return $menus;
    }

    public function store(Orderdata $request)
    {
        $token=request()->bearerToken();
        $member=Member::where('api_token', $token)->first();
        $data=$request->validated();
        $menu=Menu::find($data['menu_id']);
        if (isSet($data['flavor_id'])) {
            $flavor=Flavor::find($data['flavor_id']);
            if ($flavor->menu_id != $menu->id) {
                return 'This flavor not belong the menu.';
            }
        }

        if ($menu->group_id != null && $menu->group_id != $member->group_id) {
            return 'error menu';
        }

        if ($menu->quantity_limit != null && $data['quantity'] > $menu->quantity_limit) {
            return 'This item only remain ' . $menu->quantity_limit . '.';
        }


        if ($menu->flavors->count() != 0 && !isSet($data['flavor_id'])) {
            return 'Please choose a flavor.';
        }

        $data['user_id']=$member->id;
        $data['menu_name']=$menu->name;
        $data['menu_price']=$menu->price;
        $data['menu_date']=$menu->menu_date;

        if (isSet($data['flavor_id'])) {
            $data['flavor_choice']=$flavor->choice;
        }

        if ($menu->quantity_limit != null) {
            $menu->quantity_limit = $menu->quantity_limit - $data['quantity'];
            $menu->save();
        }

        unset($data['menu_id']);
        unset($data['flavor_id']);

        return Order::create($data);
    }
}
