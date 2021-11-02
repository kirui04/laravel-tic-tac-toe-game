@extends('layouts.app')
@section('content')

    <div class="text-center font-italic f-size-1-5 mb-3">
        {{ $game->first_player_name }} | {{ $game->second_player_name }}
    </div>

    @if ($errors->any())

        <div class="alert alert-danger" role="alert">
            <p class="mb-0">{{ __('Errors') }}</p>
            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>
        </div>

    @endif

    @if (empty($prepareData['gameHistories']))
        <div class="text-center">
            {{ __('Start the game') }}
        </div>
    @endif

    @if ($isFullGameField || $prepareData['gameOver'])

        <div class="text-center">
            {{ __('The game is over') }}
        </div>

        @if ($prepareData['playerWinner'])
            <div class="text-center f-size-2">
                {{ __('Winner') }}  "{{ strtoupper($game::getPlayerTypes($prepareData['playerWinner'])) }}"
            </div>
        @else
            <div class="text-center f-size-2">
                {{ __('A draw') }}
            </div>
        @endif

    @else

        @if ($prepareData['playerType'] && $prepareData['playerType'] === $firstPlayerType)
            <div class="d-flex align-items-center justify-content-center">
                <div>{{ __('To walk') }}&nbsp;</div>
                <div class="mb-2 f-size-2">{{ $game::getPlayerTypes($secondPlayerType) }}</div>
            </div>
        @endif

        @if ($prepareData['playerType'] && $prepareData['playerType'] === $secondPlayerType)
            <div class="d-flex align-items-center justify-content-center">
                <div>{{ __('To walk') }}&nbsp;</div>
                <div class="mb-2 f-size-2">{{ $game::getPlayerTypes($firstPlayerType) }}</div>
            </div>
        @endif

    @endif

    <div class="ttt-content mt-3">
    <style>
    @media (max-width: 768px) {
        #hidemobile {
            display: none;
        }
    }
</style>
        <div class="row">
            <div id="hidemobile" class="col-md-4 col-sm-12" style="background-color: ;">
                RESULTS OF LAST FIVE MATCHES 
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
                    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.css">

                    <div class="container">
                    <table id="table" data-toggle="table" data-flat="true" data-url="http://localhost:8000/games/stats">
                        <thead>
                        <tr>
                            <th data-field="game_id" data-sortable="true">Game id</th>
                            <th data-field="winner" data-sortable="true">Winner</th>
                        </tr>
                        </thead>
                    </table>
                    </div>

                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
                    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
                    <script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.js"></script> 
            </div>
            <div class="col-md-8 col-sm-12" style="background-color: ;">
                @for ($row = 1; $row <= $gameSize; $row++)
                    <div class="d-flex justify-content-center ttt-row">

                        @for ($col = 1; $col <= $gameSize; $col++)
                            <div class="align-self-center ttt-col">

                                @if (isset($prepareData['gameHistories'][$row][$col]))

                                    <div class="ttt-element">

                                        @if ($prepareData['horizontalSuccess'][$row] ?? null)
                                            <div class="line"></div>

                                        @elseif($prepareData['verticalSuccess'][$col] ?? null)
                                            <div class="line rotate-90"></div>

                                        @elseif ($prepareData['diagonalRightSuccess'][$row][$col] ?? null)
                                            <div class="line rotate-135"></div>

                                        @elseif ($prepareData['diagonalLeftSuccess'][$row][$col] ?? null)
                                            <div class="line rotate-45"></div>
                                        @endif

                                        {{  $game::getPlayerTypes($prepareData['gameHistories'][$row][$col]) }}

                                    </div>

                                @else

                                    <div class="ttt-element">

                                        @if (! $prepareData['gameOver'])

                                            {!! Form::open(['route' => 'gameHistories.store', 'method' => 'post']) !!}
                                            {!! Form::hidden('game_id', $game->id) !!}
                                            {!! Form::hidden('game_round_id', $round) !!}
                                            {!! Form::hidden('game_row', $row) !!}
                                            {!! Form::hidden('game_column', $col) !!}

                                            @if (! $prepareData['playerType'])
                                                {!! Form::hidden('player_type', $firstPlayerType) !!}
                                            @endif

                                            @if ($prepareData['playerType'] && $prepareData['playerType'] === $firstPlayerType)
                                                {!! Form::hidden('player_type', $secondPlayerType) !!}
                                            @endif

                                            @if ($prepareData['playerType'] && $prepareData['playerType'] === $secondPlayerType)
                                                {!! Form::hidden('player_type', $firstPlayerType) !!}
                                            @endif

                                            {!! Form::submit('', ['class' => 'btn btn-link btn-block']) !!}
                                            {!! Form::close() !!}

                                        @endif

                                    </div>

                                @endif

                            </div>
                        @endfor

                    </div>
                @endfor
            </div>
        </div>

    </div>

    <hr>

    <div class="row">
        <div class="col-sm text-center pb-3">

            {!! Form::open(['route' => 'gameRounds.store', 'method' => 'post']) !!}
            {!! Form::hidden('game_id', $game->id) !!}
            {!! Form::submit(__('Play again'), ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}

        </div>
        <div class="col-sm text-center pb-3">

            {{ link_to_route('games.create', __('New game'), [], ['class' => 'btn btn-success']) }}

        </div>
        <div class="col-sm text-center pb-3">

            {{ link_to_route('games.index', __('Statistics'), [], ['class' => 'btn btn-info']) }}

        </div>
    </div>

@endsection
