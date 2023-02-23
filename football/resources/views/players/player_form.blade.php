@extends('layout')

@section('content')

<div class="container my-5">
  <div class="row justify-content-center mt-5">
    <div class="col-md-6">
      <h3 class="mb-5">Add new player</h3>
      @if (!empty($result))
          @if ($result === true)
            <p class="text-success text-lg">Succesfully added!</p> 
          @else
            <p class="text-danger text-lg">Internal error has occured, please retry later.</p> 
          @endif
      @endif
      <form action="/addplayer" method="post">
        @csrf
        <div class="mb-3">
          <label for="id" class="form-label">ID</label>
          <input type="text" id="id" class="form-control" value="{{ $player->id }}" disabled>
        </div>
        <div class="mb-3">
          <label for="firstName" class="form-label">First Name</label>
          <input type="text" name="first_name" id="first_name" class="form-control" required>
          @if($errors->has('first_name'))
              <p class="text-danger">{{ $errors->first('first_name') }}</p>
          @endif
        </div>
        <div class="mb-3">
          <label for="lastName" class="form-label">Last Name</label>
          <input type="text" id="last_name" name="last_name" class="form-control" required>
          @if($errors->has('last_name'))
              <p class="text-danger">{{ $errors->first('last_name') }}</p>
          @endif
        </div>
        <div class="mb-3">
          <label for="dob" class="form-label">Date of Birth</label>
          <input type="date" id="birth_date" name="birth_date" class="form-control" required>
          @if($errors->has('birth_date'))
              <p class="text-danger">{{ $errors->first('birth_date') }}</p>
          @endif
        </div>
        <div class="mb-3">
          <label for="salary" class="form-label">Salary</label>
          <input type="number" id="salary" name="salary" step="0.01" class="form-control" required>
          @if($errors->has('salary'))
              <p class="text-danger">{{ $errors->first('salary') }}</p>
          @endif
        </div>
        <button type="submit" class="btn btn-primary">Add Player</button>
        <button class="btn btn-secondary"><a class="no-style text-white" href="/manageplayers">Cancel</a></button>
      </form>
    </div>
  </div>
</div>

@endsection
