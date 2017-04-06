@extends('layouts.template')

@section('title')
    Restaurant Booking App
@endsection

@section('content')
    @include('includes.header')

    <div class="box">
        <h1>Add Employee Working Times</h1>
        <form action="/addroster" method="post">
            {{ csrf_field() }}
            <select name="employee_id">
                <option value="" selected disabled>Select employee</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee['employee_id'] }}">{{ $employee['employee_name'] }}</option>
                @endforeach
            </select>
            <h4>Choose available working dates</h4>

            <!-- error message for date field needs to be put here, but the name 'date' can't be found -->

            <input id="roster-date" type="text" placeholder="Select date" value="">
            <input id="dateHidden" type="hidden" name="dateHidden" value="">
            <h4>Shift</h4>
            <div class="error">{{ $errors->first('shift') }}</div>
            <div class="flex-container">
                <div class="flex">
                    <input type="radio" name="shift" value="Day">Day
                </div>
                <div class="flex">
                    <input type="radio" name="shift" value="Night">Night
                </div>
            </div>
            <button type="submit" name="submit">Add</button>
        </form>
    </div>

    <div class="col-lg-4">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/dates.js') }}"></script>
@endsection
