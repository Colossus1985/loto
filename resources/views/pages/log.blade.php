@extends('layouts.home')
@section('content')
<div class="mx-1 px-0 mx-sm-5 px-sm-5">
    <div class="mx-1 px-0 mx-sm-5 px-sm-5">
        <form method="POST" action="{{ route('register.action') }}">
            @csrf
            <div class="form-group form-floating mb-3 d-flex">
                <input type="text" class="form-control flex-fill" maxlength="20" name="inputFirstName" id="floatingFirstName" value="{{ old('inputFirstName') }}" placeholder="First name" required>
                <label for="floatingFirstName">Prenom</label>
            </div>

            <div class="form-group form-floating mb-3 d-flex">
                <input type="text" class="form-control flex-fill" maxlength="20" name="inputLastName" id="floatingLastName" value="{{ old('inputLastName') }}" placeholder="Last name" required>
                <label for="floatingLastName">Nom</label>
            </div>

            <div class="form-group form-floating mb-3 d-flex">
                <input type="text" class="form-control flex-fill" maxlength="15" name="inputPseudo" id="floatingPseudo" value="{{ old('inputPseudo') }}" placeholder="Pseudo" required>
                <label for="floatingPseudo">Pseudo</label>
            </div>
            
            <div class="form-group form-floating mb-3 d-flex">
                <input type="email" class="form-control flex-fill" maxlength="50" name="inputEmail" id="floatingEmail" value="{{ old('inputEmail') }}" placeholder="name@example.com">
                <label for="floatingEmail">Email</label>
            </div>

            <div class="form-group form-floating mb-3 d-flex">
                <input type="text" class="form-control flex-fill" name="inputTel" minlength="10" maxlength="15" id="floatingTel" value="{{ old('inputTel') }}" placeholder="Phone number" required>
                <label for="floatingTel">Téléphone</label>
            </div>

            <div class="form-group form-floating mb-3 d-flex">
                <input type="password" class="form-control flex-fill" name="inputPassword" minlength="3" maxlength="20"  id="floatingPassword" placeholder="Password" required>
                <label for="floatingPassword">Password</label>
            </div>

            <div class="form-group form-floating mb-3 d-flex">
                <input type="password" class="form-control flex-fill" name="inputPassword_confirmation" minlength="3" maxlength="20" id="floatingConfirmPassword" placeholder="Confirm Password" required>
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