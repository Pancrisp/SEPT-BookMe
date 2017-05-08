@extends('layouts.template')

@section('title')
    Customer Dashboard - Booking App
@endsection

@section('content')
    @include('includes.return')

    <div class="dashboard">
        <div id="greeting">Hello, {{ $user['customer_name'] }}!</div>

        <div class="overview">
            <h3>BOOKINGS</h3>
            <a class="block" href="/booking/make">New booking</a>
            <a class="block" href="/booking/summary/today">Your booking history</a>
        </div>

        <div class="overview">
            <h3>PROFILE</h3>
            <a class="block" href="/profile/display">View profile</a>
            <a class="block" href="/profile/update">Update profile</a>
        </div>
    </div>
@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/form.js') }}"></script>
@endsection
