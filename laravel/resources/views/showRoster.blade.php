@extends('layouts.template')

@section('title')
    Booking App
@endsection

@section('content')
    @include('includes.return')

    <div class="dashboard">
        <h2>Weekly Roster</h2>

        <table>
            <tr>
                <th class="head"></th>
                @foreach($dayShifts as $shift)
                    <th class="head">
                        {{ $shift['day'] }} <br>
                        {{ $shift['date'] }}
                    </th>
                @endforeach
            </tr>
            <tr>
                <th class="head">Day</th>
                @foreach($dayShifts as $shift)
                    <td>
                        {{ $shift['name'] }}
                    </td>
                @endforeach
            </tr>
            <tr>
                <th class="head">Night</th>
                @foreach($nightShifts as $shift)
                    <td>
                        {{ $shift['name'] }}
                    </td>
                @endforeach
            </tr>
        </table>
        
    </div>

@endsection
