<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <base href="http://localhost:8888/test/search/">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Search Field</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="flex">
        <div class="search">
            <form action="" method="post" id="searchForm">
                <input type="text" name="search" placeholder="Search">
                <button type="submit" name="submit-search">Search</button>
            </form>
        </div>
    </div>
    <div class="header">
        <h2>Search Results</h2>
    </div>
    <div class="flex mt-0">
        <div class="output">

        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        //#searchForm
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            //get value of input
            let search = $('input[name="search"]').val();
            //ajax request
            $.ajax({
                url: 'process/ajax.php',
                type: 'POST',
                data: {
                    search: search
                },
                success: function(response) {
                    $('.output').html(response);
                }
            });
        });

        //check if user is done typing
        let typingTimer;
        let doneTypingInterval = 1000; //2 seconds
        $('input[name="search"]').on('keyup', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });

        //user is typing
        $('input[name="search"]').on('keydown', function() {
            clearTimeout(typingTimer);
        });

        //user is done typing
        function doneTyping() {
            //get value of input
            let search = $('input[name="search"]').val();
            //ajax request
            $.ajax({
                url: 'process/ajax.php',
                type: 'POST',
                data: {
                    search: search
                },
                success: function(response) {
                    $('.output').html(response);
                }
            });
        }
    </script>
</body>

</html>