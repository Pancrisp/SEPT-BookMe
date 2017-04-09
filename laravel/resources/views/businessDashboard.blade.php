@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    @include('includes.header')

    <div class="dashboard">
        <h1>Hello, {{ $user['owner_name'] }} from {{ $user['business_name'] }}</h1>

        <div class="overview">
            <h3>Manage your bookings</h3>
            <a href="/bookings/summary?id={{ $user['business_id'] }}" >Bookings Overview</a>
        </div>

        <div class="overview">
            <h3>Staff Management</h3>
            <a class="block" href="/newstaff?id={{ $user['business_id'] }}">Add new employee</a>
            <a class="block" href="/newroster?id={{ $user['business_id'] }}">Add employee working time</a>
            <a class="block" href="/viewroster?id={{ $user['business_id'] }}">Show all employees' availability for next 7 days</a>
        </div>
    </div>

@endsection
