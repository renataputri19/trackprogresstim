@extends('layouts.main')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <h2 class="my-4">Admin Dashboard</h2>

    <div class="row">
        <!-- Example of a Card for Overall Progress -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Overall Progress</div>
                <div class="card-body">
                    <h5 class="card-title">75%</h5>
                    <p class="card-text">This is the overall progress of all tasks.</p>
                </div>
            </div>
        </div>

        <!-- Card for Teams Performance -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Top Performing Team</div>
                <div class="card-body">
                    <h5 class="card-title">Team A</h5>
                    <p class="card-text">Achieved 90% of targets.</p>
                </div>
            </div>
        </div>

        <!-- Card for Timely Task Completion -->
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Timely Task Completion</div>
                <div class="card-body">
                    <h5 class="card-title">85%</h5>
                    <p class="card-text">Tasks completed on time.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Example (Using Chart.js or any other chart library) -->
    <div class="row">
        <div class="col-md-12">
            <canvas id="tasksChart"></canvas>
        </div>
    </div>


    <div class="container">
        <h1>Gantt Chart Dashboard</h1>
    
        <!-- TIM Filter -->
        <div class="form-group">
            <label for="tim-filter">Filter by TIM</label>
            <select class="form-control" id="tim-filter">
                <option value="">All TIM</option>
                <option value="SUBBAGIAN UMUM">SUBBAGIAN UMUM</option>
                <option value="TIM SOSIAL">TIM SOSIAL</option>
                <option value="TIM PRODUKSI">TIM PRODUKSI</option>
                <option value="TIM DISTRIBUSI">TIM DISTRIBUSI</option>
                <option value="TIM NERWILIS">TIM NERWILIS</option>
                <option value="TIM PENGOLAHAN DAN IT">TIM PENGOLAHAN DAN IT</option>
            </select>
        </div>
    
        <!-- Gantt chart container -->
        <div id="gantt_here" style="width:100%; height:500px;"></div>
    </div>
    

    <!-- Add more sections or widgets as needed -->

    <div class="container">
        <h1>Dashboard</h1>
    
        <!-- TIM Filter -->
        <div class="form-group">
            <label for="tim-filter">Filter by TIM</label>
            <select class="form-control" id="tim-filter">
                <option value="">All TIM</option>
                <option value="SUBBAGIAN UMUM">SUBBAGIAN UMUM</option>
                <option value="TIM SOSIAL">TIM SOSIAL</option>
                <option value="TIM PRODUKSI">TIM PRODUKSI</option>
                <option value="TIM DISTRIBUSI">TIM DISTRIBUSI</option>
                <option value="TIM NERWILIS">TIM NERWILIS</option>
                <option value="TIM PENGOLAHAN DAN IT">TIM PENGOLAHAN DAN IT</option>
            </select>
        </div>
    
        <div id="calendar"></div>
    </div>

</div>

<!-- Include your charting library (e.g., Chart.js) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('tasksChart').getContext('2d');
    var tasksChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Team A', 'Team B', 'Team C'],
            datasets: [{
                label: 'Task Completion',
                data: [90, 70, 85],
                backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>


@endsection


















{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard</h1>

    <!-- TIM Filter -->
    <div class="form-group">
        <label for="tim-filter">Filter by TIM</label>
        <select class="form-control" id="tim-filter">
            <option value="">All TIM</option>
            <option value="SUBBAGIAN UMUM">SUBBAGIAN UMUM</option>
            <option value="TIM SOSIAL">TIM SOSIAL</option>
            <option value="TIM PRODUKSI">TIM PRODUKSI</option>
            <option value="TIM DISTRIBUSI">TIM DISTRIBUSI</option>
            <option value="TIM NERWILIS">TIM NERWILIS</option>
            <option value="TIM PENGOLAHAN DAN IT">TIM PENGOLAHAN DAN IT</option>
        </select>
    </div>

    <div id="calendar"></div>
</div>
@endsection --}}
