@extends('layouts.home')
@section('content')
<div class="px-5">
    <div class="mt-0">
        <div class="d-flex flex-row justify-content-between mb-3">
            {{-- <div class="d-flex flex-column">
                <div>
                    <h2 class="fw-bold">Sommes des gains :</h2> 
                </div>
                <div class="d-flex flex-column">
                    @foreach ( $sommeGainsByGroups as $sommeGainsByGroup )
                        <div class="d-flex flex-row">
                            <h3 class="me-3 fw-bold">{{ $sommeGainsByGroup['nameGroup'] }}</h3>
                            <h3 class="text-info fw-bold"> {{ number_format($sommeGainsByGroup['sommeGains'], 2) }} €</h3>
                        </div>
                    @endforeach
                </div>
            </div> --}}
            <div class="d-flex flex-row">
                <h2 class="me-3">🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳🥳</h2>
            </div>
            <div>
                <button type="button" class="btn btn-light py-0 d-flex align-items-center border border-3" data-bs-toggle="modal" data-bs-target="#modalAddGain">
                    ➕ <span class="fs-3 fw-bold ms-2">de Gains</span> 
                </button>
            </div>
        </div>
        <table class="table table-bordered">
            <tr class="bg-light text-center fs-4">
                <th>Groupe</th>
                <th class="">Gain</th>
                <th>Gain individuel</th>
                <th>Date</th>
                <th>Personnes</th>
            </tr>
            @foreach ($gains as $gain)
                <tr>
                    <td>{{ $gain->nameGroup }}</td>
                    <td class="text-end align-middle fw-bold">
                        <span>{{ number_format($gain->amount, 2) }} €</span>
                    </td>
                    <td class="text-end align-middle fw-bold">
                        <span>{{ number_format($gain->gainIndividuel, 2) }} €</span>
                    </td>
                    <td class="text-end align-middle">
                        <span>{{ $gain->date }}</span>
                    </td>
                    <td class="text-end align-middle">
                        <span>{{ $gain->nbPersonnes }} personnes</span>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@include('modals.addGain')
@endsection