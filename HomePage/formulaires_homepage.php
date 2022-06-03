<?php
    include '../connexion_bdd.php';
    include '../deco.php';
    //On démarre une nouvelle session
    session_start();
    deconnexion();
    date_default_timezone_set('Europe/Paris');
    $date_actuelle = date('y-m-d h:i:s');
    // formulaire pour envoyer un poste
    if (isset($_POST['submit_post'])){
        if ($_POST['text_poste'] == ""){
            header('Location: homepage.php');
        }
        else{
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
            }
            $text_poste = $_POST['text_poste'];
            $insert_post = $pdo->prepare("INSERT INTO postes (id_user, texte_poste, image_poste, date_poste) VALUES ('".$_SESSION['id_user']."','".$text_poste."','".$file."','".$date_actuelle."');");
            $insert_post->execute();
            header('Location: homepage.php');
        }
    }

    // formulaire pour modifier un poste
    if (isset($_POST['submit_update_post'])){
        if ($_POST['new_texte_poste'] == ""){
            header('Location: homepage.php');
        }
        else{
            $new_texte_poste = $_POST['new_texte_poste'];
            $id_poste = $_POST['id_poste'];
            $update_post = $pdo->prepare("UPDATE postes SET texte_poste = '".$new_texte_poste."' WHERE id_poste = '".$id_poste."'");
            $update_post->execute();
            header('Location: homepage.php');
        }
    }
    
    // formulaire pour commenter un poste
    if (isset($_POST['submit_commentaire'])){
        if ($_POST['texte_commentaire'] == ""){
            header('Location: homepage.php');
        }
        else{
            $texte_commentaire = $_POST['texte_commentaire'];
            $id_poste = $_POST['id_poste'];
            $insert_commentaire = $pdo->prepare("INSERT INTO commentaire (id_user, id_poste, texte_commentaire, image_commentaire, date_commentaire) VALUES ('".$_SESSION['id_user']."','".$id_poste."','".$texte_commentaire."','image','".$date_actuelle."');");
            $insert_commentaire->execute();
            header('Location: homepage.php');
        }
    }

    // formulaire pour aimer un poste
    if (isset($_POST['submit_like'])){
        $id_poste = $_POST['id_poste'];
        $verif_like = $pdo->prepare("SELECT * FROM aime_poste WHERE id_user = '".$_SESSION['id_user']."' AND id_poste = '".$id_poste."'");
        $verif_like->execute();
        $count_verif_like = $verif_like->rowCount();
        if($count_verif_like == 0){
            $insert_like = $pdo->prepare("INSERT INTO aime_poste (id_user, id_poste) VALUES ('".$_SESSION['id_user']."','".$id_poste."')");
            $insert_like->execute();
            header('Location: homepage.php');
        }
        else{
            $delete_like = $pdo->prepare("DELETE FROM aime_poste WHERE id_user = '".$_SESSION['id_user']."' AND id_poste = '".$id_poste."'");
            $delete_like->execute();
            header('Location: homepage.php');
        }
    }

    // formulaire pour aimer un commentaire
    if (isset($_POST['submit_like_com'])){
        $id_com = $_POST['id_com'];
        $verif_like_com = $pdo->prepare("SELECT * FROM aime_commentaire WHERE id_user = '".$_SESSION['id_user']."' AND id_commentaire = '".$id_com."'");
        $verif_like_com->execute();
        $count_verif_like_com = $verif_like_com->rowCount();
        if($count_verif_like_com == 0){
            $insert_like_com = $pdo->prepare("INSERT INTO aime_commentaire (id_user, id_commentaire) VALUES ('".$_SESSION['id_user']."','".$id_com."')");
            $insert_like_com->execute();
            header('Location: homepage.php');
        }
        else{
            $delete_like_com = $pdo->prepare("DELETE FROM aime_commentaire WHERE id_user = '".$_SESSION['id_user']."' AND id_commentaire = '".$id_com."'");
            $delete_like_com->execute();
            header('Location: homepage.php');
        }
    }

    // formulaire pour supprimer un poste
    if (isset($_POST['submit_delete_post'])){
        $id_poste = $_POST['id_poste'];

        $verif_like_post = $pdo->prepare("SELECT * FROM aime_poste WHERE id_poste = '".$id_poste."'");
        $verif_like_post->execute();
        $count_verif_like_post = $verif_like_post->rowCount();
        if($count_verif_like_post > 0){
            $delete_post = $pdo->prepare("DELETE FROM aime_poste WHERE id_poste = '".$id_poste."'");
            $delete_post->execute();
        }

        $verif_com_post = $pdo->prepare("SELECT * FROM commentaire WHERE id_poste = '".$id_poste."'");
        $verif_com_post->execute();
        $count_verif_com_post = $verif_com_post->rowCount();
        if($count_verif_com_post > 0){
            $delete_post = $pdo->prepare("DELETE FROM commentaire WHERE id_poste = '".$id_poste."'");
            $delete_post->execute();
        }

        $delete_post = $pdo->prepare("DELETE FROM postes WHERE id_poste = '".$id_poste."'");
        $delete_post->execute();
        header('Location: homepage.php');        
    }

    // formulaire pour changer l'image d'un poste
    if (isset($_POST['submit_update_img_post'])){
        if(isset($_FILES['banniere'])){
            $tmpName = $_FILES['banniere']['tmp_name'];
            $name = $_FILES['banniere']['name'];
            $size = $_FILES['banniere']['size'];
            $error = $_FILES['banniere']['error'];
            $id_poste = $_POST['id_poste'];
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
        
                $req = $pdo->prepare("UPDATE postes SET image_poste = :file  WHERE id_poste = '".$id_poste."'");
                $req->execute([
                    ":file" => $banniere,
                   
                ]);
                
            }
        }
        header('Location: homepage.php');
    }
?>