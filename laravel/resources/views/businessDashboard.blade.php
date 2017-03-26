@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    @include('includes.header')

    <div class="dashboard">
        <div>Hello, {{ $owner_name }} for {{ $business_name }}</div>
        <div class="overview-bookings">
            <a href="/bookings/summary" >Bookings Overview</a>
        </div>

        <div>
            <h1>Staff Roster</h1>
            <a href="/newstaff">+ Add new staff</a>
        </div>


    </div>

@endsection