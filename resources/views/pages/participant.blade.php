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
            <div class="form-group form-floating mb-3 me-3 d-flex flex-fill">
                <input id="floatingEmail" type="email" class="form-control flex-fill fw-bold" name="inputEmail"
                    value="{{ $participant[0]->email }}">
                <label for="floatingEmail" class="text-nowrap">Email</label>
            </div>
            <div class="d-flex flex-row mb-3 ">
                <div class="form-group form-floating d-flex flex-fill me-3">
                    <input id="floatingNameGroup" type="text" class="form-control flex-fill fw-bold" name="inputNameGroupOld"
                        value="{{ $participant[0]->nameGroup }}">
                    <label for="floatingNameGroup" class="text-nowrap">Groupe</label>
                </div>
                <div class="border border-3 rounded-3 form-group form-floating d-flex flex-fill flex-column text-nowrap p-2">
                    <div class="">
                        <p class="mt-1 mb-2 ps-3">Changer le Group : </p>
                    </div>
                    @foreach ($groups as $group)
                        <div class="ms-3 form-check form-switch">
                            <input class="form-check-input me-3"
                                type="radio" 
                                name="inputNameGroupNew" 
                                role="switch" 
                                id="flexSwitchNameGroup" 
                                value="{{ $group->nameGroup }}">
                            <label class="form-check-label" for="flexSwitchNameGroup">{{ $group->nameGroup }}</label>
                        </div>
                    @endforeach
                </div>
                
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

    <table class="table table-bordered my-4">
        <tr class="bg-light text-center">
            <th>Montant</th>
            <th>Credit</th>
            <th>Debit</th>
            <th>Credit Gain</th>
            <th>Date</th>
        </tr>
        @foreach ($actions as $action)
            <tr>
                @if ($action->amount < 0)
                    <td class="text-end fw-bold bg-dark text-white">
                        {{ number_format($action->amount, 2) }} €
                    </td>
                @elseif ($action->amount == 0.00 || $action->amount == null)
                    <td class="text-end fw-bold bg-light">
                        {{ number_format($action->amount, 2) }} €
                    </td>
                @elseif ($action->amount >= 0.01 && $action->amount <= 3.49 )
                    <td class="text-end fw-bold bg-danger">
                        {{ number_format($action->amount, 2) }} €
                    </td>
                @elseif ($action->amount >= 3.50 && $action->amount <= 9.99)
                    <td class="text-end fw-bold bg-warning">
                        {{ number_format($action->amount, 2) }} €
                    </td>
                @elseif ($action->amount >= 10.00)
                    <td class="text-end fw-bold bg-success">
                        {{ number_format($action->amount, 2) }} €
                    </td>
                @endif
                
                @if ( $action->credit >= 0.01 )
                    <td class="bg-success text-end fw-bold">{{ number_format($action->credit, 2) }} €</td>
                @else
                    <td class="text-end fw-bold">{{ number_format($action->credit, 2) }} €</td>
                @endif

                @if ( $action->debit >= 0.01 )
                    <td class="bg-danger text-end fw-bold">{{ number_format($action->debit, 2) }} €</td>
                @else
                    <td class="text-end fw-bold">{{ number_format($action->debit, 2) }} €</td>
                @endif

                @if ( $action->creditGain >= 0.01 )
                    <td class="bg-success text-end fw-bold">{{ number_format($action->creditGain, 2) }} €</td>
                @else
                    <td class="text-end fw-bold">{{ number_format($action->creditGain, 2) }} €</td>
                @endif

                <td class="fw-bold text-center">{{ $action->created_at }}</td>
            </tr>
        @endforeach
    </table>

    <form action="{{ route('participantDelete', $participant[0]->id) }}" method="get">
        @csrf
        @method('DELETE')
        <div class="">
            <button type="submit" class="btn btn-danger"
                onclick="return confirm('Veux tu vraiment supprimer {{ $participant[0]->pseudo }} ?');">Supprimer {{$participant[0]->pseudo}}
            </button>
        </div>
    </form>
</div>

@endsection