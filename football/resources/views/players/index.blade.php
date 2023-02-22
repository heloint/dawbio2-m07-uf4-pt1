@extends('layout')

@section('content')

    <div class="container my-5">
        <h3>Manage Players</h3>
        <div class="row">
            <div class="my-3">
                <button class="btn btn-primary"><a class="no-style text-white" href="/newplayer">Add player</a></button>
            </div >
            @if (empty($players))
                <p>There are no items!</p>
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
                            <th class="text-center" scope="col">Date of birth</th>
                            <th class="text-center" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($players as $player)
                        <tr class="table-light">
                            <td class="text-center">{{ $player->first_name }}</td>
                            <td class="text-center">{{ $player->last_name }}</td>
                            <td class="text-center">{{ $player->birth_date }}</td>
                            <td>
                                <form class="d-flex justify-content-center gap-3">
                                    <input type="hidden" name="team_id" value="{{ $player->id }}">
                                    <button type="submit" name="edit/player" value="{{ $player->id }}"
                                        class="btn btn-primary">Edit</button>
                                    <button type="submit" name="delete/player" value="{{ $player->id }}"
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
