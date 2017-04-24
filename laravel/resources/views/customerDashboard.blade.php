@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')

    <div class="dashboard">
        <div id="greeting">Hello, {{ $user['customer_name'] }}!</div>
        <h2>Please fill out the form below to make a booking with us</h2>

        <form action="booking/customer" method="post">
            {{ csrf_field() }}
            <select id="business" name="business" placeholder="Business" required>
                <option value="" selected disabled>Find a Place</option>
                @foreach($businesses as $business)
                    <option value="{{ $business['business_id'] }}">{{ $business['business_name'] }}</option>
                @endforeach
            </select>

            <label for="date">Date</label>
            <input id="date" type="text" name="date">
            <label for="time">Time</label>
            <input id="time" type="time" name="time" min="09:00" max="18:00" step="1800" placeholder="09:00">

            <!-- displays a drop down list of available services by this business -->
            <label for="services">Service required</label>
            <select id="services" name="services">
                <option value="" selected disabled>Choose service</option>
            </select>

            <!-- let me know if this works, couldn't seed the employees table so this isn't working for me -->
            <div class="error">{{ $errors->first('employee_id') }}</div>
            <select name="employee_id">
                <!-- lists all available employees -->
                <option value="" selected disabled>Select employee</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee['employee_id'] }}">{{ $employee['employee_name'] }}</option>
                @endforeach
            </select>

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
                @foreach($employees as $employee)
                    <tr id="employee-{{ $employee['employee_id'] }}">
                        <td>{{ $employee['employee_name'] }}</td>
                        @foreach($timeSlots as $slot)
                            <td class="marker">
                                <span id="slot-{{ $slot }}:00-{{ $employee['employee_id'] }}" class="slot"></span>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
            <div id="note">[X] = slot unavailable</div>
        </div>
    </div>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/form.js') }}"></script>
@endsection
