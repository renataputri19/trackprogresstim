@extends('layouts.app')

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
@endsection
