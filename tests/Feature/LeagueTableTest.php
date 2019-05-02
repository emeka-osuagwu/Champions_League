<?php

namespace Tests\Feature;

use App\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeagueTableTest extends TestCase
{
    use RefreshDatabase;

    /** #@test */
    public function it_returns_teams_list_with_basic_store_table_if_not_matches_played()
    {
        $team = factory(Team::class)->create();
        $response = $this->getJson(route('api.league.index'));
        $response->assertStatus(200);
        $response->assertJson([
            0 => [
                'id' => $team->id,
                'name' => $team->name,
                'PTS' => 0,
                'P' => 0,
                'W' => 0,
                'D' => 0,
                'L' => 0,
                'GD' => 0,
            ]
        ]);
    }
}
