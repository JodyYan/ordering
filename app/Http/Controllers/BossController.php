<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\Bossdata;
use App\Boss;

class BossController extends Controller
{
    public function store(Bossdata $request)
    {
        $data=$request->validated();
        $data['password']=Hash::make($data['password']);
        return Boss::create($data);
    }

    public function login()
    {
        $account=request()->get('account');
        $password=request()->get('password');
        if (!Boss::where('account', $account)->exists()) {
            return 'error account';
        }

        $boss=Boss::where('account', $account)->first();
        if (!Hash::check($password, $boss->password)) {
            return 'error password';
        }
        $token=Str::random(60);
        $boss->api_token=$token;
        $boss->save();
        return $boss;
    }
}
