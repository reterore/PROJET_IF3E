<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <style>
        body {
            background-color: #000;
            color: #fff;
        }

        .container {
            background-color: #222;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .center {
            text-align: center;
        }

        .button-container {
            margin-top: 20px;
        }

        blockquote {
            font-style: italic;
            text-align: center;
            margin-top: 30px;
        }

        footer {
            background-color: #000;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="center">
        <h1>Welcome to our website</h1>
        <p>Discover a new world of possibilities and adventures!</p>
        <div class="button-container">
            <div class="grid">
                <div>
                    <form action="create_account.php" method="get">
                        <button href="create_account.php" role="button" class="btn contrast">Create an Account</button>
                    </form>
                </div>
                <div>
                    <form action="connect_to_account.php" method="get">
                        <button href="connect_to_account.php" role="button" class="btn contrast">Log In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<blockquote>
    "Join us and embark on an intergalactic journey beyond your wildest dreams."
    <footer>
        <cite>-Elon Musk-</cite>
    </footer>
</blockquote>
</body>
<footer class="container-fluid">
    <a href="presentation.php" class="btn secondary">Learn more about our project</a>
</footer>
</html>
