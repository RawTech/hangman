$(document).ready(function(){

    $.get('./api/status', function(response){
        if (response.board.length) {
            $('div.new-game').addClass('hidden');
            $('div.game-board').removeClass('hidden');

            toast('Found a previous game. Resuming...');

            disableKeys(response.guesses);
            guessCallback(response);
        }
    });

    $('a.btn-board-key').click(function(){
        var key = $(this),
            char = key.data('char');

        key.addClass('disabled');

        setTimeout(function(){
            key.removeClass('waves-effect');
        }, 500);

        $.get('./api/guess/' + char, guessCallback);
    });

    $('#new-game-button, #start-over-button').click(function(){
        $.get('./api/new', function(response){
            $('div.new-game').addClass('hidden');
            $('div.game-board').removeClass('hidden');

            toast(response.message);

            resetBoard();
            drawBoard(response);
        });
    });

    function gameOver() {
        $('a.btn-board-key').addClass('disabled');
    }

    function resetBoard() {
        $('a.btn-board-key').removeClass('disabled');
        $('a.btn-board-tile').remove();
    }

    function drawBoard(response) {
        var cardContent = $('div.card-content.tiles'),
            wrongGuesses = response.wrongGuesses.length;

        cardContent.empty();

        if (wrongGuesses > 6) {
            wrongGuesses = 6;
        }

        $('img#hangman').prop('src', '/img/'+wrongGuesses+'.gif');

        $.each(response.board, function(key, value){
            cardContent.append('<a class="btn-large btn-board-tile disabled">' + value + '</a>');
        });
    }

    function guessCallback(response) {
        toastIfError(response);
        drawBoard(response);

        if (response.wrongGuesses.length >= response.maxMistakes) {
            gameOver();
        }
    }

    function toastIfError(response) {
        if (response.code !== 200) {
            toast(response.message);

            return true;
        }

        return false;
    }

    function toast(content, duration) {
        if (!duration) {
            duration = 4000;
        }

        Materialize.toast(content, duration);
    }

    function disableKeys(keys) {
        $.each(keys, function (key, value) {
            $('a.btn-board-key[data-char="' + value + '"]').addClass('disabled');
        });
    }

});