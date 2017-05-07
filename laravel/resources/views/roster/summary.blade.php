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
                @foreach($dates as $date)
                    <th class="head">
                        {{ $date['day'] }} <br>
                        {{ $date['date'] }}
                    </th>
                @endforeach
            </tr>
            @foreach($activities as $activity)
                <tr>
                    <th class="head">{{ $activity['activity_name'] }}</th>
                    @foreach($dates as $date)
                        <td>
                            @foreach($rosters[$activity['activity_id']][$date['date']] as $roster)
                            <p>
                                {{ $roster['employee_name'] }}
                            </p>
                            @endforeach
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>
    </div>

@endsection
