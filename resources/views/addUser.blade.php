@extends('base')

@section('title', 'Création d\'un compte')

@section('content')

<form method="POST" action="{{route('addUser.post')}}" >
@csrf  
    @if ($errors->any())
        <div class="alert alert-warning">
          Le compte n'a pas été cree !!! &#9785;
        </div>
    @endif


    <div class="form-group">
      <label for="email">E-mail</label>
      <input type="email" id="email" name="email" 
             aria-describedby="email_feedback" class="form-control @error('email') is-invalid @enderror"> 
      @error('email')
      <div id="email_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>


    <div class="form-group">
      <label for="emailConfirmation">E-mail de confirmation</label>
      <input type="email" id="emailConfirmation" name="emailConfirmation"
             aria-describedby="email_feedback" class="form-control @error('email') is-invalid @enderror"> 
      @error('emailConfirmation')
      <div id="email_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>


    <div class="form-group">
      <label for="password">Choisissez un mot de passe</label>
      <input type="password" id="password" name="password"
             aria-describedby="password_feedback" class="form-control @error('password') is-invalid @enderror">  
      @error('password')
      <div id="password_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>

    <div class="form-group">
      <label for="passwordConfirmation">Confirmez votre mot de passe</label>
      <input type="password" id="passwordConfirmation" name="passwordConfirmation"
             aria-describedby="password_feedback" class="form-control @error('password') is-invalid @enderror">  
      @error('passwordConfirmation')
      <div id="password_feedback" class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>

    <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>

    <button type="submit" class="btn btn-primary">Soumettre</button>
</form>
<br><br>

<script src='https://www.google.com/recaptcha/api.js'></script>
@endsection