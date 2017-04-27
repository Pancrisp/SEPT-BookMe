@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')

    <div class="dashboard">
        <h1>Hello, {{ $business['owner_name'] }} from {{ $business['business_name'] }}</h1>

        <div class="overview">
            <h3>BOOKINGS</h3>
            <a class="block" href="/booking/owner?id={{ $business['business_id'] }}">Make a booking</a>
            <a class="block" href="/booking/summary?id={{ $business['business_id'] }}" >Bookings Overview</a>
        </div>

        <div class="overview">
            <h3>STAFF</h3>
            <a class="block" href="/staff/add?id={{ $business['business_id'] }}">Add new staff</a>
            <a class="block" href="/staff/update?id={{ $business['business_id'] }}">Update staff working days</a>
            <a class="block" href="/staff/summary?id={{ $business['business_id'] }}">Staff Summary</a>
        </div>

        <div class="overview">
            <h3>ROSTER</h3>
            <a class="block" href="/roster/add?id={{ $business['business_id'] }}">Add new roster</a>
            <a class="block" href="/roster/summary?id={{ $business['business_id'] }}">Show roster (next week)</a>
        </div>
    </div>

@endsection
