<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Member;
use App\Http\Requests\Memberdata;
use App\Http\Requests\Memberupdate;

class MemberController extends Controller
{
    public function store (Memberdata $request)
    {
        $data=$request->validated();
        $data['password']=Hash::make($data['password']);
        return Member::create($data);
    }

    public function login()
    {
        $account=request()->get('account');
        $password=request()->get('password');
        if (!Member::where('account', $account)->exists()) {
            return 'error account';
        }

        $member=Member::where('account', $account)->first();
        if (!Hash::check($password, $member->password)) {
            return 'error password';
        }
        $token=Str::random(60);
        $member->api_token=$token;
        $member->save();
        return $member;
    }

    public function update (Member $member, Memberupdate $request)
    {
        $token=request()->bearerToken();
        $password=request()->get('yourpassword');
        if ($token!==$member->api_token) {
            return 'token error';
        }
        if (Hash::check($password, $member->password)) {
            $data=$request->validated();
            if (isset($data['password'])) {
                $data['password']=Hash::make($data['password']);
            }
            $member->update($data);
            return $member;
        }
        return 'error password';
    }

}
