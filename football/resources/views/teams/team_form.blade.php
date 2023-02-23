@extends('layout')

@section('content')

<div class="container my-5">
  <div class="row justify-content-center mt-5">
    <div class="col-md-6">
      <h3 class="mb-5">Add new team</h3>
      @if (!empty($error))
            <p class="text-danger text-lg">{{ $error }}</p> 
      @endif
      <form action="/addteam" method="post">
        @csrf
        <div class="mb-3">
          <label for="id" class="form-label">ID</label>
          <input type="text" id="id" class="form-control" value="{{ $team->id }}" disabled>
        </div>
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" id="name" name="name" class="form-control"  value="{{ $team->name }}" required>
          @if($errors->has('name'))
              <p class="text-danger">{{ $errors->first('name') }}</p>
          @endif
        </div>
        <div class="mb-3">
          <label for="coach" class="form-label">Coach</label>
          <input type="text" id="coach"  name="coach" class="form-control"  value="{{ $team->coach }}" required>
          @if($errors->has('coach'))
              <p class="text-danger">{{ $errors->first('coach') }}</p>
          @endif
        </div>
        <div class="mb-3">
          <label for="category" class="form-label">Category</label>
          <input type="text" id="category" name="category" class="form-control"  value="{{ $team->category }}" required>
          @if($errors->has('category'))
              <p class="text-danger">{{ $errors->first('category') }}</p>
          @endif
        </div>
        <div class="mb-3">
          <label for="budget" class="form-label">Budget</label>
          <input type="number" id="budget" name="budget" class="form-control" value="{{ $team->budget}}"required>
          @if($errors->has('budget'))
              <p class="text-danger">{{ $errors->first('budget') }}</p>
          @endif
        </div>
        <button type="submit" class="btn btn-primary">Add Team</button>
        <button class="btn btn-secondary"><a class="no-style text-white" href="/manageteams">Cancel</a></button>
      </form>
    </div>
  </div>
</div>

@endsection
