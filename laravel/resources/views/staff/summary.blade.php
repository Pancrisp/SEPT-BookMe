@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')
    @include('includes.return')

    <div class="dashboard">
        <h2>Staff Summary</h2>
        @if(count($employees))
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>TFN</th>
                    <th>Contact</th>
                    <th>Activity</th>
                    <th>Available Days</th>
                </tr>
                </thead>
                <tbody>
                @foreach($employees AS $employee)
                    <tr>
                        <td>{{ $employee['employee_name'] }}</td>
                        <td>{{ $employee['TFN'] }}</td>
                        <td>{{ $employee['mobile_phone'] }}</td>
                        <td>{{ $employee['activity_name'] }}</td>
                        <td>{{ $employee['available_days'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div>
                There is no staff. <a href="/staff/add">Add new staff</a>
            </div>
        @endif
    </div>

@endsection
