@extends('layouts.template')

@section('title')
    Business Owner Dashboard - BookMe
@endsection

@section('nav')
    @include('nav.logout')
@endsection

@section('content')

    <div class="dashboard">
        <h1>Hello, {{ $user['owner_name'] }} from {{ $user['business_name'] }}</h1>

        <div class="overview-container">
            <div class="overview">
                <h3>BOOKINGS</h3>
                <a class="block" href="/booking/make">New booking</a>
                <a class="block" href="/booking/summary/recent">Bookings overview</a>
            </div>

            <div class="overview">
                <h3>STAFF</h3>
                <a class="block" href="/staff/add">New staff</a>
                <a class="block" href="/staff/update">Update staff working days</a>
                <a class="block" href="/staff/summary">Staff summary</a>
                <a class="block" href="/staff/availability">Availability</a>
            </div>

            <div class="overview">
                <h3>ROSTER</h3>
                <a class="block" href="/roster/add">New roster</a>
                <a class="block" href="/roster/summary">Show roster</a>
            </div>

            <div class="overview">
                <h3>SETTINGS</h3>
                <a class="block" href="/profile">Profile</a>
                <a class="block" href="/business/hour">Opening hours</a>
                <a class="block" href="/business/activity">Business services</a>
            </div>
        </div>

    </div>

@endsection
