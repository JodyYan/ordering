<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Group;
use App\Boss;
use App\Flavor;
use App\Http\Requests\Addmenu;
use App\Http\Requests\Addflavor;
use App\Http\Requests\UpdateMenu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function store(Addmenu $request)
    {
        $data=$request->validated();
        return Menu::create($data);
    }

    public function flavorstore(Addflavor $request)
    {
        $datas=$request->validated();
        $result=[];
        foreach ($datas['flavorArray'] as $data) {
            $result[]=Flavor::create($data);
        }
        return $result;
    }

    public function index()
    {
        $startDate=request()->get('start_date');
        $endDate=request()->get('end_date');
        $menus=Menu::with('flavors')
            ->whereDate('menu_date', '>=', $startDate)
            ->whereDate('menu_date', '<=', $endDate)
            ->get();
        return $menus;
    }

    public function show()
    {
        $daily=request()->get('menu_date');
        $menus=Menu::with('flavors')
            ->whereDate('menu_date', '=', $daily)
            ->get();
        return $menus;
    }

    public function menuUpdate(UpdateMenu $request, Menu $menu)
    {
        $data=$request->validated();
        $menu->update($data);
        return $menu;
    }

    public function flavorUpdate(Flavor $flavor)
    {
        $flavor->update(request()->validate([
            'choice' => ['string']
        ]));
        $menus=Menu::with('flavors')
            ->where('id', '=', $flavor->menu_id)
            ->get();
        return $menus;
    }

    public function menuDestroy(Menu $menu)
    {
        Menu::with('flavors')
            ->where('id', '=', $menu->id)
            ->delete();
        return 'already delete this menu';
    }

    public function flavorDestroy(Flavor $flavor)
    {
        $flavor->delete();
        Menu::with('flavors')
            ->where('id', '=', $flavor->menu_id)
            ->get();
        return 'already delete this flavor';
    }
}
