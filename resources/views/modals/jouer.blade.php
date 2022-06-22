<div class="modal fade" id="modalJouer" tabindex="-1" aria-labelledby="jouer" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex flex-row">
                    <h5 class="modal-title me-3" id="jouer">
                        🍀🍀🍀 Miser pour jouer 🍀🍀🍀
                    </h5>
                </div>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('debitAll') }}">
                    @csrf
                    <div class="d-flex flex-row">
                        <div class="form-group form-floating mb-3 me-3 d-flex flex-column">
                            <div class="form-group form-floating mb-3 d-flex flex-fill">
                                <input
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    class="form-control flex-fill"
                                    name="inputAmount"
                                    id="floatingMontant"
                                    value="{{ old('inputAmount') }}"
                                    placeholder="Montant ➖ €"
                                    required
                                />
                                <label for="floatingMontant">Montant ➖ <span>€</span></label>
                            </div>
                        </div>
                        <div class="border border-3 rounded-3 form-group form-floating mb-3 d-flex flex-fill flex-column">
                            <div class="form-check form-switch">
                                <p class="mb-0">Choisir le(s) Participant(s) : </p>
                            </div>
                            @foreach ($participants as $participant)
                                <div class="ms-3 form-check form-switch">
                                    <input class="form-check-input"
                                        type="checkbox" 
                                        name="inputParticipantWinArray[]" 
                                        role="switch" 
                                        id="flexSwitchCheckDefault" 
                                        checked
                                        value="{{ $participant->pseudo }}">
                                    <label class="form-check-label" for="flexSwitchCheckDefault">{{ $participant->pseudo}}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex btn-G-L d-flex justify-content-center">
                        <button
                            class="btn btn-primary me-4"
                            type="submit"
                            data-bs-toggle="modal"
                            data-bs-target="#modalLogin"
                            style="width: 45%"
                            onclick="return confirm('Jouer et retirer la part de tous les participant(s)?');"
                        >
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex flex-end">
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
    <script src="/js/addGain.js"></script>
</div>
