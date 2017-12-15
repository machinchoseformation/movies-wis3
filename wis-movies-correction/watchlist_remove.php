<?php 
/*
    Cette page n'affiche pas de HTML, car elle redirige les utiliteurs à tous les coups vers une autre page.
    Ce n'est qu'un script sur lequel on rebondit. 
*/

//on utilise la variable de session plus bas...
session_start();

//connexion à la bdd
include("inc/db.php");

//récupère l'id du film à ajouter dans l'URL
$movieId = $_GET['id'];
//récupère l'id de l'utilisateur dans la session
$userId = $_SESSION["user"]["id"];

//supprime cette relation dans la table de watchlist
$sql = "DELETE FROM watchlist WHERE userId = :userId AND movieId = :movieId";
$stmt = $dbh->prepare($sql);
$stmt->execute([
    ":userId" => $userId,
    ":movieId" => $movieId,
]);

//on redirige vers la page de détail du film
header("Location: detail.php?id=" . $movieId);