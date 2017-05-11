@extends('layouts.template')

@section('title')
    Roster overview - BookMe
@endsection

@section('nav')
    @include('nav.dashboard')
    @include('nav.logout')
@endsection

@section('content')

    <div class="dashboard">
        <h2>Weekly Roster</h2>

        @if(count($dates))
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
        @else
            <div>
                There is no roster. <a href="/roster/add">Add new roster</a>
            </div>
        @endif
    </div>

@endsection
