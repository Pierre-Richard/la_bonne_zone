<?php
session_start();
 

$bdd = new PDO ('mysql:host=localhost; dbname=la_bonne_zone', 'root', 'root');

/* Le isset me permet de savoir si une personne est connecteé ou non */
/* id_utilisateur proviens de ma base de donnée */
if(isset($_SESSION['id_utilisateur']))
{/* Me selectionne un utiisateur par son id_utilisateur*/
    $requser = $bdd->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
    $requser->execute(array($_SESSION['id_utilisateur']));
    $user = $requser->fetch();
    /* != veut dire différent ou n'est pass egal */
    if(isset($_POST['newnom']) AND !empty($_POST['newnom']) AND $_POST['newnom'] != $user['nom'])
        /* htmlentities sécurise mon input des injection sql et des trucs du genre */
    {   $newnom = htmlentities($_POST['newnom']);
        $insertnom = $bdd->prepare("UPDATE utilisateurs SET nom = ? WHERE id_utilisateur = ?");
        $insertnom->execute(array($newnom,$_SESSION['id_utilisateur']));
        header('Location: profil.php?id_utilisateur='.$_SESSION['id_utilisateur']);
    }
    if(isset($_POST['newprenom']) AND !empty($_POST['newprenom']) AND $_POST['newprenom'] != $user['prenom'])
    {
        $newprenom = htmlentities($_POST['newprenom']);
        $insertprenom = $bdd->prepare("UPDATE utilisateurs SET prenom = ? WHERE id_utilisateur = ?");
        $insertprenom->execute(array($newprenom, $_SESSION['id_utilisateur']));
        header('Location: profil.php?id_utilisateur='.$_SESSION['id_utilisateur']);
    }
    if(isset($_POST['newmail']) AND !empty($_POST['newmail']) AND $_POST['newmail'] != $user['mail'])
    /* htmlentities sécurise mon input des injection sql et des trucs du genre */
    {   $newmail = htmlentities($_POST['newmail']);
        $insertmail = $bdd->prepare("UPDATE utilisateurs SET mail = ? WHERE id_utilisateur = ?");
        $insertmail->execute(array($newmail,$_SESSION['id_utilisateur']));
        header('Location: profil.php?id_utilisateur='.$_SESSION['id_utilisateur']);
    }
    if(isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2']))
        
    {   
        $mdp1 = sha1($_POST['newmdp1']);
        $mdp2 = sha1($_POST['newmdp2']);
        if($mdp1 == $mdp2)
        {
            $insertmdp = $bdd->prepare("UPDATE utilisateurs set mdp = ? WHERE id_utilisateur =?");
            $insertmdp->execute(array($mdp1,$_SESSION['id_utilisateur']));
            header('Location: profil.php?id_utilisateur='.$_SESSION['id_utilisateur']);
        }
        else
        {
            $msg = "Vos deux mdp ne correspondent pas !";       
        }       
    }
    if(isset($_POST['newtelephone']) AND !empty($_POST['newtelephone']) AND $_POST['newtelephone'] != $user['telephone'])
    {
        $newtelephone = $_POST['newtelephone'];
        $insertTelephone = $bdd->prepare("UPDATE utilisateurs SET telephone = ? WHERE id_utilisateur = ?");
        $insertTelephone->execute(array($newtelephone, $_SESSION['id_utilisateur']));
        header('Location: profil.php?id_utilisateur='.$_SESSION['id_utilisateur']);
    }
    if(isset($_FILES['avatar']) AND !empty($_FILES['avatar']['name']))
    {
        $tailleMax = 2097152;
        $extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
        if($_FILES['avatar']['size'] <= $tailleMax)
        {
            $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
            if(in_array($extensionUpload, $extensionsValides))
            {
                $chemin = "utilisateurs/avatars/".$_SESSION['id_utilisateur'].".".$extensionUpload;
                /* tmp_name est l endroit ou se trouve le fichier */
                $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
                if($resultat)
                {
                    $updateAvatar = $bdd->prepare('UPDATE utilisateurs SET avatar = :avatar WHERE id_utilisateur = :id_utilisateur');
                    $updateAvatar->execute(array( 
                        'avatar' => $_SESSION['id_utilisateur'].".".$extensionUpload,
                        'id_utilisateur' => $_SESSION['id_utilisateur']                    
                    ));
                    header('Location: profil.php?id_utilisateur='.$_SESSION['id_utilisateur']);
                }
                else
                {
                    $msg = "Erreur durant l'importation de votre photo de profil";
                }
            }
            else
            {
                $msg = "Votre photo de profil doit être au format jpg, jpeg, gif ou png";            }
        }
        else
        {
            $msg = "Votre photo de profil ne doit pas dépasser 2Mo";
        }
    }
    
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
        <h2>Edition de mon profil</h2>
        <!-- enctype me permet de lire correctement mon input file pour pouvoir telecharger mes images -->
        <form method="POST" action=""   enctype="multipart/form-data">
            <table>
                <tr>
                    <td align="right">
                        <label>Nom: </label>
                    </td>
                    <td>
                        <input type="text" name="newnom" placeholder="Nom" value="<?php echo $user['nom']; ?>"><br><br>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label>Prenom: </label>
                    </td>
                    <td>
                        <input type="text" name="newprenom" placeholder="Prénom" value="<?php echo $user['prenom']; ?>"><br><br>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label>Mail: </label>
                    </td>
                    <td>
                        <input type="text" name="newmail" placeholder="Mail" value="<?php echo $user['mail']; ?>"><br><br>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label>Mot de passe: </label>
                    </td>
                    <td>
                        <input type="password" name="newmdp1" placeholder="Mot de passe "><br><br>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label>Confirmation - mot de passe: </label>
                    </td>
                    <td>
                        <input type="password" name="newmdp2" placeholder="Confrmation du mot de passe"><br><br>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label>Telephone: </label>
                    </td>
                    <td>
                        <input type="text" name="newtelephone" placeholder="newtelephone" value="<?php echo $user['telephone']; ?>"><br><br>
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label>Avatar: </label>
                    </td>
                    <td>
                        <input type="file" name="avatar"><br><br>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <input type="submit" value="Mettre à jour mon profil""><br><br>
                    </td>
                </tr>
                
            </table>
        </form> 
        <?php
        if(isset($msg)){ echo '<font color="red">'.$msg."</font>";}
        
        ?>      
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>    
</body>
</html>
<?php
}
else
{
    header("Location : connexion.php");
}
?>