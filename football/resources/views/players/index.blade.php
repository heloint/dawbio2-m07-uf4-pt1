@extends('layout')

@section('content')

    <div class="container my-5">
        <h3>Manage Players</h3>
        <div class="row">
            <div class="my-3">
                <button class="btn btn-primary"><a class="no-style text-white" href="/player/add">Add player</a></button>
            </div>
            @if (empty($players))
                <h6 class="text-danger my-5">There are no players to display!</h6>
            @else
                @if (!empty($error))
                    <h6 class="text-danger my-5">{{ $error }}</h6>
                @endif
                @if (!empty($deletionResult))
                    @if ($deletionResult === true)
                        <h6 class="text-success"> Successfully deleted player !</h6>
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
                            <th class="text-center" scope="col">Year of birth</th>
                            <th class="text-center" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($players as $player)
                        <tr class="table-light">
                            <td class="text-center">{{ $player->first_name }}</td>
                            <td class="text-center">{{ $player->last_name }}</td>
                            <td class="text-center">{{ $player->birth_year }}</td>
                            <td class="d-flex justify-content-center gap-3">
                                <form action="/player/edit">
                                    @csrf
                                    <button type="submit" name="player_id" value="{{ $player->id }}"
                                        class="btn btn-primary">Edit</button>
                                </form>
                                <form action="/player/confirm-deletion" method="post">
                                    @csrf
                                    <button type="submit" name="player_id" value="{{ $player->id }}"
                                        class="btn btn-danger">Delete</button>
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
