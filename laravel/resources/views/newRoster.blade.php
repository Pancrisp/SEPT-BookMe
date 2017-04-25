@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')
    @include('includes.return')

    <div class="box">
        <h1>Add Employee Working Times</h1>
        <div class="success">{{ $errors->first('result') }}</div>

        <form action="/roster/add/submit" method="post">
            {{ csrf_field() }}
            <div class="error">{{ $errors->first('employee_id') }}</div>
            <select id="roster-select-employee" name="employee_id">
                <option value="" selected disabled>Select employee</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee['employee_id'] }}">{{ $employee['employee_name'] }}</option>
                @endforeach
            </select>

            <h4>Choose available working dates</h4>
            <div class="error">{{ $errors->first('date') }}</div>
            <input id="roster-date" type="text" placeholder="Select date" value="">
            <input id="dateHidden" type="hidden" name="date" value="">

            <button type="submit" name="submit">Add/Update</button>
        </form>

    </div>
    
@endsection

@section('pageSpecificJs')
    <script src="{{ asset('js/form.js') }}"></script>
@endsection
