<?php //dans wamp64/www/movies

    //connexion à la bdd
    include("inc/db.php");

    //on initialise notre variable d'erreur à vide dans tous les cas
    $error = "";

    //si le formulaire est soumis... 
    if (!empty($_POST)){

        //on récupère les données du formulaire dans des variables
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];        
        $passwordBis = $_POST["password_bis"];
        
        //si le pseudo n,est pas fourni
        if (empty($username)){
            //on change le message d'erreur
            $error = "Please pick a username!";
        }

        //on vérifie si le username existe déjà en bdd
        $sql = "SELECT * FROM users 
                WHERE username = :username";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([":username" => $username]);
        $user = $stmt->fetch();

        //si on a trouvé un résultat, c'est que le pseudo est déjà pris
        if ($user){
            $error = "Oops! Username already taken!";
        }

        //email vide ?
        if (empty($email)){
            $error = "Please provide your email!";
        }

        //email valide ?
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $error = "Your email is invalid!";
        }

        //on vérifie si l'email existe déjà en bdd
        $sql = "SELECT * FROM users 
                WHERE email = :email";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([":email" => $email]);
        $user = $stmt->fetch();

        //utilisateur trouvé ? ça veut dire que l'email est déjà inscrit ici
        if ($user){
            $error = "Oops! This email is already registered!";
        }

        //password vide ?
        if (empty($password)){
            $error = "Please choose a password!";
        }

        //confirmation du mot de passe vide ?
        if (empty($passwordBis)){
            $error = "Please confirm your password!";
        }

        //les mots de passe concordent ?
        if ($password != $passwordBis){
            $error = "Your password do not match!";
        }

        //si tout le formulaire est valide... (on n'a pas d'erreur)
        if (empty($error)){
            //on insère l'utilisateur en bdd
            $sql = "INSERT INTO users 
                    VALUES (NULL, :username, :email, :password, NOW())";
            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                ":username" => $username,
                ":email" => $email,
                ":password" => $password
            ]);
        }
    }

?>
    <?php include("inc/top.php"); ?>

    <title>Create an account ! | Movies!</title>
</head>
<body>
    <div class="container">
        <?php include("inc/header.php"); ?>

        <main>
            <h2>Create an account!</h2>
            <form method="POST">
                <div>
                    <label for="username">Your username:</label>
                    <input type="text" name="username" id="username" value="" placeholder="bob">
                </div>

                <div>
                    <label for="email">Your email:</label>
                    <input type="email" name="email" id="email" value="" placeholder="yo@yo.com">
                </div>

                <div>
                    <label for="password">Your password:</label>
                    <input type="password" name="password" id="password">
                </div>

                <div>
                    <label for="password_bis">Your password again:</label>
                    <input type="password" name="password_bis" id="password_bis">
                </div>

                <div class="error">
                    <?php echo $error; ?>
                </div>
                <button type="submit">Go!</button>
            </form>
        </main>
        
        <footer>
            <p>&copy; <?php echo date("Y"); ?> Movies!</p>
        </footer>
    </div>
</body>
</html>