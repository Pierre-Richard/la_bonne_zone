<?php
session_start();
 

$bdd = new PDO ('mysql:host=localhost; dbname=la_bonne_zone', 'root', 'root');

if(isset($_GET['id_utilisateur']) AND $_GET['id_utilisateur'] > 0)
{
    $getid = intval($_GET['id_utilisateur']);
    $requser = $bdd->prepare('SELECT * FROM utilisateurs WHERE id_utilisateur = ?');
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Profil</title>
</head>
<body>
   
    <div align="center">
        <h2>Profil de <?php echo $userinfo['nom']; ?></h2>
        <br>
        Nom = <?php echo $userinfo['nom']; ?>
        <br>
        Prenom = <?php echo $userinfo['prenom']; ?>
        <br>
        Mail = <?php echo $userinfo['mail']; ?>
        <br>
        Telephone = <?php echo $userinfo['telephone']; ?>
        <br>
        <?php
            if(isset($_SESSION['id_utilisateur']) AND $userinfo['id_utilisateur'] == $_SESSION['id_utilisateur'])
            {
        ?>
        <a href="editionprofil.php">Editer mon profil</a>
        <a href="deconnexion.php">Deconnexion</a>
        <?php
         }
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>    
</body>
</html>
<?php
}
?>