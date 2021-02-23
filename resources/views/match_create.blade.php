@extends('base')

@section('title', 'Création d\'un match')

@section('content')
<script>

</script>
<form method="POST" action="{{route('matches.store')}}">
  @csrf
@if ($errors->any())
        <div class="alert alert-warning">
            Le matche n'a pas pu être ajouté &#9785;
        </div>
    @endif
     

    <div class="form-group">
      <label for="team0">Équipe à domicile</label>
      <select class="form-control" id="team0" name="team0">
      @foreach($teams as $user)
      <option value="{{ $user['id'] }}" >{{ $user['name'] }}</option>
      @endforeach
      </select>
    </div>


    <div class="form-group">
      <label for="team1">Équipe à l'extérieur</label>
      <select class="form-control" id="team1" name="team1">
      
      @foreach($teams as $user)
      <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
      @endforeach
      
      </select>
    </div>


    <div class="form-group">
      <label for="date">Date</label>
      <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}">
    @if($errors->first('date'))
    <div class="alert-danger"> {{ $errors->first('date')}} </div>
    </div>
    @endif
    <div class="form-group">
      <label for="time">Heure</label>
      <input type="time" class="form-control" id="time" name="time" value="{{ old('time') }}">
      <div class="alert-danger"> {{ $errors->first('time')}} </div>
    </div>

    <div class="form-group">
      <label for="score0">Nombre de buts de l'équipe à domicile</label>
      <input type="number" class="form-control" id="score0" name="score0" min="0" value="{{ old('score0') }}">
      <div class="alert-danger"> {{ $errors->first('score0')}} </div>
    </div>

    <div class="form-group">
      <label for="score1">Nombre de buts de l'équipe à l'extérieur</label>
      <input type="number" class="form-control" id="score1" name="score1" min="0" value="{{ old('score1') }}">
      <div class="alert-danger"> {{ $errors->first('score1')}} </div>
    </div>

    <button type="submit" class="btn btn-primary">Soumettre</button>
</form>
@endsection