<?php 
    //on va utiliser la variable $_SESSION...
    session_start();

    //on enlève tout ce qu'on a pu stocker sous "user"
    unset($_SESSION["user"]);

    //on dégage l'utilisateur ailleurs
    header("Location: index.php");