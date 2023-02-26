@extends('layout')

{{-- 
  This Blade file contains the confirmation message for subscribing a football player.
  @author Dániel Májer
--}}

@section('content')
    <div class="row" style="height: 35rem;">
        <div class="d-flex justify-content-center align-items-center mt-5">
            <h2 class="mb-0 text-center">Player "{{ "{$player->first_name} {$player->last_name}" }}" already subscribed to
                team "{{ $player->team->name }}!"
                <br>
                Are you sure you want to transfer this player to team "{{ $team->name }}"?
            </h2>
        </div>
        <div class="d-flex justify-content-center gap-5">
            <form action="/team/subscribe-player" method="post">
                @csrf
                <input type="hidden" name="confirmed" value="true">
                <input type="hidden" name="team_id" value="{{ $team->id }}">
                <button name="player_id" value="{{ $player->id }}" class="btn btn-lg btn-success">Yes</button>
            </form>
            <div>
                <button class="btn btn-lg btn-danger"><a class="no-style text-white"
                        href="{{ route('team.subscribePlayerTable', ['team_id' => $team->id]) }}">No</a></button>
            </div>
        </div>
    </div>
@endsection
