@extends('base')

@section('title', 'Suppression d\'une equipe')

@section('content')
<form method="POST" action="{{route('delete_team.store')}}">

  @csrf
@if ($errors->any())
        <div class="alert alert-warning">
            L\'équipe n'a pas pu être supprimée &#9785;
        </div>
    @endif
     

    <div class="form-group">
      <label for="team0">Equipe a supprimer</label>
      <select class="form-control" id="team_delete" name="team_delete">
      @foreach($teams as $user)
      <option value="{{ $user['id'] }}" >{{ $user['name'] }}</option>
      @endforeach
      </select>
    </div>

    <button type="submit" class="btn btn-primary">supprimer</button>
</form>
@endsection