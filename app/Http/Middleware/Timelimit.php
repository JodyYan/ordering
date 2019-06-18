<?php

namespace App\Http\Middleware;

use Closure;
use App\Deadline;
use App\Member;
use App\Group;
use Carbon\Carbon;

class Timelimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token=$request->bearerToken();
        $member=Member::where('api_token', $token)->first();
        $today=Carbon::today();
        $nowTime=Carbon::now();
        $timeCheck=Deadline::where('group_id', $member->group_id)->where('which_date', $today)->first();
        if ($member->group->time_limit == 1) {
            if ($timeCheck != null) {
                $timeCarbon=Carbon::parse($timeCheck->deadline_time);
                if ($nowTime->gt($timeCarbon)) {
                    return response(['error' => 'over order time'], 422);
                }
            }

            if ($timeCheck == null && Carbon::parse($member->group->preset_time)->lt($nowTime)) {
                return response(['error' => 'over order time'], 422);
            }
        }

        return $next($request);
    }
}
