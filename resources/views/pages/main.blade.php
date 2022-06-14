@extends('layouts.home')
@section('content')
<div class="px-5">
    <div class="mt-0">
        <div class="row pt-4 mb-4">
            {{-- <div class="d-flex flex-row">
                <div>
                    <a class="btn btn-primary me-3" href="{{ route('home') }}" enctype="multipart/form-data">Back</a>
                </div>
                <div>
                    <form class="d-flex me-5" action="{{ route('searchUser') }}" method="GET">
                        @csrf
                        <div class="input-group">
                            <input class="form-control me-2" name="inputSearchUser" placeholder="Search User..."
                                aria-label="Search User...">
                            <button class="btn btn-outline-success ms-2" type="submit">🔎</button>
                        </div>
                    </form>
                </div>
            </div> --}}
        </div>
        <table class="table table-bordered">
            <tr class="bg-light">
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
                    @if ( $participant->amount == null || $participant->amount == 0)
                        <td class="bg-danger text-end pe-3 align-middle d-flex align-items-center justify-content-between">
                            <a class="btn bg-light" href="{{ route('participant', $participant->id) }}" role="button">
                                ➕
                            </a>
                            0 €
                        </td>
                    @elseif ( $participant->amount <= 2.49)
                        <td class="bg-danger text-end pe-3 align-middle d-flex align-items-center justify-content-between">
                            <a class="btn bg-light" href="{{ route('participant', $participant->id) }}" role="button">
                                ➕
                            </a>
                            {{ $participant->amount }} €
                        </td>
                    @elseif ( $participant->amount < 10 && $participant->amount >= 2.5)
                        <td class="bg-warning text-end pe-3 align-middle d-flex align-items-center justify-content-between">
                            <a class="btn bg-light" href="{{ route('participant', $participant->id) }}" role="button">
                                ➕
                            </a>
                            {{ $participant->amount }} €
                        </td>
                    @elseif ( $participant->amount > 10)
                        <td class="bg-success text-end pe-3 align-middle d-flex align-items-center justify-content-between">
                            <a class="btn bg-light" href="{{ route('participant', $participant->id) }}" role="button">
                                ➕
                            </a>
                            {{ $participant->amount }} €
                        </td>
                    @endif
                    <td class="align-middle text-end">{{ $participant->totalAmount }} €
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection