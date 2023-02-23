@extends('layout')

@section('content')

<div class="container my-5">
  <div class="row justify-content-center mt-5">
    <div class="col-md-6">
      <h3 class="mb-5">Add new team</h3>
      @if (!empty($error))
            <p class="text-danger text-lg">{{ $error }}</p> 
      @endif

      @if($mode === 'add')
        <form action="/addteam" method="post">
      @elseif($mode === 'edit')
        <form action="/modifyTeam" method="post">
      @endif
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

          @if($mode === 'add')
            <button type="submit" class="btn btn-primary">Add Team</button>
          @elseif($mode === 'edit')
            <button type="submit" class="btn btn-primary">Edit Team</button>
          @endif
        <button class="btn btn-secondary"><a class="no-style text-white" href="/manageteams">Cancel</a></button>
      </form>
    </div>
  </div>
</div>


@if($mode === 'edit')
<div class="container my-5">
        <h3>Manage Players</h3>
        <div class="row">
            <div class="my-3">
                <button class="btn btn-primary"><a class="no-style text-white" href="/newplayer">Add player</a></button>
            </div>
            @if (empty($players))
                <p class="text-danger" >There are no players to display!</p>
            @else
                <div class="pagination-container">
                    <ul class="pagination justify-content-center" id="paginationLinks">
                        <li class="page-item" id="previousPage">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <table id="paginated-table" class="table table-hover">
                    <thead>
                        <tr class="table-primary">
                            <th class="text-center" scope="col">First name</th>
                            <th class="text-center" scope="col">Last name</th>
                            <th class="text-center" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($players as $player)
                        <tr class="table-light">
                            <td class="text-center">{{ $player->first_name }}</td>
                            <td class="text-center">{{ $player->last_name }}</td>
                            <td class="d-flex justify-content-center gap-3">
                                <form action="/unsubscribe-confirmation" method="post">
                                    @csrf
                                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                                    <button type="submit" name="player_id" value="{{ $player->id }}"
                                        class="btn btn-danger">Unsubscribe</button>
                                </form>
                            </td>
                        </tr>
            @endforeach
            </tbody>
            </table>
            @endif
        </div>
    </div>





@endif




@endsection
