<div class="modal fade" id="modalAddParticipant" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un Participant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('addParticipant') }}">
                    @csrf
                    <div class="form-group form-floating mb-3 d-flex">
                        <input type="text" class="form-control flex-fill" name="inputFirstName" id="floatingFirstName" value="{{ old('inputFirstName') }}" placeholder="First name" required>
                        <label for="floatingFirstName">First Name</label>
                    </div>
            
                    <div class="form-group form-floating mb-3 d-flex">
                        <input type="text" class="form-control flex-fill" name="inputLastName" id="floatingLastName" value="{{ old('inputLastName') }}" placeholder="Last name" required>
                        <label for="floatingLastName">Last Name</label>
                    </div>
            
                    <div class="form-group form-floating mb-3 d-flex">
                        <input type="text" class="form-control flex-fill" name="inputPseudo" maxlength="15" id="floatingPseudo" value="{{ old('inputPseudo') }}" placeholder="Pseudo" required>
                        <label for="floatingPseudo">Pseudo</label>
                    </div>
                    
                    <div class="form-group form-floating mb-3 d-flex">
                        <input type="email" class="form-control flex-fill" name="inputEmail" id="floatingEmail" value="{{ old('inputEmail') }}" placeholder="name@example.com">
                        <label for="floatingEmail">Email address</label>
                    </div>

                    <div class="form-group form-floating mb-3 d-flex">
                        <input type="text" class="form-control flex-fill" name="inputTel" id="floatingTel" value="{{ old('inputTel') }}" placeholder="Phone number" required>
                        <label for="floatingTel">Phone number</label>
                    </div>
                    <div class="border border-3 rounded-3 form-group form-floating mb-3 d-flex flex-fill flex-column">
                        <div class="">
                            <p class="mt-1 mb-2 ps-3">Choisis le Group : </p>
                        </div>
                         @foreach ($groups as $group)
                            <div class="ms-3 form-check form-switch">
                                <input class="form-check-input me-3"
                                    type="radio" 
                                    name="inputNameGroup" 
                                    role="switch" 
                                    id="flexSwitchNameGroup" 
                                    value="{{ $group->nameGroup }}">
                                <label class="form-check-label" for="flexSwitchNameGroup">{{ $group->nameGroup }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex btn-G-L d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit" data-bs-toggle="modal" data-bs-target="#modalLogin" style="width: 45%;">Ajouter</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-start">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>