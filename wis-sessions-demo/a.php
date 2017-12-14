<?php 
//les sessions : 
//permettent de garder des données en mémoire sur le serveur

session_start();

$_SESSION["views"] = $_SESSION["views"] + 1;

echo $_SESSION["views"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sessions</title>
</head>
<body>
    <h1>A</h1>
    <ul>
        <li><a href="a.php">A</a></li>
        <li><a href="b.php">B</a></li>
        <li><a href="c.php">C : page sécurisée</a></li>
        <li><a href="d.php">D : supprime les données en session</a></li>
    </ul>
</body>
</html>