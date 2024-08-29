@extends('layouts.main')

@section('title', 'User Dashboard')

@section('content')
<div class="container">
    <h2 class="my-4">My Dashboard</h2>

    <div class="row">
        <!-- Example of a Card for Personal Progress -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">My Overall Progress</div>
                <div class="card-body">
                    <h5 class="card-title">60%</h5>
                    <p class="card-text">This is your overall progress in all assigned tasks.</p>
                </div>
            </div>
        </div>

        <!-- Card for Task Completion -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Tasks Completed</div>
                <div class="card-body">
                    <h5 class="card-title">8 out of 10</h5>
                    <p class="card-text">Tasks you have completed.</p>
                </div>
            </div>
        </div>

        <!-- Card for Upcoming Deadlines -->
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Upcoming Deadlines</div>
                <div class="card-body">
                    <h5 class="card-title">3</h5>
                    <p class="card-text">Tasks with approaching deadlines.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Example of a Progress Bar -->
    <div class="row">
        <div class="col-md-12">
            <h5>Your Progress</h5>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">60%</div>
            </div>
        </div>
    </div>

    <!-- More Widgets or Charts as Needed -->

</div>
@endsection
