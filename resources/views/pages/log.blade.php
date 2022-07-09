@extends('layouts.home')
@section('content')
<div class="mx-1 px-0 mx-sm-5 px-sm-5">
    <div class="mx-1 px-0 mx-sm-5 px-sm-5">
        <form method="POST" action="{{ route('register.action') }}">
            @csrf
            <div class="form-group form-floating mb-3 d-flex">
                <input type="text" class="form-control flex-fill" name="inputFirstName" id="floatingFirstName" value="{{ old('inputFirstName') }}" placeholder="First name">
                <label for="floatingFirstName">Prenom</label>
            </div>

            <div class="form-group form-floating mb-3 d-flex">
                <input type="text" class="form-control flex-fill" name="inputLastName" id="floatingLastName" value="{{ old('inputLastName') }}" placeholder="Last name">
                <label for="floatingLastName">Nom</label>
            </div>

            <div class="form-group form-floating mb-3 d-flex">
                <input type="text" class="form-control flex-fill" name="inputPseudo" id="floatingPseudo" value="{{ old('inputPseudo') }}" placeholder="Pseudo">
                <label for="floatingPseudo">Pseudo</label>
            </div>
            
            <div class="form-group form-floating mb-3 d-flex">
                <input type="email" class="form-control flex-fill" name="inputEmail" id="floatingEmail" value="{{ old('inputEmail') }}" placeholder="name@example.com">
                <label for="floatingEmail">Email</label>
            </div>

            <div class="form-group form-floating mb-3 d-flex">
                <input type="text" class="form-control flex-fill" name="inputTel" id="floatingTel" value="{{ old('inputTel') }}" placeholder="Phone number">
                <label for="floatingTel">Téléphone</label>
            </div>

            <div class="form-group form-floating mb-3 d-flex">
                <input type="password" class="form-control flex-fill" name="inputPassword"  id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>

            <div class="form-group form-floating mb-3 d-flex">
                <input type="password" class="form-control flex-fill" name="inputPassword_confirmation" id="floatingConfirmPassword" placeholder="Confirm Password">
                <label for="floatingConfirmPassword">Confirm Password</label>
            </div>
            <div class="btn-G-L d-flex justify-content-end flex-row">
                <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal"
                        data-bs-target="#modalLogin">
                    Login
                </button>
                <button class="btn btn-primary" type="submit">Confirm</button>
            </div>
        </form>
    </div>
    
</div>
@endsection