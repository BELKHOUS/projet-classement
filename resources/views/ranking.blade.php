@extends('base')

@section('title')
Classement
@endsection

@section('content')
<table class="table table-striped">
<thead class="thead-dark">
                    <tr>
                        <th>N°</th> 
                        <th>Equipe</th>
                        <th>MJ</th>
                        <th>G</th>
                        <th>N</th>
                        <th>P</th>
                        <th>BP</th>                                 
                        <th>BC</th>
                        <th>DB</th>
                        <th>PTS</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($ranking as $user)
                @if($cookie === $user['team_id'])
                
                    
                    <tr class="table-primary">
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
                @endif 
                @if($cookie !== $user['team_id'])
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
                @endif 
                    @endforeach
                </tbody>
</table>
@endsection






