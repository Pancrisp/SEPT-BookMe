@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')
    @include('includes.return')

    <div class="dashboard">
        <h1>Hello, {{ $user['owner_name'] }} from {{ $user['business_name'] }}</h1>

        <div class="overview">
            <h3>BOOKINGS</h3>
            <a class="block" href="/booking/make">New Booking</a>
            <a class="block" href="/booking/summary">Bookings Overview</a>
        </div>

        <div class="overview">
            <h3>STAFF</h3>
            <a class="block" href="/staff/add">New Staff</a>
            <a class="block" href="/staff/update">Update staff working days</a>
            <a class="block" href="/staff/summary">Staff Summary</a>
            <a class="block" href="/staff/availability">Availability</a>
        </div>

        <div class="overview">
            <h3>ROSTER</h3>
            <a class="block" href="/roster/add">New Roster</a>
            <a class="block" href="/roster/summary">Show roster</a>
        </div>
    </div>

@endsection
