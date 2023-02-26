@extends('layout')

{{-- 
  This Blade file contains the confirmation message for unsubscribing a football player.
  @author Dániel Májer.
--}}

@section('content')
    <div class="row" style="height: 35rem;">
        <div class="d-flex justify-content-center align-items-center">
            @if (empty($applyAll))
                <h3 class="">Are you sure you want to unsubscribe player
                    "{{ $player->first_name . ' ' . $player->last_name }}" from team "{{ $team->name }}"?</h3>
            @else
                <h3 class="">Are you sure you want to unsubscribe all players from team "{{ $team->name }}"?</h3>
            @endif
        </div>
        <div class="d-flex justify-content-center gap-5">
            @if (empty($applyAll))
                <form action="/team/unsubscribe-player" method="post">
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                    <button type="submit" name="player_id" value="{{ $player->id }}"
                        class="btn btn-lg btn-success">Yes</button>
                @else
                    <form action="/team/unsubscribe-all" method="post">
                        @csrf
                        <button type="submit" name="team_id" value="{{ $team->id }}"
                            class="btn btn-lg btn-success">Yes</button>
            @endif
            </form>
            <div>
                <button class="btn btn-lg btn-danger"><a class="no-style text-white"
                        href="{{ url()->previous() }}">No</a></button>
            </div>
        </div>
    </div>
@endsection
