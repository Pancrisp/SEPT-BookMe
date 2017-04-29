@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')

    <nav>
        <a href="{{ URL::previous() }}">Pick another one</a>
    </nav>

    <div class="dashboard">
        <h1>Welcome to {{ $business['business_name'] }}</h1>
        <div class="success">{{ $errors->first('result') }}</div>

        <form action="/booking/submit" method="post">
            {{ csrf_field() }}
            <input id="business" name="business" value="{{ $request['business'] }}" hidden>
            <input id="customer" name="customer" value="{{ $request['customer'] }}" hidden>
            <input id="date" name="date" value="{{ $request['date'] }}" hidden>

            <!-- displays a drop down list of available services by this business -->
            <label for="service">Service required</label>
            <div class="error">{{ $errors->first('service') }}</div>
            <select id="service" name="service">
                <option value="" selected disabled>Choose service</option>
                @foreach($activities as $activity)
                    <option value="{{ $activity['activity_id'] }}">{{ $activity['activity_name'] }}</option>
                @endforeach
            </select>

            <!-- displays a drop down list of available employees of this business -->
            <label for="employee">Preferred staff</label>
            <div class="error">{{ $errors->first('employee') }}</div>
            <select id="employee" name="employee">
                <option value="" selected disabled>Choose staff</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee['employee_id'] }}">{{ $employee['employee_name'] }}</option>
                @endforeach
            </select>

            <!-- input field to enter booking time -->
            <label for="time">Time</label>
            <div class="error">{{ $errors->first('time') }}</div>
            <input id="time" type="time" name="time" min="09:00" max="18:00" step="1800" placeholder="09:00">

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
                        <th>Time</th>
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
