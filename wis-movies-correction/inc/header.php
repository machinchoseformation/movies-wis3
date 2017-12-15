<header>
    <h1><a href="index.php">Movies!</a></h1>

    <nav>
        <a href="index.php">Home</a>

    <?php 
        //si l'utilisateur est connecté...
        //littéralement: "si la clef 'user' n'est pas vide dans la variable de session"...
        if (!empty($_SESSION["user"])){
            echo '<a href="watchlist.php">Watchlist</a>';
            echo '<a href="logout.php">Logout</a>';
        }
        //si pas connecté...
        else {
            echo '<a href="register.php">Register</a>';
            echo '<a href="login.php">Login</a>';
        }
    ?>

    </nav>
</header> 