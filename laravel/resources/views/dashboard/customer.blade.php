@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')
    @include('includes.return')
    
    <div class="dashboard">
        <div id="greeting">Hello, {{ $user['customer_name'] }}!</div>

        <div class="overview">
            <h3>BOOKINGS</h3>
            <a class="block" href="/booking/make">New Booking</a>
            <a class="block" href="/booking/summary/upcoming">Bookings Overview</a>
        </div>

        <div class="overview">
            <h3>PROFILE</h3>
            <a class="block" href="/profile/view">View Profile</a>
            <a class="block" href="/profile/update">Update Details</a>
        </div>
    </div>
@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/form.js') }}"></script>
@endsection
