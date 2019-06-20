<?php

namespace App\Http\Controllers;

use App\Member;
use App\Group;
use App\Menu;
use App\Flavor;
use Illuminate\Http\Request;

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
}
