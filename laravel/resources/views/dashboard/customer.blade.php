@extends('layouts.template')

@section('title')
    Customer Dashboard - BookMe
@endsection

@section('nav')
    @include('nav.logout')
@endsection

@section('content')

    <div class="dashboard">
        <div id="greeting">Hello, {{ $user['customer_name'] }}!</div>

        <div class="overview">
            <h3>BOOKINGS</h3>
            <a class="block" href="/booking/make">New booking</a>
            <a class="block" href="/booking/summary/today">Your booking history</a>
        </div>

        <div class="overview">
            <h3>PROFILE</h3>
            <a class="block" href="/profile">Your details</a>
        </div>
    </div>
@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/form.js') }}"></script>
@endsection
