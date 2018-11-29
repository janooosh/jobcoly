<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class manager
{
    /**
     * Handle an incoming request.
     * Gets a shift and checks if the current logged-in user is the manager of this shift
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * 
     */
    public function handle($request, Closure $next, $shift_id)
    {
        //Get current user
        $user = Auth::user();
        //Get manager shifts of user
        $managed_shifts = $user->manager_shifts;
        //Loop through each of them and check if one of them is the given $shift_id
        foreach($managed_shifts as $managed_shift) {
            if($managed_shift->id == $shift_id) {
                //Continue with request, user is manager of given shift
                return $next($request);
            }
        }
        //User is not manager of given shift, reject request
        echo("Keine Berechtigung");
        return null;
    }
}
