@extends('base')

@section('title', 'Changement de mot de passe')

@section('content')

<form method="POST" action="{{route('changePassword.post')}}" >
@csrf  
    @if ($errors->any())
        <div class="alert alert-warning">
          Un des champs n'est pas correct &#9785;
        </div>
    @endif
    <div class="form-group">
      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" value=""
             aria-describedby="email_feedback" class="form-control @error('email') is-invalid @enderror"> 
      @error('email')
      <div id="email_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>


    <div class="form-group">
      <label for="oldPassword">Ancien mot de passe</label>
      <input type="password" id="oldPassword" name="oldPassword" value=""
             aria-describedby="password_feedback" class="form-control @error('password') is-invalid @enderror">  
      @error('oldPassword')
      <div id="password_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>

    <div class="form-group">
      <label for="newPassword">Nouveau mot de passe</label>
      <input type="password" id="newPassword" name="newPassword"
             aria-describedby="password_feedback" class="form-control @error('password') is-invalid @enderror">  
      @error('newPassword')
      <div id="password_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary">Envoyer</button>
</form>
<br><br>
@endsection