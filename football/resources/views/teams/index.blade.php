@extends('layout')

@section('content')

<div class="container">
    <div class="row">
    @if(empty($teams))
    <p>There are no items!</p>
    @else
    <table class="table table-hover">
      <thead>
        <tr class="table-primary">
          <th class="text-center" scope="col">Name</th>
          <th class="text-center" scope="col">Coach</th>
          <th class="text-center" scope="col">Category</th>
          <th class="text-center" scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
        @foreach($teams as $team)
        <tr class="table-light">
          <td class="text-center" >{{ $team->name }}</td>
          <td class="text-center" >{{ $team->coach }}</td>
          <td class="text-center" >{{ $team->category }}</td>
          <td >
          <form class="d-flex justify-content-center gap-3">
            <input type="hidden" name="team_id" value="{{ $team->id }}" >
            <button class="btn btn-primary">Edit</button>
            <button class="btn btn-danger">Delete</button>
          </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @endif
    </div>
</div>
@endsection
