@extends('layout')

{{-- 
  This Blade file contains the form for adding and updating a football player.
  @author Dániel Májer
--}}

@section('content')

    <div class="container my-5">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                @if ($mode === 'add')
                    <h3 class="mb-5">Add new player</h3>
                @elseif($mode === 'edit')
                    <h3 class="mb-5">Edit player</h3>
                @endif
                @if (!empty($error))
                    <p class="text-danger text-lg">{{ $error }}</p>
                @endif
                @if (!empty($result) && empty($errors->messages()))
                    @if ($result)
                        @if ($mode === 'add')
                            <p class="text-success text-lg">Succesfully added new player!</p>
                        @elseif($mode === 'edit')
                            <p class="text-success text-lg">Succesfully modified player!</p>
                        @endif
                    @else
                        <p class="text-danger text-lg">Couldn't get your request done. Contact with one of our admin.</p>
                    @endif
                @endif
                @if ($mode === 'add')
                        <form action="/player/add" method="post">
                    @elseif($mode === 'edit')
                        <form action="/player/edit" method="post">
                @endif
                @csrf
                <div class="mb-3">
                    <label for="id" class="form-label">ID</label>
                    <input type="text" id="id" name="player_id" class="form-control" value="{{ $player->id }}"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="firstName" class="form-label">First Name</label>
                    <input type="text" name="first_name" id="first_name"
                        value="{{ old('first_name', $player->first_name) }}" class="form-control" required>
                    @if ($errors->has('first_name'))
                        <p class="text-danger">{{ $errors->first('first_name') }}</p>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $player->last_name) }}"
                        class="form-control" required>
                    @if ($errors->has('last_name'))
                        <p class="text-danger">{{ $errors->first('last_name') }}</p>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="dob" class="form-label">Year of birth</label>
                    <input type="number" id="birth_year" name="birth_year"
                        value="{{ old('birth_year', $player->birth_year) }}" class="form-control" required>
                    @if ($errors->has('birth_year'))
                        <p class="text-danger">{{ $errors->first('birth_year') }}</p>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="salary" class="form-label">Salary</label>
                    <input type="number" id="salary" name="salary" step="0.001"
                        value="{{ old('salary', $player->salary) }}" class="form-control" required>
                    @if ($errors->has('salary'))
                        <p class="text-danger">{{ $errors->first('salary') }}</p>
                    @endif
                </div>
                @if ($mode === 'add')
                    <button type="submit" class="btn btn-primary">Add Player</button>
                @elseif($mode === 'edit')
                    <button type="submit" class="btn btn-primary">Edit Player</button>
                @endif
                <button class="btn btn-secondary"><a class="no-style text-white" href="/players/manage">Cancel</a></button>
                </form>
            </div>
        </div>
    </div>

@endsection
