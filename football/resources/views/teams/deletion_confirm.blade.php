@extends('layout')

@section('content')
    <div class="row" style="height: 35rem;">
        <div class="d-flex justify-content-center align-items-center">
            <h3 class="">Are you sure you want to delete team "{{ $team->name }}"?</h3>
        </div>
        <div class="d-flex justify-content-center gap-5">
            <form action="/team/delete" method="post">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->id }}">
                <button name="team_id" value="{{ $team->id }}" class="btn btn-lg btn-success">Yes</button>
            </form>
            <div>
                <button class="btn btn-lg btn-danger"><a class="no-style text-white"
                        href="{{ route('team.manage') }}">No</a></button>
            </div>
        </div>
    </div>
@endsection
