@extends('layouts.template')

@section('title')
    Create a new booking - BookMe
@endsection

@section('nav')
    @include('nav.dashboard')
    @include('nav.logout')
@endsection

@section('content')

    <div>
        <a href="{{ URL::previous() }}">Pick another one</a>
    </div>

    <div class="dashboard">
        <h1>Welcome to {{ $business['business_name'] }}</h1>

        <form action="/booking" method="post">
            {{ csrf_field() }}
            <input id="business" name="business" value="{{ $request['business'] }}" hidden>
            <input id="customer" name="customer" value="{{ $request['customer'] }}" hidden>
            <input id="date" name="date" value="{{ $request['date'] }}" hidden>

            <!-- displays a drop down list of available services by this business -->
            <label for="service">Service required</label>
            <select id="service" name="service" required>
                <option value="0" selected disabled>Choose service</option>
                @foreach($activities as $activity)
                    <option value="{{ $activity['activity_id'] }}">{{ $activity['activity_name'] }}</option>
                @endforeach
            </select>

            <!-- displays a drop down list of available employees of this business -->
            <div id="employee-list" hidden>
                <label for="employee">Preferred staff</label>
                <select id="employee" name="employee" required>
                    <option value="0" selected disabled>Choose staff</option>
                    @foreach($employees as $employee)
                        <option class="employee-option" id="employee-{{ $employee['employee_id'] }}" value="{{ $employee['employee_id'] }}">{{ $employee['employee_name'] }}</option>
                    @endforeach
                </select>
            </div>

            <!-- input field to enter booking time -->
            <div id="booking-time-picker">
                <label for="time">Time</label>
                <input id="time" type="time" name="time" min="09:00" max="18:00" step="1800" placeholder="09:00" required>
            </div>

            <!-- unavailable message -->
            <div id="unavailable-message" hidden>
                <h3>Service selected is currently unavailable on {{ $request['date'] }}</h3>
                <h3>Please select another service or pick another date</h3>
            </div>

            @if($error != "")
                <div class="error">{{ $error }}</div>
            @endif
            <button type="submit">Make Booking</button>
        </form>

        <!-- A table displaying current bookings on the date, group by activities -->
        <h2>Current bookings on {{ $request['date'] }}</h2>
        @foreach($activities as $activity)
            <!-- to calculate the length of this activity -->
            <?php $period = $business['slot_period'] * $activity['num_of_slots'] ?>
            <h3>{{ $activity['activity_name'] }} ({{ $period }} minutes) Booked</h3>

            <!-- When there is no this type of booking, return message -->
            @if(!count($bookings[$activity['activity_id']]))
                <div>There is no booking.</div>

            <!-- return the table with details otherwise-->
            @else
                <table>
                    <thead>
                        <th id="table-time">Time</th>
                        <th>Staff</th>
                    </thead>
                    @foreach(($bookings[$activity['activity_id']]) as $booking)
                        <tr>
                            <td>{{ $booking['start_time'] }}</td>
                            <td>{{ $booking['employee_name'] }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
        @endforeach
    </div>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/form.js') }}"></script>
@endsection
