<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Requests\Bossdata;
use App\Http\Requests\Bossupdate;
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

    public function show(Boss $boss) {
        $token=request()->bearerToken();
        if ($token!==$boss->api_token) {
            return 'token error';
        }
        $boss=Boss::findorfail($boss)->first();
        return ['name'=>$boss->name, 'account'=>$boss->account];
    }

    public function update(Boss $boss, Bossupdate $request) {
        $token=request()->bearerToken();
        if ($token!==$boss->api_token) {
            return 'token error';
        }

        $data=$request->validated();
        if (isset($data['password'])) {
            $data['password']=Hash::make($data['password']);
        }
        $boss->update($data);
        return $boss;
    }

    public function logout (Boss $boss)
    {
        $token=request()->bearerToken();
        if ($token!==$boss->api_token) {
            return 'token error';
        }

        $boss->api_token=null;
        $boss->save();
        return 'already logout';
    }
}
