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
            <input id="time" type="time" name="time" min="09:00" max="18:00" step="1800" placeholder="09:00">
        </div>

        <div class="booked-slots">
            <h3>Current Bookings on <span id="date-selected"></span></h3>

            <table>
                <tr>
                    <th class="head"></th>
                    @foreach($timeSlots as $slot)
                        <th class="head">{{ $slot }}</th>
                    @endforeach
                </tr>
                <tr>
                    <td>Availability</td>
                    @foreach($timeSlots as $slot)
                        <td>
                            <span id="slot-{{ $slot }}:00" class="slot"></span>
                        </td>
                    @endforeach
                </tr>
            </table>
        </div>
    </div>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/dates.js') }}"></script>
@endsection
