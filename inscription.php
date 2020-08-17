<?php 


$bdd = new PDO ('mysql:host=localhost; dbname=la_bonne_zone', 'root', 'root');

    if(isset($_POST["forminscription"]))
    {
        $nom = htmlentities($_POST["nom"]);
        $nom = strtoupper($nom);
        $prenom = htmlentities($_POST["prenom"]);
        $date_de_naissance = htmlentities($_POST["date_de_naissance"]);
        $mail = htmlentities($_POST["mail"]);
        $mail2 = htmlentities($_POST["mail2"]);
        $mdp = sha1($_POST["mdp"]);
        $mdp2 = sha1($_POST["mdp2"]);
        $telephone = $_POST["telephone"];

        if(!empty($_POST["nom"]) AND !empty($_POST["prenom"]) AND !empty($_POST["date_de_naissance"]) AND !empty($_POST["mail"]) AND !empty($_POST["mail2"]) AND !empty($_POST["mdp"]) AND !empty($_POST["mdp2"]) AND !empty($_POST["telephone"]))
        {   
           
            $nomlenght = strlen($nom);
            if($nomlenght <= 60)
            {
                $prenomlenght = strlen($prenom);
                if($prenomlenght <= 60)
                {
                    if($mail == $mail2)
                    {
                        if(filter_var($mail, FILTER_VALIDATE_EMAIL))
                        {
                            $reqmail = $bdd->prepare('SELECT * FROM utilisateurs WHERE mail =?');
                            $reqmail->execute(array($mail));
                            $mailexist = $reqmail->rowCount();
                            if($mailexist == 0)
                            {
                                if($mdp == $mdp2)
                                {
                                    $insertutilisateurs = $bdd->prepare("INSERT INTO utilisateurs (nom, prenom, date_de_naissance, mail, mdp, telephone) values (?,?,?,?,?,?)");
                                    $insertutilisateurs->execute(array($nom, $prenom,$date_de_naissance, $mail, $mdp, $telephone));
                                    $erreur = "votre compte a bien été crée !< a href=\"connexion.php\">Me connecter</a>";

                                    header('Location: connexion.php');
                                }else{
                                    $erreur = "Vos mots de passes ne correspondent pas!";
                                }
                            }else{
                                $erreur = "Adresse mail déjà utilisée!";
                            }
 
                        }else{
                            $erreur = "Votre mail n'est pas valide !";
                        }

                    }else{
                        $erreur = "Vos adresses mail ne correspondent pas!";
                    }

                }else{
                    $erreur = "votre prénom ne doit pass dépasser 60 caractères!";
                }

            }else{
                $erreur = "Votre nom ne doit pas dépasser 60 caractères!";
            }
            
        }else{
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
    <title>Inscription</title>
</head>
<body>
    <div align="center">
        <h2>Inscription</h2>
        <br/>
        <form action="" method="POST">
            <table>
                <tr>
                    <td align="right">
                        <label for="nom">Nom: </label>
                    </td>
                    <td>
                        <input type="text" class="form-control" placeholder="nom" id="nom" name="nom" value="<?php if(isset($nom)) { echo $nom; } ?>">
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="prenom">Prenom: </label>
                    </td>
                    <td>
                        <input type="text" class="form-control" placeholder="prenom" id="prenom" name="prenom" value="<?php if(isset($prenom)) { echo $prenom; } ?>">
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="date_de_naissance">Date de Naissance: </label>
                    </td>
                    <td>
                        <input type="date" class="form-control" placeholder="Date de Naissance" id="date_de_naissance" name="date_de_naissance" value="<?php if(isset($date_de_naissance)) { echo $pseudo; } ?>">
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="mail">Mail: </label>
                    </td>
                    <td>
                        <input type="email" class="form-control" placeholder="votre email" id="mail" name="mail" value="<?php if(isset($mail)) { echo $mail; } ?>">
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="mail2">Confirmation du mail: </label>
                    </td>
                    <td>
                        <input type="email" class="form-control" placeholder="confirmez votre mail" id="mail2" name="mail2" value="<?php if(isset($mail2)) { echo $mail2; } ?>">
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="mdp">Mot de passe: </label>
                    </td>
                    <td>
                        <input type="password" class="form-control" placeholder="votre mot de passe" id="mdp" name="mdp">
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="mdp2">Confirmation du mot de passe: </label>
                    </td>
                    <td>
                        <input type="password" class="form-control" placeholder="confirmez votre mdp" id="mdp2" name="mdp2">
                    </td>
                </tr>
                <tr>
                    <td align="right">
                        <label for="telephone">Téléphone: </label>
                    </td>
                    <td>
                        <input type="text" class="form-control" placeholder="telephone" id="telephone" name="telephone" value="<?php if(isset($telephone)) { echo $telephone; } ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td align="center">
                        <br/>
                        <input type="submit" class="btn btn-primary" name="forminscription" value="je m'inscris">
                    </td>
                </tr>
            </table>
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