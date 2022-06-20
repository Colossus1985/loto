@extends('layouts.home')
@section('content')
<div class="px-5">
    <div class="mt-0">
        <div class="d-flex flex-row justify-content-between">
            <div class="d-flex flex-row">
                <h2 class="me-3">Fonds disponible :</h2>
                <h2 class="text-info"> {{ number_format($fonds, 2) }} €</h2>
            </div>
            <div class="d-flex flex-row">
                <h2 class="me-3">Gain engendré(s) :</h2>
                <form action="{{ route('getGainHistory') }}">
                    <button tupe="submit" class="btn btn-light py-0 mb-3">
                        <h2 class="text-info m-0 p-0"> {{ $sommeGain }} €</h2>
                    </button>
                </form>
            </div>
        </div>
        <table class="table table-bordered">
            <tr class="bg-light text-center">
                <th>Pseudo</th>
                <th>Disponible</th>
                <th>Joué</th>
            </tr>
            @foreach ($participants as $participant)
                <tr>
                    <td>
                        <form action="{{ route('participant', $participant->id) }}" method="get">
                            <input type="submit" class="form-control me-2 btn btnhover"
                                name="inputDetailUser" value="{{ $participant->pseudo }}" readonly>
                        </form>
                    </td>
                    {{-- <button type="button" class="btn btn-light me-3" data-bs-toggle="modal" data-bs-target="#bookAutor">
                        Create a Autor</button> --}}
                    @if ( $participant->amount == null || $participant->amount == 0)
                        <td class="bg-danger text-end pe-3 align-middle d-flex align-items-center justify-content-between">
                            <div class="d-flex flex-row">
                                {{-- <a class="btn bg-light me-3" href="{{ route('participant', $participant->id) }}" role="button">
                                    ➕
                                </a>
                                <a class="btn bg-light" href="{{ route('participant', $participant->id) }}" role="button">
                                    ➖
                                </a> --}}
                                <button type="button" class="btn btn-light me-3" data-bs-toggle="modal" data-bs-target="#modalAddMoney{{$participant->id}}">
                                    ➕</button>
                                <button type="button" class="btn btn-light me-3" data-bs-toggle="modal" data-bs-target="#modalDebitMoney{{$participant->id}}">
                                    ➖</button>
                            </div>
                            0.00 €
                        </td>
                    @elseif ( $participant->amount <= 2.49)
                        <td class="bg-danger text-end pe-3 align-middle d-flex align-items-center justify-content-between">
                            <div class="d-flex flex-row">
                                {{-- <a class="btn bg-light me-3" href="{{ route('participant', $participant->id) }}" role="button">
                                    ➕
                                </a>
                                <a class="btn bg-light" href="{{ route('participant', $participant->id) }}" role="button">
                                    ➖
                                </a> --}}
                                <button type="button" class="btn btn-light me-3" data-bs-toggle="modal" data-bs-target="#modalAddMoney{{$participant->id}}">
                                    ➕</button>
                                <button type="button" class="btn btn-light me-3" data-bs-toggle="modal" data-bs-target="#modalDebitMoney{{$participant->id}}">
                                    ➖</button>
                            </div>
                            {{ number_format($participant->amount, 2) }} €
                        </td>
                    @elseif ( $participant->amount < 10 && $participant->amount >= 2.5)
                        <td class="bg-warning text-end pe-3 align-middle d-flex align-items-center justify-content-between">
                            <div class="d-flex flex-row">
                                {{-- <a class="btn bg-light me-3" href="{{ route('participant', $participant->id) }}" role="button">
                                    ➕
                                </a>
                                <a class="btn bg-light" href="{{ route('participant', $participant->id) }}" role="button">
                                    ➖
                                </a> --}}
                                <button type="button" class="btn btn-light me-3" data-bs-toggle="modal" data-bs-target="#modalAddMoney{{$participant->id}}">
                                    ➕</button>
                                <button type="button" class="btn btn-light me-3" data-bs-toggle="modal" data-bs-target="#modalDebitMoney{{$participant->id}}">
                                    ➖</button>
                            </div>
                            {{ number_format($participant->amount, 2) }} €
                        </td>
                    @elseif ( $participant->amount >= 10)
                        <td class="bg-success text-end pe-3 align-middle d-flex align-items-center justify-content-between">
                            <div class="d-flex flex-row">
                                {{-- <a class="btn bg-light me-3" href="{{ route('participant', $participant->id) }}" role="button">
                                    ➕
                                </a>
                                <a class="btn bg-light" href="{{ route('participant', $participant->id) }}" role="button">
                                    ➖
                                </a> --}}
                                <button type="button" class="btn btn-light me-3" data-bs-toggle="modal" data-bs-target="#modalAddMoney{{$participant->id}}">
                                    ➕</button>
                                <button type="button" class="btn btn-light me-3" data-bs-toggle="modal" data-bs-target="#modalDebitMoney{{$participant->id}}">
                                    ➖</button>
                            </div>
                            {{ number_format($participant->amount, 2) }} €
                        </td>
                    @endif
                    <td class="align-middle text-end">{{ number_format($participant->totalAmount, 2) }} €
                </tr>
                @include('modals.addMoney')
                @include('modals.debitMoney')
            @endforeach
        </table>
    </div>
</div>
@endsection