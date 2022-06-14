<div class="modal fade" id="modalAddMoney{{$participant->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Ajouter des Fonds pour {{$participant->pseudo}}
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('addMoney', $participant->id) }}">
                    @csrf
                    <div class="form-group form-floating mb-3 d-flex">
                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            class="form-control flex-fill"
                            name="inputMontant"
                            id="floatingMontant"
                            value="{{ old('inputMontant') }}"
                            placeholder="First name"
                        />
                        <label for="floatingMontant">Montant</label>
                    </div>

                    <div class="d-flex btn-G-L d-flex justify-content-center">
                        <button
                            class="btn btn-primary me-4"
                            type="submit"
                            data-bs-toggle="modal"
                            data-bs-target="#modalLogin"
                            style="width: 45%"
                            onclick="return confirm('Ajouter les fonds pour {{ $participant->pseudo }} ?');"
                        >
                            Confirm
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>