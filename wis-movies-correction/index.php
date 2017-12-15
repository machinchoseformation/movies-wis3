<?php
    //on veut utiliser la variable de session
    session_start();

    //connexion à la bdd
    include("inc/db.php");

    //récupère la liste de tous les genres pour populer la liste déroulante
    $sql = "SELECT * FROM genre 
            ORDER BY name ASC";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $genres = $stmt->fetchAll();

    
    //si on a soumis le formulaire de recherche et de filtre...
    if (!empty($_GET["genre"]) || !empty($_GET["q"])){
        //est-ce qu'on filtre par genre ?
        if (!empty($_GET["genre"])){
            $sql = "SELECT * FROM movie_simple 
                    WHERE genres LIKE :genre 
                    ORDER BY RAND() 
                    LIMIT 19";
            $stmt = $dbh->prepare($sql);
            //on ajoute des % de chaque côté du mot-clef pour pouvoir trouver le mot-clef au début ou à la fin, peu importe
            $stmt->execute([
                ":genre" => "%" . $_GET["genre"] . "%",
            ]);
        }
        //sinon, est-ce qu'on filtre par mot-clef ?
        elseif(!empty($_GET["q"])){
            //on cherche le mot-clef dans les colonnes title, actors et directors
            $sql = "SELECT * FROM movie_simple 
            WHERE title LIKE :q 
            OR actors LIKE :q 
            OR directors LIKE :q
            ORDER BY RAND() 
            LIMIT 19";
            $stmt = $dbh->prepare($sql);
            //on ajoute des % de chaque côté du mot-clef pour pouvoir trouver le mot-clef au début ou à la fin, peu importe
            $stmt->execute([
                ":q" => "%" . $_GET["q"] . "%",
            ]);
        }
    }
    //sinon, on affiche 19 films au hasard
    else {
        //sélectionne 19 films aléatoire
        $sql = "SELECT * FROM movie_simple 
        ORDER BY RAND() 
        LIMIT 19";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([]);
    } 
    
    //va chercher les résultats
    $movies = $stmt->fetchAll();

    //haut de la page HTML
    include("inc/top.php"); 
?>

    <title>Movies! | The best movies there is</title>
</head>
<body>
    <div class="container">
        <?php include("inc/header.php"); ?>

        <div id="filters_container">
            <form id="filter_form" method="GET">
                <input type="search" name="q" placeholder="Batman, Brad Pitt, ..." value="<?php if(!empty($_GET['q'])){ echo strip_tags($_GET['q']); } ?>">
                <button>OK!</button>
            </form>

            <form id="filter_form" method="GET">
                <select name="genre">
                    <option value="">Genre...</option>
                    <?php 
                    foreach($genres as $genre){
                        echo '<option name="'.$genre["name"].'"';
                        if (!empty($_GET['genre']) && $_GET['genre'] == $genre["name"]){
                            echo ' selected';
                        }
                        echo '>'.$genre["name"].'</option>';
                    } 
                    ?>                  
                </select>

                <button>OK!</button>
            </form>
        </div>

        <main class="posters">
        <?php 
            //on boucle sur le tableau de films pour les afficher un par un
            foreach($movies as $movie){
                echo '<a href="detail.php?id='.$movie['id'].'" class="poster">';
                    echo '<img src="img/posters/'.$movie['imdbId'].'.jpg">';
                    echo '<div class="poster-overlay">';
                        echo $movie["rating"];
                    echo '</div>';
                echo '</a>';
            }
        ?>
            <div id="more-movies-results"></div>
            <?php if (count($movies) >= 19){ ?>
                <a href="index.php" id="load-more">More movies!</a>
            <?php } ?>
        </main>

        <footer>
            <p>&copy; <?php echo date("Y"); ?> Movies!</p>
        </footer>
    </div>

    <script src="js/jquery.min.js"></script>
    <script>
        //quand on clique sur le bouton... 
        $("#load-more").on("click", function(e){
            //on empêche le lien de fonctionner
            e.preventDefault();
            //on fait une requête ajax à index.php
            $.get("index.php?<?php echo $_SERVER['QUERY_STRING'] ?>").done(function(result){
                //on récupère seulement les posters depuis la page
                var movies = $(result).find(".posters a.poster");
                //on ajoute ces posters à la page actuelle
                $("#more-movies-results").append(movies);
            });
        });
    </script>
</body>
</html>










