@extends('layouts.template')

@section('title')
    Create a new roster - BookMe
@endsection

@section('nav')
    @include('nav.dashboard')
    @include('nav.logout')
@endsection

@section('content')

    <div class="box">
        <h1>Roster a Staff</h1>
        <div class="success">{{ $errors->first('result') }}</div>

        @if(count($employees))
            <form action="/roster/add/submit" method="post">
                {{ csrf_field() }}
                <div class="error">{{ $errors->first('employee_id') }}</div>
                <select id="roster-select-employee" name="employee_id" value="{!! old('employee_id') !!}">
                    <option value="" selected disabled>Select staff</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee['employee_id'] }}">{{ $employee['employee_name'] }}</option>
                    @endforeach
                </select>

                <div id="employee-details" hidden>
                    <h3>Activity in charge: <span id="activity-in-charge"></span></h3>
                    <h3>Available on: <span id="available-days"></span></h3>
                </div>

                <div class="error">{{ $errors->first('date') }}</div>
                <input id="roster-date" type="text" placeholder="Select date" value="{!! old('date') !!}">
                <input id="dateHidden" type="hidden" name="date" value="{!! old('date') !!}">

                <button type="submit" name="submit">Add/Update</button>
            </form>
        @else
            <div>
                There is no staff. <a href="/staff/add">Add new staff</a>
            </div>
        @endif

    </div>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/form.js') }}"></script>
@endsection
