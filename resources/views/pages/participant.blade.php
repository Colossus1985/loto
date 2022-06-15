@extends('layouts.home')
@section('content')
<div class="mx-5 fw-bold">
    <form method="POST" action="{{ route('updateParticipant', $participant[0]->id) }}">
        @csrf
        <div class="d-flex flex-row">
            <div class="form-group form-floating mb-3 me-3 d-flex flex-fill">
                <input id="floatingfirstName" type="text" class="form-control flex-fill fw-bold" name="inputFirstName"
                    value="{{ $participant[0]->firstName }}">
                <label for="floatingfirstName" class="text-nowrap">First Name</label>
            </div>

            <div class="form-group form-floating mb-3 me-3 d-flex flex-fill">
                <input id="floatinglastName" type="text" class="form-control flex-fill fw-bold" name="inputLastName"
                    value="{{ $participant[0]->lastName }}">
                <label for="floatinglastName" class="text-nowrap">Last Name</label>
            </div>

            <div class="form-group form-floating mb-3 d-flex flex-fill">
                <input id="floatingpseudo" type="text" class="form-control flex-fill fw-bold" name="inputPseudo"
                    value="{{ $participant[0]->pseudo }}">
                <label for="floatingpseudo" class="text-nowrap">Pseudo</label>
            </div>
        </div>
        
        <div class="d-flex flex-row">
            <div class="form-group form-floating mb-3 me-3">
                <input id="floatingTel" type="text" class="form-control fw-bold" name="inputTel"
                    value="{{ $participant[0]->tel }}">
                <label for="floatingTel" class="text-nowrap">Phone</label>
            </div>
            <div class="form-group form-floating mb-3 d-flex flex-fill">
                <input id="floatingAge" type="email" class="form-control flex-fill fw-bold" name="inputEmail"
                    value="{{ $participant[0]->email }}">
                <label for="floatingAge" class="text-nowrap">Email</label>
            </div>
        </div>

        <div class="d-flex flex-row">
            <div class="form-group form-floating mb-3 me-3">
                <input id="floatingAmount" class="form-control text-end fw-bold"
                    value="{{ $participant[0]->amount }} €" readonly>
                <label for="floatingAmount" class="text-nowrap">Disponible</label>
            </div>

            <div class="form-group form-floating mb-3 me-3">
                <input id="floatingAmount" class="form-control text-end fw-bold"
                    value="{{ $participant[0]->totalAmount }} €" readonly>
                <label for="floatingAmount" class="text-nowrap">Joué</label>
            </div>
            
            <div class="form-group form-floating flex-fill d-flex mb-3">
                <button type="submit" class="btn btn-primary text-nowrap flex-fill">Enregistrer Changement</button>
            </div>
        </div>
    </form>

    <div class="d-flex flex-row justify-content-between mb-3">
        <form method="POST" action="{{ route('addMoney', $participant[0]->id) }}">
            @csrf
            <div class="d-flex flex-row">
                <div class="form-group form-floating d-flex me-3">
                    <input
                        type=text
                        id="floatingCredit"
                        class="form-control bg-success"
                        readonly
                    />
                    <label class="text-center text-nowrap fw-bold fs-3" for="floatingCredit">Credit :</label>
                </div>
                <div class="form-group form-floating d-flex me-3">
                    <input
                        type="number"
                        min="0"
                        step="0.01"
                        class="form-control flex-fill"
                        name="inputMontant"
                        id="floatingMontant"
                        value="{{ old('inputMontant') }}"
                        placeholder="+💲"
                    />
                    <label for="floatingMontant"><strong>+</strong>💲</label>
                </div>

                <div class="d-flex">
                    <button
                        class="btn btn-primary"
                        type="submit"
                        onclick="return confirm('Ajouter les fonds pour {{ $participant[0]->pseudo }} ?');"
                    >
                    ✔️
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="d-flex flex-row justify-content-between mb-3">
        <form method="POST" action="{{ route('retriveMoney', $participant[0]->id) }}">
            @csrf
            <div class="d-flex flex-row">
                <div class="form-group form-floating d-flex me-3">
                    <input
                        type=text
                        id="floatingDebit"
                        class="form-control bg-success"
                        readonly
                    />
                    <label class="text-center text-nowrap fw-bold fs-3" for="floatingDebit">Debit :</label>
                </div>
                <div class="form-group form-floating d-flex me-3">
                    <input
                        type="number"
                        min="0"
                        step="0.01"
                        class="form-control flex-fill"
                        name="inputMontant"
                        id="floatingMontant"
                        value="{{ old('inputMontant') }}"
                        placeholder="-💲"
                    />
                    <label for="floatingMontant"><strong>-</strong>💲</label>
                </div>

                <div class="d-flex">
                    <button
                        class="btn btn-primary"
                        type="submit"
                        onclick="return confirm('Retirer les fonds pour {{ $participant[0]->pseudo }} ?');"
                    >
                    ✔️
                    </button>
                </div>
            </div>
        </form>

        <form action="{{ route('participantDelete', $participant[0]->id) }}" method="Post">
            @csrf
            @method('DELETE')
            <div class="">
                <button type="submit" class="btn btn-danger"
                    onclick="return confirm('Veux tu vraiment supprimer {{ $participant[0]->pseudo }} ?');">Delete
                </button>
            </div>
        </form>
    </div>
    

    <table class="table table-bordered my-4">
        <tr class="bg-light">
            <th>Montant</th>
            <th>Credit</th>
            <th>Debit</th>
            <th>Date</th>
        </tr>
        @foreach ($actions as $action)
            <tr>
                <td class="text-end fw-bold">{{ number_format($action->amount, 2) }}</td>

                @if ( $action->credit >= 0.01 )
                    <td class="bg-success text-end fw-bold">{{ number_format($action->credit, 2) }} €</td>
                @else
                    <td class="text-end fw-bold">{{ number_format($action->credit, 2) }}</td>
                @endif

                @if ( $action->debit >= 0.01 )
                    <td class="bg-danger text-end fw-bold">{{ number_format($action->debit, 2) }} €</td>
                @else
                    <td class="text-end fw-bold">{{ number_format($action->debit, 2) }}</td>
                @endif
                <td class="fw-bold">{{ $action->created_at }}</td>
            </tr>
        @endforeach
    </table>
</div>

@endsection