<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\User;
use Cache;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class LastUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            updateTimeSpent();

            // // Add Last Seen Date and time
            $expiresAt = Carbon::now()->addMinutes(2); // keep online for 1 min
            Cache::put('user-is-online' . Auth::user()->id, true, $expiresAt);
            // User::where('id', Auth::user()->id)->update(['last_seen' => (new \DateTime())->format("Y-m-d H:i:s")]);
            // // if (Cache::has('user-is-online' . Auth::user()->id)) {
               
            // // }   

        }
        
       
        return $next($request);
    }
}
