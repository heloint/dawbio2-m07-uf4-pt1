@extends('layout')

@section('content')

<div class="container my-5">
    <h3>Manage Teams</h3>
    <div class="row">
    <form class="my-3">
        <button class="btn btn-primary">Add player</button>
    </form>
    @if(empty($players))
    <p>There are no items!</p>
    @else
    <table id="dtBasicExample" class="table table-hover paginated-table">
      <thead>
        <tr class="table-primary">
          <th class="text-center" scope="col">First name</th>
          <th class="text-center" scope="col">Last name</th>
          <th class="text-center" scope="col">Date of birth</th>
          <th class="text-center" scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
        @foreach($players as $player)
        <tr class="table-light">
          <td class="text-center" >{{ $player->first_name}}</td>
          <td class="text-center" >{{ $player->last_name}}</td>
          <td class="text-center" >{{ $player->birth_date}}</td>
          <td >
          <form class="d-flex justify-content-center gap-3">
            <input type="hidden" name="team_id" value="{{ $player->id }}" >
            <button type="submit" name="edit/player" value="{{ $player->id }}" class="btn btn-primary">Edit</button>
            <button type="submit" name="delete/player" value="{{ $player->id }}" class="btn btn-danger">Delete</button>
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
