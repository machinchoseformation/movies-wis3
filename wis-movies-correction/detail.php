<?php 
    //on veut utiliser la variable de session
    session_start();

    //connexion à la bdd
    include("inc/db.php");

    /*
        récupérer le film à afficher
    */
    //récupère l'id du film présent dans l'url
    $id = $_GET['id'];

    //récupère le film depuis la bdd, en fonction de l'id
    $sql = "SELECT * 
            FROM movie_simple 
            WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([":id" => $id]);
    $movie = $stmt->fetch();

    //si le film n'a pas été trouvé, affiche une page d'erreur
    if (empty($movie)){
        include("inc/404.php");
        die();
    }

    /* 
        est-ce que le film est dans la watchlist de cet user ?
    */
    //par défaut, on dit que non
    $inWatchlist = false;
    if (!empty($_SESSION["user"])){
        //on cherche si ce user a déjà ajouté ce film dans la table watchlist
        $sql = "SELECT * FROM watchlist 
                WHERE movieId = :movieId AND userId = :userId";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([
            ":movieId" => $id,
            ":userId" => $_SESSION["user"]["id"],
        ]);
        if ($stmt->fetch()){
            //on a trouvé la ligne dans la table watchlist, alors c'est déjà ajouté
            $inWatchlist = true;
        }
    }

    /*
        ajout d'une critique
    */
    //si le formulaire de critique est soumis... 
    if (!empty($_POST)){
        //récupère les données du formualire
        $username = $_POST["username"];
        $title = $_POST["title"];
        $content = $_POST["content"];

        //notre requête SQL pour insérer la review
        $sql = "INSERT INTO review 
                VALUES (NULL, :movieId, :username, :title, :content, NOW())";
        $stmt = $dbh->prepare($sql);
        //on remplace les paramètres :quelquechose par la vraie valeur 
        $stmt->execute([
            ":movieId" => $id,
            ":username" => strip_tags($username),
            ":title" => strip_tags($title),
            ":content" => strip_tags($content),
        ]);
    }

    /*
        récupération des critiques de ce film
    */

    //la requête sql : 
    //on a stocké l'id du film auquel était associée la critique dans la colonne movieId
    $sql = "SELECT * FROM review 
            WHERE movieId = :id";

    //prepare la requête
    $stmt = $dbh->prepare($sql);

    //l'exécute
    $stmt->execute([":id" => $id]);

    //récupère toutes les critiques avec un fetchAll()
    $reviews = $stmt->fetchAll();
?>

    <?php include("inc/top.php"); ?>

    <title><?php echo $movie["title"] ?> | Movies!</title>
</head>
<body>
    <div class="container">
        <?php include("inc/header.php"); ?>

        <main class="detail">
            <div class="detail-left-col">
                <img src="img/posters/<?php echo $movie["imdbId"] ?>.jpg" alt="">

                <?php 
                if (!empty($_SESSION["user"])){
                    if ($inWatchlist){ ?>
                        <a href="watchlist_remove.php?id=<?php echo $movie["id"] ?>" class="btn btn-red">Remove from watchlist!</a>
                    <?php } else { ?>
                        <a href="watchlist_add.php?id=<?php echo $movie["id"] ?>" class="btn">Add to watchlist!</a>
                    <?php }
                    } 
                ?>
            </div>

            <div class="detail-right-col"> 
                <h2><?php echo $movie["title"]; ?> <span class="year">(<?php echo $movie["year"] ?>)</span></h2>
                
                <div class="rating">Rated <?php echo $movie["rating"]; ?> / 10!</div>

                <h3>Genres</h3>
                <p><?php echo $movie["genres"]; ?></p>

                <h3>Plot</h3>
                <p><?php echo $movie["plot"]; ?></p>

                <h3>Actors</h3>
                <p><?php echo $movie["actors"]; ?></p>

                <h3>Directors</h3>
                <p><?php echo $movie["directors"]; ?></p>

                <h3>Writers</h3>
                <p><?php echo $movie["writers"]; ?></p>

                <h3>Runtime</h3>
                <p><?php echo $movie["runtime"]; ?> minutes</p>

                <h3>Links</h3> 
                <ul>
                    <li><a href="http://www.imdb.com/title/<?php echo $movie["imdbId"] ?>/"><?php echo $movie["title"] ?> on IMDb</a></li> 
                </ul>
            </div>
        </main> 

        <div class="second-row">
            
            <div class="second-row-left-col">
                <h3>Trailer</h3> 
                <div class="videoWrapper">
                    <iframe src="https://www.youtube.com/embed/<?php echo $movie["trailerId"]; ?>" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
                </div>    
            </div>

            <div class="second-row-right-col">
                <div class="reviews">
                    <h3>Leave a review!</h3>
                    <form method="POST">
                        <div>
                            <label for="username">Your username:</label>
                            <input type="text" name="username" id="username" value="" placeholder="bob">
                        </div>

                        <div>
                            <label for="title">Title:</label>
                            <input type="text" name="title" id="title" value="" placeholder="my super review">
                        </div>

                        <div>
                            <label for="content">Review:</label>
                            <textarea name="content" id="content" placeholder="nice movie!"></textarea>     
                        </div>

                        <button type="submit">Send!</button>
                    </form>

                    <h3>Previous reviews</h3>
                    <?php
                        //affiche les critiques une par une 
                        foreach($reviews as $review) {
                            echo '<div>';
                                echo '<span class="review-info">On ' . $review["dateCreated"] . ", by " . $review["username"] .  '</span>';
                                echo '<p>' . $review["content"] . '</p>';
                            echo '</div>';
                        }
                        //si pas de critique...
                        if (count($reviews) == 0){
                            echo '<div>no reviews yet!</div>';
                        }
                    ?>
                </div>
            </div>
        </div>

        <footer>
            <p>&copy; <?php echo date("Y"); ?> Movies!</p>
        </footer>
    </div>
</body>
</html>