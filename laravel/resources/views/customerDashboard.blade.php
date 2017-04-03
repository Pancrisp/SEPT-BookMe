@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    @include('includes.header')
    <div class="dashboard">
        <div id="greeting">Hello, {{ $user['customer_name'] }}!</div>
        <h2>View available booking times on</h2>
        <input id="date" type="text" placeholder="Select date">
        <button id="search-button">
            <img id="search-icon" src="./img/search.png" alt="search icon">
        </button>
    </div>
@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/view-slots.js') }}"></script>

    <script>
        var currentDate = new Date();
        var months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

        var day = currentDate.getDate();
        var month = months[currentDate.getMonth()];
        var year = currentDate.getFullYear();
        var currentDate = day + " " + month + ", " + year;

        $(document).ready(function() {
            $('#date').attr("placeholder", currentDate);
        });

        $( function () {
            $('#date').datepicker({ minDate: 0, maxDate: '+3M', dateFormat: 'd M, yy' });
        });
    </script>
@endsection
