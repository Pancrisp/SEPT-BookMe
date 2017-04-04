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
            <select class="" name="employee_id">
                <option value="" selected disabled>Select employee</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee['employee_id'] }}">{{ $employee['employee_name'] }}</option>
                @endforeach
            </select>
            <input id="date" type="text" placeholder="Select date">
            <input type="radio" name="shift" value="Day">Day
            <input type="radio" name="shift" value="Night">Night

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

    <script>
        var currentDate = new Date();
        var months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

        var day = currentDate.getDate();
        var month = months[currentDate.getMonth()];
        var year = currentDate.getFullYear();
        var currentDate = day + " " + month + ", " + year;

        $(document).ready(function() {
            $('#date').attr("placeholder", currentDate);
        });

        $(function () {
            $('#date').datepicker({ minDate: 0, maxDate: '+1M', dateFormat: 'd M, yy' });
            $('#date').attr("data-date", 'yy-mm-dd');
        });
    </script>

@endsection
