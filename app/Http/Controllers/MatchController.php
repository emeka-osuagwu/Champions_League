<?php

namespace App\Http\Controllers;

use App\Jobs\PlayWeek;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function play()
    {
        $matches = PlayWeek::dispatchNow();

        return response($matches);
    }
}
