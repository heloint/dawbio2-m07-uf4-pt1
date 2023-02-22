@extends('layout')

@section('content')

<div class="container my-5">
  <div class="row justify-content-center mt-5">
    <div class="col-md-6">
      <h3 class="mb-5">Add new team</h3>
      <form>
        <div class="mb-3">
          <label for="id" class="form-label">ID</label>
          <input type="text" id="id" class="form-control" value="{{ $nextFreeID }}" disabled>
        </div>
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="coach" class="form-label">Coach</label>
          <input type="text" id="coach" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="category" class="form-label">Category</label>
          <input type="text" id="category" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="budget" class="form-label">Budget</label>
          <input type="number" id="budget" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Team</button>
        <button class="btn btn-secondary"><a class="no-style text-white" href="/manageteams">Cancel</a></button>
      </form>
    </div>
  </div>
</div>

@endsection
