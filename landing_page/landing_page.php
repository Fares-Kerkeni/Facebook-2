<?php
    include '../connexion_bdd.php';
    //On démarre une nouvelle session
    session_start();
    // formulaire inscription
    if (isset($_POST['submit_inscription'])){
        if (!isset($_POST['email']) || !isset($_POST['tel']) || !isset($_POST['nom']) || !isset($_POST['prenom']) || !isset($_POST['pseudo']) || !isset($_POST['genre']) || !isset($_POST['password']) || !isset($_POST['verif_password']) || !isset($_POST['date_naissance'])){
            echo 'vous avez pas remplis tous les champs';
        }
        else{
            $email = $_POST['email'];
            $tel = $_POST['tel'];
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $pseudo = $_POST['pseudo'];
            $genre = $_POST['genre'];
            $password = $_POST['password'];
            $verif_password = $_POST['verif_password'];
            $date_naissance = $_POST['date_naissance'];
            // verifie si le mail est deja dans la base de donnée
            $verif_mail = $pdo->prepare("SELECT * FROM User WHERE adresse_mail = '".$email."'");
            $verif_mail->execute();
            $count = $verif_mail->rowCount();
            if($count == 1){
                echo"mail deja utilisé";
            }
            else{
                // verifie si le le champ "mot de passe" et "confirmer mot de passe" sont les memes
                if($verif_password != $password){
                    echo"pas le meme mdp";
                }
                else{
                    // creer l'utilisateur
                    $requete_inscription = $pdo->prepare("INSERT INTO User (adresse_mail, numero_tel, nom, prenom, pseudo, genre, mot_de_passe, date_naissance) VALUES ('".$email."','".$tel."','".$nom."','".$prenom."','".$pseudo."','".$genre."','".$password."','".$date_naissance."');");
                    $requete_inscription->execute();
                   
                    $select_profil = $pdo->prepare('SELECT MAX(id_user) from user ');
                    $select_profil->execute();
                    $donnees_select_profil = $select_profil->fetchAll(PDO::FETCH_ASSOC);
                   
                    $id_utilisateur = $donnees_select_profil[0]["MAX(id_user)"];

                    $sadd_ami = $pdo->prepare("INSERT INTO contacts (id_contact, id_user) VALUES ('".$id_utilisateur."','".$id_utilisateur."');");
                    $sadd_ami->execute();

                    $requete_description  = $pdo->prepare("INSERT INTO description (`id_user`, `image`, `banniere`, `ecole`, `description`, `ville`, `code_postal`, `pays`) VALUES
                    (:id_utilisateur , 'default_profil.jpg', 'default_banner.jpg', '', '', '',0, '');");
        
                    $requete_description->execute([
                        ":id_utilisateur" =>  $id_utilisateur
                    ]);
                }
            }
        }
    }
    // connexion utilisateur
    if (isset($_POST['submit_connexion'])){
        if (!isset($_POST['email']) || !isset($_POST['password'])){
            echo 'vous avez pas remplis tous les champs';
        }
        else{
            $email = $_POST['email'];
            $password = $_POST['password'];
            $requete_connexion = $pdo->prepare("SELECT * FROM User WHERE mot_de_passe = '$password' and adresse_mail = '$email'");
            $requete_connexion->execute();
            $verif_connexion = $requete_connexion->fetch(PDO::FETCH_ASSOC);
            $count = $requete_connexion->rowCount();
            // si le mot de passe et l'adresse mail existe dans la base de donnée le count sera = a 1 donc il sera connecté
            if($count == 1){
                $_SESSION['prenom'] = $verif_connexion["prenom"];
                $_SESSION['nom'] = $verif_connexion["nom"];
                $_SESSION['id_user'] = $verif_connexion["id_user"];
                $_SESSION['pseudo'] = $verif_connexion["pseudo"];
                header('Location: ../HomePage/homepage.php');
            }
            else{
                echo "mauvais mot de passe ou mauvaise adresse mail";            
            }
        }
    }
    // deconnexion
    if (isset($_POST['submit_deconnexion'])) {
		$_SESSION = array();
		session_destroy();
		unset($_SESSION);
		header('Location: landing_page.php');
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing_page</title>
    <link rel="stylesheet" href="../root.css">
    <link rel="stylesheet" href="landing.page.css">
</head>
<body>
    <div class="landing_page">
        <div class="title">
            <h1>FACEBOOK <span>2</span></h1>
            
        </div>
        <div class="connexion_inscription">
            <div id="inscription" class="inscription">
                <form action="landing_page.php" method="post">
                    <input type="text" name="email" placeholder="Adresse Mail" required>
                    <input type="tel" name="tel" placeholder="Numéro de téléphone">
                    <input type="text" name="nom" placeholder="Nom" required>
                    <input type="text" name="prenom" placeholder="Prénom" required>
                    <input type="text" name="pseudo" placeholder="Pseudo">
                    <input type="text" name="genre" placeholder="Genre"> 
                    <input type="password" name="password" placeholder="Mot de passe" required>
                    <input type="password" name="verif_password" placeholder="Confirmer mot de passe" required>
                    <input type="date" name="date_naissance">
                    <input type="submit" name="submit_inscription" value="S'inscrire">
                    <p>Vous avez déjà un compte ? Connectez-vous <a id="open_connexion"><strong>ici</strong></a>.</p>
                </form>
            </div>
            <div id="connexion" class="connexion">
                <form action="landing_page.php" method="post">
                    <input type="text" name="email" placeholder="Adresse Mail">
                    <input type="password" name="password" placeholder="Mot de passe">
                    <input type="submit" name="submit_connexion" value="Se connecter">
                    <p>Pas de compte ? Inscrivez-vous <a id="open_inscription"><strong>ici</strong></a>.</p>
                </form>
            </div>
        </div>
    </div>
    <script src="../theme.js"></script>
    <script src="landing_page.js"></script>
</body>
</html>