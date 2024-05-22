{{-- @extends('layouts.app') --}}

{{-- @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}

<!-- resources/views/home.blade.php -->

@extends('layouts.main')

@section('title', 'Business Tracker - Home')

@section('content')
    <div class="bg-white shadow p-5 rounded-lg d-flex align-items-center mb-5">
        <div class="w-50">
            <h1 class="display-4 font-weight-bold">Always Track & Analyze Your Business Statistics To Succeed.</h1>
            <p class="lead">A better way to manage your sales, team, clients & marketing — on a single platform. Powerful, affordable & easy.</p>
            <form class="form-inline my-4">
                <input type="email" class="form-control mr-2" placeholder="Enter your email">
                <button type="submit" class="btn btn-dark">Get started</button>
            </form>
            <div class="d-flex align-items-center">
                <img src="path/to/visa.png" alt="Visa" class="mr-2">
                <img src="path/to/mastercard.png" alt="MasterCard" class="mr-2">
                <img src="path/to/paypal.png" alt="PayPal">
            </div>
        </div>
        <div class="w-50">
            <img src="path/to/illustration.png" alt="Illustration" class="img-fluid">
        </div>
    </div>

    <div class="bg-white shadow p-5 rounded-lg d-flex align-items-center">
        <div class="w-50">
            <img src="path/to/second-illustration.png" alt="Illustration" class="img-fluid">
        </div>
        <div class="w-50">
            <h2 class="h4 font-weight-bold">Faster, friendlier feedback loops make life easier.</h2>
            <p class="lead">Add a Viewer to your team so they can see everything you share, or invite people to individual documents. It's up to you. Stakeholders can check out designs in their web browser, test prototypes and leave feedback for free.</p>
            <ul class="list-unstyled">
                <li class="mb-2">✔️ Shared Cloud Libraries, for a single source of truth</li>
                <li class="mb-2">✔️ Prototype previews for user testing and research</li>
                <li class="mb-2">✔️ Easy organization with projects</li>
                <li class="mb-2">✔️ Free developer handoff, right inside the browser</li>
                <li class="mb-2">✔️ Two-factor authentication and SSO</li>
            </ul>
        </div>
    </div>

    
    






@endsection




