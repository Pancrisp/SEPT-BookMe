@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')

    <div class="dashboard">
        <h1>Hello, {{ $user['owner_name'] }} from {{ $user['business_name'] }}</h1>

        <div class="overview">
            <h3>BOOKINGS</h3>
            <a class="block" href="/booking/owner?id={{ $user['business_id'] }}">Make a booking</a>
            <a class="block" href="/booking/summary?id={{ $user['business_id'] }}" >Bookings Overview</a>
        </div>

        <div class="overview">
            <h3>STAFF</h3>
            <a class="block" href="/staff/add?id={{ $user['business_id'] }}">Add new staff</a>
            <a class="block" href="/staff/update?id={{ $user['business_id'] }}">Update staff working days</a>
            <a class="block" href="/staff/summary?id={{ $user['business_id'] }}">Staff Summary</a>
        </div>

        <div class="overview">
            <h3>ROSTER</h3>
            <a class="block" href="/roster/add?id={{ $user['business_id'] }}">Add new roster</a>
            <a class="block" href="/roster/summary?id={{ $user['business_id'] }}">Show roster (next week)</a>
        </div>
    </div>

@endsection
