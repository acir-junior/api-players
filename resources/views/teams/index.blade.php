<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Times de Futebol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">

    <div class="container-sm">
        <h1 class="text-center mb-5">Cadastro de Jogadores</h1>

        <form action="{{ route('players.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="level" class="form-label">Nível (1-5)</label>
                <input type="number" class="form-control" id="level" name="level" min="1" max="5" required>
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="is_goalkeeper" value="0">
                <input type="checkbox" class="form-check-input" id="is_goalkeeper" name="is_goalkeeper" value="1">
                <label class="form-check-label" for="is_goalkeeper">Goleiro</label>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar Jogador</button>
        </form>
        <p>HAHAHA</p>
        <div class="row col-md-12">
            <div class="g-4 row-cols-1 row-cols-md-2 row col-md-5">
                <div class="col-md-12">
                    <h1 class="text-center mb-5">Jogadores Confirmados</h1>
                </div>
                @foreach($players as $player)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h2 class="card-title h5">{{ $player->name }}</h2>
                                <p class="card-text">Nível: {{ $player->level }}</p>
                                <p class="card-text">{{ $player->is_goalkeeper ? 'Goleiro' : 'Jogador de Linha' }}</p>
                                <form action="{{ route('players.confirm', $player) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success" {{ $player->is_confirmed ? 'disabled' : '' }}>
                                        {{ $player->is_confirmed ? 'Confirmado' : 'Confirmar Presença' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="g-4 row-cols-1 row-cols-md-2 row col-md-7">
                <div class="col-md-12">
                    <h1 class="text-center">Times Formados</h1>
                </div>
                @foreach($teams as $index => $team)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h2 class="card-title h5">Time {{ $index }}</h2>
                                <ul class="list-group list-group-flush">
                                    @foreach($team as $player)
                                        <li class="list-group-item">
                                            {{ $player->name }} (Nível: {{ $player->level }}) - {{ $player->is_goalkeeper ? 'Goleiro' : 'Jogador de Linha' }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
