<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
</head>
<body>
    @include('layouts.navbar')
    <div class="mx-5 my-2">
        @if (session('success'))
            <p class="alert alert-success mt-3">{{ session('success') }}</p>
        @endif
        @if (session('error'))
            <p class="alert alert-danger mt-3">{{ session('error') }}</p>
        @endif
    </div>
    @include('modals.addAddParticipant')
    @include('modals.jouer')
    
    @yield('content')
    
    @include('layouts.footer')
    <script src="/js/addGain.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>