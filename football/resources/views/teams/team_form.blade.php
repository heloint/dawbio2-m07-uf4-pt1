@extends('layout')

@section('content')

    <div class="container my-5">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">

                @if ($mode === 'add')
                    <h3 class="mb-5">Add new team</h3>
                @elseif($mode === 'edit')
                    <h3 class="mb-5">Edit team</h3>
                @endif

                @if (!empty($error))
                    <p class="text-danger text-lg">{{ $error }}</p>
                @endif
                @if (!empty($result) && empty($errors->messages()))
                    @if ($result)
                        @if ($mode === 'add')
                            <p class="text-success text-lg">Succesfully added new team!</p>
                        @elseif($mode === 'edit')
                            <p class="text-success text-lg">Succesfully modified team!</p>
                        @endif
                    @else
                        <p class="text-danger text-lg">Internal error has occured, please contact with one of the admins..
                        </p>
                    @endif
                @endif
                @if ($mode === 'add')
                    <form action="/team/add" method="post">
                    @elseif($mode === 'edit')
                        <form action="/team/edit" method="post">
                @endif
                @csrf
                <div class="mb-3">
                    <label for="id" class="form-label">ID</label>
                    <input type="text" id="id" name="team_id" class="form-control" value="{{ $team->id }}"
                        readonly>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ old('name', $team->name) }}" required>
                    @if ($errors->has('name'))
                        <p class="text-danger">{{ $errors->first('name') }}</p>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="coach" class="form-label">Coach</label>
                    <input type="text" id="coach" name="coach" class="form-control"
                        value="{{ old('coach', $team->coach) }}" required>
                    @if ($errors->has('coach'))
                        <p class="text-danger">{{ $errors->first('coach') }}</p>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" id="category" name="category" class="form-control"
                        value="{{ old('category', $team->category) }}" required>
                    @if ($errors->has('category'))
                        <p class="text-danger">{{ $errors->first('category') }}</p>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="budget" class="form-label">Budget</label>
                    <input type="number" id="budget" name="budget" class="form-control"
                        value="{{ old('budget', $team->budget) }}"required>
                    @if ($errors->has('budget'))
                        <p class="text-danger">{{ $errors->first('budget') }}</p>
                    @endif
                </div>

                @if ($mode === 'add')
                    <button type="submit" class="btn btn-primary">Add Team</button>
                @elseif($mode === 'edit')
                    <button type="submit" class="btn btn-primary">Edit Team</button>
                @endif
                <button class="btn btn-secondary"><a class="no-style text-white" href="/manage-teams">Cancel</a></button>
                </form>
            </div>
        </div>
    </div>


    @if ($mode === 'edit')
        <div class="container my-5">
            <h3>Contracted players</h3>
            <div class="row">
                <div class="d-flex gap-3">
                    <form action="/team/subscribe-player-table" method="post" class="my-3">
                        @csrf
                        <button name="team_id" value="{{ $team->id }}" class="btn btn-primary">
                            <a class="no-style text-white">
                                Subscribe players
                            </a>
                        </button>
                    </form>
                    <form action="/team/unsubscribe-confirmation" method="post" class="my-3">
                        @csrf
                        <input type="hidden" name="apply_all" value="true">
                        <button name="team_id" value="{{ $team->id }}" class="btn btn-danger">
                            <a class="no-style text-white">
                                Unsubscribe all
                            </a>
                        </button>
                    </form>
                </div>
                @if (empty($players))
                    <p class="text-danger">There are no players to display!</p>
                @else
                    @if (!empty($unsubscriptionResult))
                        @if ($unsubscriptionResult === true)
                            <h6 class="text-success my-5">Successfully unsubscribed player
                                "{{ $unsubscribedPlayer->first_name . ' ' . $unsubscribedPlayer->last_name }}"!</h6>
                        @else
                            <h6 class="text-danger my-5">{{ $error }}</h6>
                        @endif
                    @endif

                    @if (!empty($unsubAllResult))
                        @if ($unsubAllResult === true)
                            <h6 class="text-success my-5">Successfully unsubscribed all player!</h6>
                        @else
                            <h6 class="text-danger my-5">{{ $error }}</h6>
                        @endif
                    @endif

                    <div class="input-group mb-3 w-50 d-flex gap-1">
                        <input id="search-input" type="text" class="form-control" placeholder="Search...">
                        <button id="search-button" class="btn btn-primary" type="button">Search</button>
                    </div>

                    <div class="pagination-container">
                        <ul class="pagination justify-content-start" id="paginationLinks">
                            <li class="page-item" id="previousPage">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <table class="table table-hover filterable-table paginated-table">
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
                                    <form action="/team/unsubscribe-confirmation" method="post">
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
