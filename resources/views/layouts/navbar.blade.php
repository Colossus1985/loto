<nav class="navbar navbar-expand-lg bg-info px-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropUsers" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Participants
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropUsers">
                    <li>
                        <button type="button" class="btn mt-2 dropdown-item" data-bs-toggle="modal"
                            data-bs-target="#modalAddParticipant">
                            Ajouter
                        </button>
                    </li>
                    <li>
                        <button type="button" class="btn mt-2 dropdown-item" data-bs-toggle="modal"
                            data-bs-target="#modalJouer">
                            Jouer
                        </button>
                    </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropGains" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Gains
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropGains">
                        <li><a class="dropdown-item" href="{{ route('getGainHistory') }}">Gains</a></li>
                        <li>
                            <button type="button" class="btn mt-2 dropdown-item" data-bs-toggle="modal"
                                data-bs-target="#modalAddGain">
                                Ajouter Gain
                            </button>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropUsers" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Groupes
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropUsers">
                    <li>
                        <button type="button" class="btn mt-2 dropdown-item" data-bs-toggle="modal"
                            data-bs-target="#modalAddGroup">
                            Créer Groupe
                        </button>
                    </li>
                    <li>
                        <button type="button" class="btn mt-2 dropdown-item" data-bs-toggle="modal"
                            data-bs-target="#modalParticipantGroup">
                            Gérer Groupes
                        </button>
                    </li>
                    </ul>
                </li>
            </ul>

            <form class="me-5" action="{{ route('searchParticipant') }}" method="GET">
                @csrf
                <div class="input-group ">
                    <input class="form-control me-1 mt-2" maxlength="15" name="inputParticipant" placeholder="Participant..."
                        aria-label="Participant...">
                    <button class="btn btn-outline-success ms-1 mt-2" type="submit">🔎</button>
                </div>
            </form>
        </div>
    </div>
    @include('modals.addGain')
</nav>