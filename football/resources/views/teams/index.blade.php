@extends('layout')

@section('content')

    <div class="container my-5">
        <h3>Manage Teams</h3>
        <div class="row">
            <div class="my-3">
                <button class="btn btn-primary"><a class="no-style text-white" href="/newteam">Add team</a></button>
            </div>
            @if (empty($teams))
                <h6 class="text-danger my-5">There are no items!</h6>
            @else
            @if (!empty($error))
                <h6 class="text-danger my-5">{{ $error }}</h6>
            @endif
            @if (!empty($deletionResult))
                @if ($deletionResult === true)
                    <h6 class="text-success"> Successfully deleted team "{{ $teamToDelete->name }}" !</h6>
                @endif
            @endif
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
                            <th class="text-center" scope="col">Name</th>
                            <th class="text-center" scope="col">Coach</th>
                            <th class="text-center" scope="col">Category</th>
                            <th class="text-center" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($teams as $team)
                        <tr class="table-light">
                            <td class="text-center">{{ $team->name }}</td>
                            <td class="text-center">{{ $team->coach }}</td>
                            <td class="text-center">{{ $team->category }}</td>
                            <td class="d-flex justify-content-center gap-3">
                                <form action="/editteamform" method="get">
                                    <button type="submit" name="team_id" value="{{ $team->id }}"
                                        class="btn btn-primary">Edit</button>
                                </form>
                                <form action="/confirm-team-deletion" method="post">
                                    @csrf
                                    <button type="submit" name="team_id" value="{{ $team->id }}"
                                        class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
            @endforeach
            </tbody>
            </table>

        </div>
    </div>
    @endif
    </div>
    </div>

@endsection
