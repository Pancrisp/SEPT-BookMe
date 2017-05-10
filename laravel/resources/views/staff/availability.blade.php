@extends('layouts.template')

@section('title')
    BookMe
@endsection

@section('content')
    @include('includes.return')

    <div class="dashboard">

        @if(count($dates))
            <!-- more dates selection as pagination -->
            <hr>
            @foreach($dates as $date)
                <span class="
                    @if($date['date'] == $dateSelected)
                        list-page-selected
                    @else
                        list-page
                    @endif
                ">
                    <a href="/staff/availability?date={{ $date['date'] }}">{{ $date['date'] }}</a>
                </span>
            @endforeach

            @foreach($employees as $employee)
                <!-- to calculate the length of this activity -->
                <?php $period = $business['slot_period'] * $employee['num_of_slots'] ?>
                <h3>{{ $employee['employee_name'] }} ({{ $employee['activity_name'] }} - {{ $period }} minutes) Booked</h3>
                <!-- When there is no this type of booking, return message -->
                @if(!count($bookings[$employee['employee_id']]))
                    <div>There is no booking.</div>
                <!-- return the table with details otherwise-->
                @else
                <table>
                    <thead>
                    <th>ID</th>
                    <th>Time</th>
                    <th>Customer Name</th>
                    <th>Customer Contact</th>
                    <th>Customer Email</th>
                    </thead>
                    @foreach($bookings[$employee['employee_id']] as $booking)
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

        @else
            <div>
                There is no staff. <a href="/staff/add">Add new staff</a>
            </div>
        @endif

    </div>

@endsection
