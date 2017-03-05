<?php
/**
 * Created by PhpStorm.
 * User: Nakroma
 * Date: 05.03.2017
 * Time: 20:16
 */

namespace laravelTest\Providers;

use laravelTest\Callname;
use Illuminate\Support\Facades\Auth;

class CallnameService
{
    /**
     * Returns a random callname to use.
     *
     * @param $thread_id
     * @return void
     */
    public static function assignCallname($thread_id)
    {
        $cln = uniqid();

        $callnames = config('_custom.callnames'); // Available Callnames
        $used_callnames = Callname::where('thread', $thread_id)->get();

        // Iterate through used callnames and remove them from available ones
        foreach ($used_callnames as $cn) {
            if ($cn->callname >= 0)
                unset($callnames[$cn->callname]);
        }

        // Assign ID if no callnames left, otherwise assign callname
        if (count($callnames) > 0)
            $cln = array_rand($callnames);

        // Create callname
        $callname = new Callname;
        $callname->thread = $thread_id;
        $callname->author = Auth::id();
        if (is_string($cln)) {
            $callname->callname = -1;
            $callname->custom_id = $cln;
        } else {
            $callname->callname = $cln;
        }
        $callname->save();
    }
}