<?php 
include("db.php");

//traiter le form
        //récupérer les données
       // print_r($_POST);

        $name = $_POST["nom"];
        $date = $_POST["date"];
        $persons = $_POST["persons"];

        $error = "";

        //valide les données
            //nom renseigné ?
        if (empty($name)){
            $error = "veuillez renseigner votre nom dude.";
        }
            
            //date dans le futur ? 
        if ($date < date("Y-m-d")){
            $error = "blabla.";
        }
        
            //téléphone ?
            //nb de personne ?

        //si les données sont valides
        if ($error == ""){
            //ajout dans la bdd
            /*
            INSERT INTO booking 
            VALUES 
            (NULL, 'guillaume', '2018-01-01', 12, '0634444444', 'près de la fenêtre svp', NOW())
            */
            $sql = "INSERT INTO booking 
                    VALUES (NULL, :name, :wantedDate, 
                    :persons, :phone, :note, NOW())";

            $stmt = $dbh->prepare($sql);
            $stmt->execute([
                ":name" => $name,
                ":wantedDate" => $date, 
                ":persons" =>$persons,
                ":phone" => "pifpaf",
                ":note" => "coucou",
            ]);

            //afficher un message de succès
            //redirige
            header("Location: https://lingscars.com");
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Réservation</title>
</head>
<body>
    <!-- afficher le formulaire --> 
    <form method="post">

        <label>Votre nom</label>
        <input type="nom" name="nom">

        <label>date</label>
        <input type="date" name="date">

        <input type="number" name="persons">

        <div><?php echo $error; ?></div>
        <button>Envoyer ma réservation</button>
    </form>
</body>
</html>