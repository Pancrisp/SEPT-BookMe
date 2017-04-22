@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')

    <div class="dashboard">
        <div id="greeting">Hello, {{ $user['customer_name'] }}!</div>
        <h2>Choose your booking date and time</h2>

        <form action="booking/customer" method="post">
            {{ csrf_field() }}
            <select id="business" name="business" placeholder="Business" required>
                <option value="" selected disabled>Find a Place</option>
                @foreach($businesses as $business)
                    <option value="{{ $business['business_id'] }}">{{ $business['business_name'] }}</option>
                @endforeach
            </select>
            <input id="date" type="text" name="date">
            <input id="time" type="time" name="time" min="09:00" max="18:00" step="1800" placeholder="09:00">
            <button type="submit">Make Booking</button>
        </form>

        <div class="booked-slots" hidden>
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
                        <td class="marker">
                            <span id="slot-{{ $slot }}:00" class="slot"></span>
                        </td>
                    @endforeach
                </tr>
            </table>
            <div id="note">[X] = slot unavailable</div>
        </div>
    </div>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/dates.js') }}"></script>
@endsection
