<?php
    //on veut utiliser la variable de session
    session_start();

    //on doit être connecté pour venir ici ! 
    //si la personne n'est pas connecté, on le redirige au login
    if (empty($_SESSION["user"])){
        header("Location: login.php");
        die();
    }

    //connexion à la bdd
    include("inc/db.php");

    $userId = $_SESSION["user"]["id"];

    //sélectionne 50 films aléatoire
    $sql = "SELECT * FROM movie_simple 
            JOIN watchlist ON movie_simple.id = watchlist.movieId
            WHERE watchlist.userId = :userId
            LIMIT 50";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([":userId" => $userId]);
    $movies = $stmt->fetchAll();
?>
    <?php include("inc/top.php"); ?>

    <title>My watchlist | Movies!</title>
</head>
<body>
    <div class="container">
        <?php include("inc/header.php"); ?>

        <main class="posters">
        <?php 
            //on boucle sur le tableau de films pour les afficher un par un
            foreach($movies as $movie){
                echo '<a href="detail.php?id='.$movie['id'].'">';
                    echo '<img src="img/posters/'.$movie['imdbId'].'.jpg">';
                echo '</a>';
            }

            if (count($movies) == 0){
                echo "No movies in watchlist yet!";
            }
        ?>
        </main>

        <footer>
            <p>&copy; <?php echo date("Y"); ?> Movies!</p>
        </footer>
    </div>
</body>
</html>










