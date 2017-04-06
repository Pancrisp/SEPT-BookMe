@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    @include('includes.header')

    <div class="dashboard">
        <div id="greeting">Hello, {{ $user['customer_name'] }}!</div>
        <h2>Choose your booking date and time</h2>
        <div>
            <label for="date">Date</label>
            <input id="date" type="text" name="date">
        </div>

        <div class="time">
            <label for="time">Time</label>
            <input id="time" type="time" name="time" min="09:00" max="18:00" step="1800" placeholder="09:00"
        </div>

        <div class="booked-slots">
            <h3>Current Bookings on <span id="date-selected"></span></h3>

            <table>
                <tr>
                    <th class="head"></th>
                    <th class="head">09:00</th>
                    <th class="head">09:30</th>
                    <th class="head">10:00</th>
                    <th class="head">10:30</th>
                    <th class="head">11:00</th>
                    <th class="head">11:30</th>
                    <th class="head">12:00</th>
                    <th class="head">12:30</th>
                    <th class="head">13:00</th>
                    <th class="head">13:30</th>
                    <th class="head">14:00</th>
                    <th class="head">14:30</th>
                    <th class="head">15:00</th>
                    <th class="head">15:30</th>
                    <th class="head">16:00</th>
                    <th class="head">16:30</th>
                </tr>
                <tr>
                    <td>Availability</td>
                    <!-- feed time slots that have already been taken here -->
                </tr>
            </table>

        </div>

        <!-- this is the old template, please don't remove it
        <div class="results">
            <ul class="flex-container"></ul>
        </div>
        -->
    </div>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/dates.js') }}"></script>
@endsection
