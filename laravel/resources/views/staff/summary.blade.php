@extends('layouts.template')

@section('title')
    Staff overview - BookMe
@endsection

@section('nav')
    @include('nav.dashboard')
    @include('nav.logout')
@endsection

@section('content')

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
            <div class="empty-state">
                <h3>There is no staff.</h3>
                <a class="btn-state" href="/roster/add">Add staff</a>
            </div>
        @endif
    </div>

@endsection
