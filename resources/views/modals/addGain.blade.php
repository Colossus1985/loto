<div class="modal fade" id="modalAddGain" tabindex="-1" aria-labelledby="addGain" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex flex-row">
                    <h5 class="modal-title me-3" id="addGain">
                        🥳🥳 Ajouter des Gain 🥳🥳
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
                <form method="POST" action="{{ route('addGain') }}">
                    @csrf
                    <div class="form-group form-floating mb-3 d-flex">
                        <div class="form-group form-floating me-3">
                            
                            <input
                                type="number"
                                min="0"
                                step="0.01"
                                class="form-control flex-fill"
                                name="inputAmount"
                                id="floatingMontant"
                                value="{{ old('inputAmount') }}"
                                placeholder="Montant ➕ €"
                                required
                            />
                            <label for="floatingMontant">Montant ➕ <span>€</span></label>
                        </div>
                        <div class="form-group form-floating">
                            <input
                                type="number"
                                min="0"
                                step="1"
                                class="form-control flex-fill"
                                name="inputNbPersonnes"
                                id="floatingPersonnes"
                                value="{{ old('inputNbPersonnes') }}"
                                placeholder="Nombre de Participants"
                                required
                            />
                            <label for="floatingPersonnes">Nombre de Participants</label>
                        </div>
                    </div>
                    <div class="form-group form-floating mb-3 d-flex">
                        <input
                            type="date"
                            class="form-control flex-fill"
                            name="inputDate"
                            id="floatingDate"
                            value="{{ old('inputDate') }}"
                            placeholder="gain"
                            required
                        />
                        <label for="floatingDate">Date</label>
                    </div>

                    <div class="d-flex btn-G-L d-flex justify-content-center">
                        <button
                            class="btn btn-primary me-4"
                            type="submit"
                            data-bs-toggle="modal"
                            data-bs-target="#modalLogin"
                            style="width: 45%"
                            onclick="return confirm('Ajouter les gains?');"
                        >
                            Rajouter
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