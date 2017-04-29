@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')
    @include('includes.return')

    <div class="dashboard">
    @foreach($dates as $date)
        <h2>{{ $date['date'] }}</h2>
        @foreach($employees as $employee)
            <!-- When there is no this type of booking, return message -->
            @if(count($bookings[$employee['employee_id']][$date['date']]))
                <!-- to calculate the length of this activity -->
                <?php $period = $business['slot_period'] * $employee['num_of_slots'] ?>
                <h3>{{ $employee['employee_name'] }} ({{ $employee['activity_name'] }} - {{ $period }} minutes) Booked</h3>
                <table>
                    <thead>
                    <th>ID</th>
                    <th>Time</th>
                    <th>Customer Name</th>
                    <th>Customer Contact</th>
                    <th>Customer Email</th>
                    </thead>
                    @foreach($bookings[$employee['employee_id']][$date['date']] as $booking)
                        <tr>
                            <td>{{ $booking['booking_id'] }}</td>
                            <td>{{ $booking['start_time'] }}</td>
                            <td>{{ $booking['customer_name'] }}</td>
                            <td>{{ $booking['mobile_phone'] }}</td>
                            <td>{{ $booking['email_address'] }}</td>
                        </tr>
                    @endforeach
                </table>
             @endif
        @endforeach
        <br><hr>
    @endforeach

@endsection
