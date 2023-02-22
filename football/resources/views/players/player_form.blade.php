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
      <form action="/addplayer">
        <div class="mb-3">
          <label for="id" class="form-label">ID</label>
          <input type="text" id="id" class="form-control" value="{{ $player->id }}" disabled>
        </div>
        <div class="mb-3">
          <label for="firstName" class="form-label">First Name</label>
          <input type="text" name="first_name" id="first_name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="lastName" class="form-label">Last Name</label>
          <input type="text" id="last_name" name="last_name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="dob" class="form-label">Date of Birth</label>
          <input type="date" id="birth_date" name="birth_date" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="salary" class="form-label">Salary</label>
          <input type="number" id="salary" name="salary" step="0.01" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Player</button>
        <button class="btn btn-secondary"><a class="no-style text-white" href="/manageplayers">Cancel</a></button>
      </form>
    </div>
  </div>
</div>

@endsection
