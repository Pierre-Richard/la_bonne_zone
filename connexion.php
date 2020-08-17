<?php
session_start();


$bdd = new PDO ('mysql:host=localhost; dbname=la_bonne_zone', 'root', 'root');
if(isset($_POST['formconnexion']))
{
    $mailconnect = htmlentities($_POST['mailconnect']);
    $mdpconnect = sha1($_POST['mdpconnect']);
    if(!empty($mailconnect) AND !empty($mdpconnect))
    {
        $requser = $bdd->prepare("SELECT * FROM utilisateurs WHERE mail = ? AND mdp = ?");
        $requser->execute(array($mailconnect, $mdpconnect));  
        $userexist =  $requser->rowCount();
        if($userexist == 1)
        {
            $userinfo = $requser->fetch();
            $_SESSION["id_utilisateur"] = $userinfo['id_utilisateur'];
            $_SESSION['nom'] = $userinfo['nom'];
            $_SESSION['mail'] = $userinfo['mail'];
            header("Location: profil.php?id_utilisateur =".$_SESSION['id_utilisateur']);
        }
        else
        {
            $erreur = "Mauvais mail ou mot de passe";
        }
    }
    else
    {
        $erreur = "Tous les champs doivent etre complétés!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Connexion</title>
</head>
<body>
   
    <div align="center">
        <h2>Connexion</h2>
        <br>
        <form method="POST" action="">
            <input type="email" name="mailconnect" placeholder="Mail">
            <input type="password" name="mdpconnect" placeholder="Mot de Passe">
            <input type="submit" name="formconnexion" value="Se connecter !">
        </form>
        <?php
            if(isset($erreur)){
                echo '<font color="red">'.$erreur."</font>";
            }
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>    
</body>
</html>