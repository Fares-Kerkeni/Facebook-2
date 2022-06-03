<?php
include '../deco.php';
include '../connexion_bdd.php';
session_start();
deconnexion();
   
if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $description = filter_input(INPUT_POST, "description");
    $ecole = filter_input(INPUT_POST, "ecole");
    $code_postal = filter_input(INPUT_POST, "code_postal");
    $ville = filter_input(INPUT_POST, "ville");
    $pays = filter_input(INPUT_POST, "pays");
    $nom = filter_input(INPUT_POST, "Nom");
    $prenom = filter_input(INPUT_POST, "Prenom");
    $pseudo = filter_input(INPUT_POST, "Pseudo");

    // modifier sa description
    if ($description) {
        $description = strip_tags($description);
        // Méthode 2 : Encoder les caractères 
        $description = htmlentities($description);
        //On démarre une nouvelle session
        $update_description = $pdo->prepare("UPDATE description SET description = :description WHERE id_user = :id_user");
         // Etape 2 : J'exécute la requête
        $update_description->execute( [
            ":description" => $description,
            ":id_user" => $_SESSION['id_user'] 
        ]);
    }
    // modifier son ecole
    if ($ecole) {
        $ecole = strip_tags($ecole);
        // Méthode 2 : Encoder les caractères 
        $ecole = htmlentities($ecole);
        $update_ecole = $pdo->prepare("UPDATE description SET ecole = :ecole  WHERE id_user = :id_user");
        // Etape 2 : J'exécute la requête
        $update_ecole->execute( [
        ":ecole" => $ecole,
        ":id_user" => $_SESSION['id_user'] 
        ]);
    }
    // modifier sa ville
    if ($ville) {
        $ville = strip_tags($ville);
        // Méthode 2 : Encoder les caractères 
        $ville = htmlentities($ville);
        $update_ville = $pdo->prepare("UPDATE description SET ville = :ville WHERE id_user = :id_user");
        // Etape 2 : J'exécute la requête
        $update_ville->execute( [
        ":ville" => $ville,
        ":id_user" => $_SESSION['id_user'] 
        ]);
    }
    // modifier son code postal
    if ($code_postal) {
        $code_postal = strip_tags($code_postal);
        // Méthode 2 : Encoder les caractères 
        $code_postal = htmlentities($code_postal);
        $update_code_postal = $pdo->prepare("UPDATE description SET code_postal = :code_postal WHERE id_user = :id_user");
        // Etape 2 : J'exécute la requête
        $update_code_postal->execute( [
        ":code_postal" => $code_postal,
        ":id_user" => $_SESSION['id_user'] 
        ]);
    }
    // modifier son pays
    if ($pays) {
        $pays = strip_tags($pays);
        // Méthode 2 : Encoder les caractères 
        $pays = htmlentities($pays);
        $update_pays = $pdo->prepare("UPDATE description SET pays = :pays WHERE id_user = :id_user");
        // Etape 2 : J'exécute la requête
        $update_pays->execute( [
        ":pays" => $pays,
        ":id_user" => $_SESSION['id_user'] 
        ]);
    } 
    // modifier son nom
    if ($nom) {
        $nom = strip_tags($nom);
        // Méthode 2 : Encoder les caractères 
        $nom = htmlentities($nom);
        $update_nom = $pdo->prepare("UPDATE user SET nom = :nom WHERE id_user = :id_user");
        // Etape 2 : J'exécute la requête
        $update_nom->execute( [
        ":nom" => $nom,
        ":id_user" => $_SESSION['id_user'] 
        ]);
    }     
    // modifier son prenom
    if ($prenom) {
        $prenom = strip_tags($prenom);
        // Méthode 2 : Encoder les caractères 
        $prenom = htmlentities($prenom);
        $update_prenom = $pdo->prepare("UPDATE user SET prenom = :prenom WHERE id_user = :id_user");
        // Etape 2 : J'exécute la requête
        $update_prenom->execute( [
        ":prenom" => $prenom,
        ":id_user" => $_SESSION['id_user'] 
        ]);
    } 
    // modifier son pseudo
    if ($pseudo) {
        $pseudo = strip_tags($pseudo);
        // Méthode 2 : Encoder les caractères 
        $pseudo = htmlentities($pseudo);
        $update_pseudo = $pdo->prepare("UPDATE user SET pseudo = :pseudo WHERE id_user = :id_user");
        // Etape 2 : J'exécute la requête
        $update_pseudo->execute( [
        ":pseudo" => $pseudo,
        ":id_user" => $_SESSION['id_user'] 
        ]);
}

?>
<?php
// modifier son image de profil
if(isset($_FILES['file'])){
    $tmpName = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $error = $_FILES['file']['error'];

    $tabExtension = explode('.', $name);
    $extension = strtolower(end($tabExtension));

    $extensions = ['jpg', 'png', 'jpeg', 'gif'];
    $maxSize = 4000000000;
   
    if(in_array($extension, $extensions) && $size <= $maxSize && $error == 0){

        $uniqueName = uniqid('', true);
        //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
        $file = $uniqueName.".".$extension;
        //$file = 5f586bf96dcd38.73540086.jpg

        move_uploaded_file($tmpName, '../upload/'.$file);

        $req = $pdo->prepare("UPDATE description SET image = :file WHERE id_user=:id_user");
        $req->execute([
            ":file" => $file,
            ":id_user" => $_SESSION['id_user']
        ]);
    }

}
?>
<?php
    // modifier sa banniere
    if(isset($_FILES['banniere'])){
        $tmpName = $_FILES['banniere']['tmp_name'];
        $name = $_FILES['banniere']['name'];
        $size = $_FILES['banniere']['size'];
        $error = $_FILES['banniere']['error'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));

        $extensions = ['jpg', 'png', 'jpeg', 'gif'];
        $maxSize = 4000000000;
    
        if(in_array($extension, $extensions) && $size <= $maxSize && $error == 0){

            $uniqueName = uniqid('', true);
            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
            $banniere = $uniqueName.".".$extension;
            //$file = 5f586bf96dcd38.73540086.jpg

            move_uploaded_file($tmpName, '../upload/'.$banniere);

            $req = $pdo->prepare("UPDATE description SET banniere = :file WHERE id_user=:id_user");
            $req->execute([
                ":file" => $banniere,
                ":id_user" => $_SESSION['id_user']
            ]);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change</title>

    <link rel="stylesheet" href="../root.css">
    <link rel="stylesheet" href="change.css">


</head>

<body>



    <div id="mes_infos">

        <div id="back_button">
            <button class="bouton"><a href="../profil/profil.php"><img src="../icone/x.svg" alt="back" height="20px" witdh="20px"></a></button>
        </div>


        <p class="title">Modifier mes informations :</p>
    
        <form action="change.php" method="POST">

            <div id="loc">
                <p>Ma localisation :</p>
                <input type="pays" name="pays" placeholder="pays">
                <input type="number" name="code_postal" placeholder="code_postal">
                <input type="ville" name="ville" placeholder="ville">
            </div>

            <div id="school">
                <p>Mon école :</p>
                <input type="text" name="ecole" placeholder="ecole">
            </div>

            <div id="identity">
                <p>Mon identité :</p>
                <input type="text" name="Nom" placeholder="Nom">
                <input type="text" name="Prenom" placeholder="Prenom">
                <input type="text" name="Pseudo" placeholder="Pseudo">
            </div>


            <div id="descp">
                <p>Changer ma description :</p>
                <input type="text" name="description" placeholder="description">
            </div>

            <div id="confirm">
                <input type="submit" name="submit_edit" value="Valider les modifications">
            </div>

        </form>
        <form action="formulaires_profils.php" method="post">
            <input type="submit" name="delete_user" value="Supprimer son compte">
        </form>
    </div>








    <div id="zone_images">

        <div id="profil_image">

            <p class="title">Photo de profil</p>
            <p>Image actuelle :</p>

                <?php 
                     // affiche l'image
                    $affiche = $pdo->prepare("SELECT image FROM description WHERE id_user=:id_user");
                    $affiche->execute([
                    
                        ":id_user" => $_SESSION['id_user']
                    ]);
                    
                    while($data = $affiche->fetch()){
                    ?>
                    <div class="img_profil">  
                        <img src='../upload/<?= $data['image'] ?>'>
                    </div>
                    <?php
                    }
                    ?>
                <form action="change.php" method="POST" enctype="multipart/form-data">
                
                    <label for="file">Choississez une nouvelle image :</label>
                    <input type="file" name="file">

                    <button type="submit">Valider</button>
                </form>

        </div>
        <div id="banner_image">







                
                <p class="title">Bannière</p>
                <p>Bannière actuelle :</p>
                <?php 
                    // affiche la banniere
                    $affiche_bannière = $pdo->prepare("SELECT banniere FROM description WHERE id_user=:id_user");
                    $affiche_bannière->execute([
                    
                        ":id_user" => $_SESSION['id_user']
                    ]);
                    
                    while($data = $affiche_bannière->fetch()){
                    ?>
                    <div class="img_banner">  
                        <img src='../upload/<?= $data['banniere'] ?>'>
                    </div>
                    <?php
                    }
                    ?>

                <!-- validation : -->
                <form action="change.php" method="POST" enctype="multipart/form-data">
                
                <label for="banniere">Choississez une nouvelle image :</label>
                <input type="file" name="banniere">

                <button type="submit">Valider</button>
                </form>


        </div>
    </div>
        
</body>

</html>