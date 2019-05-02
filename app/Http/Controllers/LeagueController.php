<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    public function index()
    {
        $teams = Team::get();

        $teams = $teams->sortByDesc('PTS');

        return response($teams->values());
    }
}
