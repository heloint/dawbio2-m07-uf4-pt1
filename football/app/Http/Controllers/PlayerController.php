<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        $players= Player::all();
        return view('players.index', compact('players'));
    }

    public function newPlayer()
    {
        $nextFreeID = Player::max('id') + 1;
        $player = new Player();
        $player->id = $nextFreeID;

        return view('players.player_form', compact('player'));
    }

    public function addNewPlayer(Request $request)
    {
        // Reformat received date so it matches the database's format.
        $request['birth_date'] = (int) \implode(\array_reverse(\explode('-', $request['birth_date'])));

        $player = new Player();

        foreach($request->all() as $key => $value)
        {
            $player->$key = $value;
        }

        $result = $player->save();

        return view('players.player_form', compact('result', 'player'));
    }
}
