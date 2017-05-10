@extends('layouts.template')

@section('title')
    Cancel a booking - BookMe
@endsection

@section('content')
    @include('includes.return')

    <nav>
        <a href="{{ URL::previous() }}">Cancel another one</a>
    </nav>

    <div class="dashboard">
        <h1>Confirm to cancel the following order</h1>

        @if(\Illuminate\Support\Facades\Auth::user()['user_type'] == 'customer')
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>Business</th>
                        <th>Activity</th>
                        <th>Staff</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $booking['date'] }}</td>
                        <td>{{ $booking['start_time'] }}</td>
                        <td>{{ $booking['business_name'] }}</td>
                        <td>{{ $booking['activity_name'] }}</td>
                        <td>{{ $booking['employee_name'] }}</td>
                        <td>{{ $booking['address'] }}</td>
                        <td>{{ $booking['mobile_phone'] }}</td>
                        <td>{{ $booking['email_address'] }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>Activity</th>
                        <th>Staff</th>
                        <th>Customer Name</th>
                        <th>Customer Contact</th>
                        <th>Customer Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $booking['booking_id'] }}</td>
                        <td>{{ $booking['date'] }}</td>
                        <td>{{ $booking['start_time'] }}</td>
                        <td>{{ $booking['activity_name'] }}</td>
                        <td>{{ $booking['employee_name'] }}</td>
                        <td>{{ $booking['customer_name'] }}</td>
                        <td>{{ $booking['mobile_phone'] }}</td>
                        <td>{{ $booking['email_address'] }}</td>
                    </tr>
                </tbody>
            </table>
        @endif

        <form action="/booking/cancel/submit" method="post">
            {{ csrf_field() }}
            <input id="booking" name="booking" value="{{ $booking['booking_id'] }}" hidden>
            <button type="submit">Confirm to Cancel</button>
        </form>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/form.js') }}"></script>
@endsection
