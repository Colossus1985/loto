<div class="modal fade" id="modalAddGroup" tabindex="-1" aria-labelledby="addGain" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex flex-row">
                    <h5 class="modal-title me-3" id="addGain">
                        Créer un Groupe
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
                <form method="POST" action="{{ route('addGroup') }}">
                    @csrf
                    <div class="d-flex flex-row">
                        <div class="form-group form-floating mb-3 me-3 d-flex flex-column">
                            <div class="form-group form-floating mb-3 d-flex flex-fill">
                                <input
                                    type="text"
                                    class="form-control flex-fill"
                                    name="inputNameGroup"
                                    id="floatingNameGroup"
                                    value="{{ old('inputAmount') }}"
                                    maxlength="20"
                                    placeholder="Nom du Groupe"
                                    required
                                />
                                <label for="floatingNameGroup">Nom du Groupe</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex btn-G-L justify-content-end">
                        <button
                            class="btn btn-primary"
                            type="submit"
                            data-bs-toggle="modal"
                            data-bs-target="#modalLogin"
                            style="width: 45%"
                            onclick="return confirm('Créer ce group?');"
                        >
                            Créer ce group
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-start">
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
