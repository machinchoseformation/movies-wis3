<?php 
    session_start();

    //connexion à la bdd
    include("inc/db.php");

    //on initialise notre variable d'erreur à vide dans tous les cas
    $error = "";

    //si le formulaire est soumis... 
    if (!empty($_POST)){

        //on récupère les données du formulaire dans des variables
        $username = $_POST["username"];
        $password = $_POST["password"];        
        
        //si le pseudo n,est pas fourni
        if (empty($username)){
            //on change le message d'erreur
            $error = "Please pick a username!";
        }

        //on vérifie si le username existe en bdd
        $sql = "SELECT * FROM users 
                WHERE username = :username";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([":username" => $username]);
        $user = $stmt->fetch();

        //si on n'a pas trouvé un résultat, c'est que le pseudo n'est pas inscrit
        if (!$user){
            $error = "Oops! wrong credentials!";
        }

        //password vide ?
        if (empty($password)){
            $error = "Please provide your password!";
        }

        //est-ce que le mot de passe envoyé dans le formulaire est le même que celui stocké en bdd ?
        if ($password == $user["password"]){
            //si oui, c'est que tout est ok ! 

            //on connecte l'utilisateur
            $_SESSION["user"] = $user;

            //on le redirige à la page d'accueil
            header("Location: index.php");
        }
        else {
            $error = "Wrong credentials!";
        }
    }

?>
    <?php include("inc/top.php"); ?>

    <title>Login! | Movies!</title>
</head>
<body>
    <div class="container">
        <?php include("inc/header.php"); ?>

        <main>
            <h2>Login!</h2>
            <form method="POST">
                <div>
                    <label for="username">Your username:</label>
                    <input type="text" name="username" id="username" value="" placeholder="bob">
                </div>

                <div>
                    <label for="password">Your password:</label>
                    <input type="password" name="password" id="password">
                </div>

                <div class="error">
                    <?php echo $error; ?>
                </div>
                <button type="submit">Login!</button>
            </form>
        </main>
        
        <footer>
            <p>&copy; <?php echo date("Y"); ?> Movies!</p>
        </footer>
    </div>
</body>
</html>