@extends('layout')

@section('content')

    <div class="row" style="height: 35rem;">
        <div class="d-flex justify-content-center align-items-center">
            <h3 class="">Are you sure you want to delete player "{{ $player->first_name . ' ' . $player->last_name }}"?</h3>
        </div>
        <div class="d-flex justify-content-center gap-5">
            <form action="/delete-player" method="post">
                @csrf
                <input type="hidden" name="player_id" value="{{ $player->id }}">
                <button name="player_id" value="{{ $player->id }}" class="btn btn-lg btn-success">Yes</button>
            </form>
            <div>
                <button class="btn btn-lg btn-danger"><a class="no-style text-white" href="{{ url()->previous() }}">No</a></button>
            </div>
        </div>
    </div>

@endsection
