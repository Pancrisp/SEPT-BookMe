@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    @include('includes.header')

    <div class="dashboard">
        <div id="greeting">Hello, {{ $user['customer_name'] }}!</div>
        <h2>View available booking times on</h2>
        <div class="search">
            <input id="date" type="text" placeholder="Select date">
            <button id="search-button">
                <img id="search-icon" src="./img/search.png" alt="search icon">
            </button>
        </div>

        <div class="results">
            <ul class="flex-container">
                <li class="flex"><a class="timeslot block-btn" href="#">09:00</a></li>
                <li class="flex"><a class="timeslot block-btn" href="#">10:00</a></li>
                <li class="flex"><a class="timeslot block-btn" href="#">11:00</a></li>
                <li class="flex"><a class="timeslot block-btn" href="#">12:00</a></li>
                <li class="flex"><a class="timeslot block-btn" href="#">13:00</a></li>
                <li class="flex"><a class="timeslot block-btn" href="#">14:00</a></li>
            </ul>
        </div>
    </div>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/view-slots.js') }}"></script>
@endsection
