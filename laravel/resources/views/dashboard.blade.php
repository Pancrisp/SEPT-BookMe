@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    @include('includes.header')

    <div class="dashboard">
        <div class="overview-bookings">
            <h1>Bookings Overview</h1>
        </div>

        <div>
            <h1>Staff Roster</h1>
            <a href="/newstaff">+ Add new staff</a>
        </div>


    </div>

@endsection
