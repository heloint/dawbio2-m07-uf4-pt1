@extends('layout')

@section('content')
    <div class="container my-5">
        <div class="row">

            <!-- Jumbotron -->
            <div class="jumbotron jumbotron-fluid">
                <div class="container">
                    <h1 class="display-4">Welcome to Football Manager</h1>
                    <p class="lead">Manage your football teams and players with ease.</p>
                </div>
            </div>

            <!-- Features Section -->
            <section class="features">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Manage Teams</h5>
                                    <p class="card-text">
                                        Create and manage your football teams with ease. Keep track of their players,
                                        budget and more.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Manage Players</h5>
                                    <p class="card-text">
                                        Add and manage your football players.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Get Insights</h5>
                                    <p class="card-text">
                                        Analyze and manage you database. It's all yours.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="d-flex justify-content-center">
                <img style="max-width: 25rem;" src="{{ asset('images/football-drawing.webp') }}">
            </div>

        </div>
    </div>
@endsection
