@extends('layout')

@section('content')

    <div class="container my-5">
        <h3>Manage Teams</h3>
        <div class="row">
            <div class="my-3">
                <button class="btn btn-primary">Add team</button>
            </div>
            @if (empty($teams))
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
                            <td>
                                <form class="d-flex justify-content-center gap-3">
                                    <input type="hidden" name="team_id" value="{{ $team->id }}">
                                    <button type="submit" name="edit/team" value="{{ $team->id }}"
                                        class="btn btn-primary">Edit</button>
                                    <button type="submit" name="delete/team" value="{{ $team->id }}"
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
