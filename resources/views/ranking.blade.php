@extends('base')

@section('title')
Classement
@endsection

@section('content')
<table class="table table-striped">
<thead class="thead-dark">
                    <tr>
                        <th>N°</th> 
                        <td>Equipe</td>
                        <td>MJ</td>
                        <td>G</td>
                        <td>N</td>
                        <td>P</td>
                        <td>BP</td>                                 
                        <td>BC</td>
                        <td>DB</td>
                        <td>PTS</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ranking as $user)
                    <tr>
                    <td>{{ $user['rank'] }}</td>
                    <td><a href="{{ route('teams.show', ['teamId'=>$user['team_id']]) }}">{{ $user['name'] }}</a></td>
                    <td>{{ $user['match_played_count'] }}</td>
                    <td>{{ $user['won_match_count'] }}</td>
                    <td>{{ $user['draw_match_count'] }}</td>
                    <td>{{ $user['lost_match_count'] }}</td>
                    <td>{{ $user['goal_for_count'] }}</td>
                    <td>{{ $user['goal_against_count'] }}</td>
                    <td>{{ $user['goal_difference'] }}</td>
                    <td>{{ $user['points'] }}</td>
                </tr>
                    @endforeach
                </tbody>
</table>
@endsection






