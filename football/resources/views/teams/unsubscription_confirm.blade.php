@extends('layout')

@section('content')

    <div class="row" style="height: 35rem;">
        <div class="d-flex justify-content-center align-items-center">
            <h3 class="">Are you sure you want to unsubscribe player "{{ $player->first_name . ' '. $player->last_name }}"?</h3>
        </div>
        <div class="d-flex justify-content-center gap-5">
            <form>
                <button class="btn btn-lg btn-success">Yes</button>
            </form>
            <form>
                <button class="btn btn-lg btn-danger">No</button>
            </form>
        </div>
    </div>

@endsection
