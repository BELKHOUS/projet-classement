@extends('base')

@section('title')
Matche de l'équipe
@endsection

@section('content')

<table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>N°</th> 
                        <th>Équipe</th>
                        <th>MJ</th>
                        <th>G</th>
                        <th>N</th>
                        <th>P</th>
                        <th>BP</th>
                        <th>BC</th>
                        <th>DB</th>
                        <th>PTS</td>
                    </tr>
                </thead>



                <tbody>
                <tr>
                    <td>{{ $row['rank'] }}</td>
                    <td><a href="{{ route('teams.show', ['teamId'=>$row['team_id']]) }}">{{ $row['name'] }}</a></td>
                    <td>{{ $row['match_played_count'] }}</td>
                    <td>{{ $row['won_match_count'] }}</td>
                    <td>{{ $row['draw_match_count'] }}</td>
                    <td>{{ $row['lost_match_count'] }}</td>
                    <td>{{ $row['goal_for_count'] }}</td>
                    <td>{{ $row['goal_against_count'] }}</td>
                    <td>{{ $row['goal_difference'] }}</td>
                    <td>{{ $row['points'] }}</td>
                </tr>
                </tbody>
                </table>
                <table class="table table-striped">
                @foreach ($matches as $value)
                <tr>
                    <td>{{ $value['date']  }}                </td>
                    <td><a href="{{ route('teams.show', ['teamId'=>$value['team0']]) }}">{{ $value['name0'] }}</td>
                    <td>{{$value['score0']}} <span>-</span> {{$value['score1']}}</td>

                    <td><a href="{{ route('teams.show', ['teamId'=>$value['team1']]) }}">{{ $value['name1'] }}</td>
                </tr>
                @endforeach
            </table>
@endsection