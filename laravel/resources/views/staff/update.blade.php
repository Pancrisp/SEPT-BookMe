@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')
    @include('includes.return')

    <div class="box">
        <h1>Update Staff Available Days</h1>

        <div class="success">{{ $errors->first('result') }}</div>

        @if(count($employees))
            <form action="/staff/update/submit" method="post">
                {{ csrf_field() }}
                <div class="error">{{ $errors->first('employee_id') }}</div>
                <select id="roster-select-employee" name="employee_id">
                    <option value="" selected disabled>Select employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee['employee_id'] }}">{{ $employee['employee_name'] }}</option>
                    @endforeach
                </select>

                <div class="error">{{ $errors->first('availability') }}</div>
                <h4>Available working days</h4>
                <div class="flex-container">
                    <div class="flex">
                        <label><input type="checkbox" name="availability[]" value="Mon">Monday</label>
                        <label><input type="checkbox" name="availability[]" value="Tue">Tuesday</label>
                        <label><input type="checkbox" name="availability[]" value="Wed">Wednesday</label>
                        <label><input type="checkbox" name="availability[]" value="Thu">Thursday</label>
                        <label><input type="checkbox" name="availability[]" value="Fri">Friday</label>
                    </div>
                    <div class="flex">
                        <label><input type="checkbox" name="availability[]" value="Sat">Saturday</label>
                        <label><input type="checkbox" name="availability[]" value="Sun">Sunday</label>
                    </div>
                </div>

                <button type="submit" name="submit">Submit</button>
            </form>
        @else
            <div>
                There is no staff. <a href="/staff/add">Add new staff</a>
            </div>
        @endif

    </div>

@endsection
