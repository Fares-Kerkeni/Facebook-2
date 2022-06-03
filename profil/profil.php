<?php
    
    include '../connexion_bdd.php';
    session_start();

    include '../deco.php';
    deconnexion();
    // selectionne les infos de la personne connecté
    $select_profil = $pdo->prepare('SELECT * from user WHERE id_user="'.$_SESSION['id_user'].'"');
    $select_profil->execute();
    $donnees_select_profil= $select_profil->fetchAll(PDO::FETCH_ASSOC);

    // selectionne les postes de la personne connecté
    $select_postes_user = $pdo->prepare('SELECT * FROM `postes` INNER JOIN user ON postes.id_user = user.id_user INNER JOIN description ON user.id_user = description.id_user WHERE user.id_user="'.$_SESSION['id_user'].'" ORDER BY postes.date_poste DESC');
    $select_postes_user->execute();
    $donnees_select_postes_user = $select_postes_user->fetchAll(PDO::FETCH_ASSOC);    
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="../root.css">

    <link rel="stylesheet" href="profil.css">


</head>

<body>
    <div id="header">

      <div id="navbar">
        <div id="logo">
          <p>FaceBook2</p>
        </div>

        <div id="boutons">
          <div style="display:inline-block; height:20px; witdh:20px;" id="theme_white" class="theme_change"> ⚪ </div>
          <div style="display:inline-block; height:20px; witdh:20px;" id="theme_black" class="theme_change"> ⚫ </div>
          <button class="bouton"><a href="../HomePage/homepage.php"><img src="../icone/home.svg" alt="homepage" height="20px" witdh="20px"></a></button>
          <button class="bouton"><a href="../Messagerie/messagerie.php"><img src="../icone/mail.svg" alt="messages" height="20px" witdh="20px"></a></button>
          <button class="bouton"><a href="../profil/profil.php"><img src="../icone/user.svg" alt="profil" height="20px" witdh="20px"></a></button>
          <form class="formulaire" method="POST">
            <button class="bouton" name="submit_deconnexion"><img src="../icone/power.svg" alt="logout" height="20px" witdh="20px"></button>
          </form>

        </div>

      </div>

    </div>  






  <div id="profil_container">
    <div id="profil">

      <div id="banner">
      
        <?php
            // affiche la banniere de la personne connecté
            $affiche_bannière = $pdo->prepare("SELECT banniere FROM description WHERE id_user=:id_user");
            $affiche_bannière->execute([
            
                ":id_user" => $_SESSION['id_user']
            ]);

            while($data = $affiche_bannière->fetch()){
            ?>
            <div >  
                <img src='../upload/<?= $data['banniere'] ?>' class="img_banniere"><br>
            </div>
            <?php
            }
            ?>
                   
     
      
      
      <?php 
        // affiche l'image de profil de la personne connecté
        $affiche = $pdo->prepare("SELECT image FROM description WHERE id_user=:id_user");
        $affiche->execute([
           
            ":id_user" => $_SESSION['id_user']
        ]);
        
        while($data = $affiche->fetch()){
        ?>
          <div id="user_pdp">
        <button class="bouton"> <img src='../upload/<?= $data['image'] ?>'  alt="profil" height="50px" witdh="50px" >
       
      </button>
        </div>

        <?php
        }
        ?>
      

      </div>

      

      <div id="user_profil">

        <div id="first_line">
          <?php 
          // affiche les infos de la personne connecté
          foreach($donnees_select_profil as $donnee_select_profil): ?>
          <div id="nom"><p><?= $donnee_select_profil["pseudo"]?></p></div>
          <button class="bouton"><a href="change.php"><img src="../icone/edit-2.svg" alt="edit" height="20px" witdh="20px"></a></button>

        </div>

        <div id="infos">
          <p><?= $donnee_select_profil["prenom"]?> <?= $donnee_select_profil["nom"]?> 
          <img src="../icone/mail.svg" alt="mail" height="10px" witdh="10px"> <?= $donnee_select_profil["adresse_mail"] ?> 
         
        </div>
        
        <?php endforeach; ?>

        <div id="description">futur description ici</div>
      </div>
      
      
    </div>




    </div>

  </div>


    <div id="page">

      <div id="publications">

      <!-- new post -->
        <div id="new_post">
          
          <div id="new">

          <h2>Nouveau poste</h2>
            <form action="formulaires_profils.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="text_poste">
                <label for="file">Fichier</label>
                <input type="file" name="file">

                <button type="submit"  name="submit_post">Enregistrer</button>
            </form>
            
                
               

           
          </div>

        </div>
        <!-- Une publication -->
        <?php 
          // affiche les postes de la personne connecté
          foreach($donnees_select_postes_user as $donnee_select_postes_user):
          // recupere les like du poste
          $select_like = $pdo->prepare("SELECT * FROM aime_poste WHERE id_poste = '".$donnee_select_postes_user['id_poste']."'");
          $select_like->execute();
          $count_select_like = $select_like->rowCount(); 
          // recupere le nombre de com du poste
          $nb_com = $pdo->prepare("SELECT * FROM commentaire WHERE id_poste = '".$donnee_select_postes_user['id_poste']."'");
          $nb_com->execute();
          $count_nb_com = $nb_com->rowCount();
          ?>
          <div class="publication">
            <div class="head">
              <div class="user">
                  <a href="../profil/profil_other_user.php?id_other_user=<?= $donnee_select_postes_user["id_user"]?>" class="pp"><img style="height:100%; width:100%; border-radius:50%;" src="../upload/<?= $donnee_select_postes_user["image"]?>" alt=""></a>
                  <p class="name"><?= $donnee_select_postes_user["prenom"]?> <?= $donnee_select_postes_user["nom"]?></p>
                  <div class="datetime"><li><?= $donnee_select_postes_user["date_poste"]?></li></div>
              </div>
              <div class="settings">
                <button onclick="document.getElementById('poste_<?= $donnee_select_postes_user['id_poste']?>').style.display = 'flex'" class="bouton"><img src="../icone/more-horizontal.svg" alt="more_info" height="20px" witdh="20px"></button>
              </div>
            </div>  

            <div class="content">
              <p class="texte"><?= $donnee_select_postes_user["texte_poste"]?></p>         
               <img src='../upload/<?= $donnee_select_postes_user["image_poste"]?>'>
             
            </div>

            <div class="reaction">
            <form action="formulaires_profils.php" method="post">
                <input type="hidden" name="id_poste" value="<?= $donnee_select_postes_user["id_poste"]?>">
                <button class="bouton" name="submit_like"><img src="../icone/heart.svg" alt="like" height="20px" witdh="20px"></button>
              </form>
              <!-- affiche le nombre de like  -->
              <p style="margin-right:20px;"><?= $count_select_like ?></p>
              <button onclick="open_com('commentaire_poste_<?= $donnee_select_postes_user['id_poste']?>');" class="bouton"><img src="../icone/message-square.svg" alt="like" height="20px" witdh="20px"></button>
               <!-- affiche le nombre de commentaire  -->
              <p style="margin-right:20px;"><?= $count_nb_com ?></p>
            </div>
            <div class="commentaires" id="commentaire_poste_<?= $donnee_select_postes_user['id_poste']?>">
              <?php 
              // recupere les commentaires du poste
              $select_commentaire = $pdo->prepare('SELECT * FROM commentaire INNER JOIN user ON commentaire.id_user = user.id_user INNER JOIN description ON user.id_user = description.id_user WHERE id_poste = "'.$donnee_select_postes_user['id_poste'].'" ORDER BY commentaire.date_commentaire DESC');
              $select_commentaire->execute();
              $donnees_select_commentaire = $select_commentaire->fetchAll(PDO::FETCH_ASSOC);  
              // affiche tous les commentaire du poste
              foreach($donnees_select_commentaire as $donnee_select_commentaire): ?>
                <div class="com">
                  <div class="first">
                    <!-- j'arrive pas a aligner les boutons avec le texte, flemme -->
                    <div class="first_txt" style="margin-top:20px; margin-bottom:20px;"><p><?= $donnee_select_commentaire["prenom"]?> <?= $donnee_select_commentaire["nom"]?> - <?= $donnee_select_commentaire["date_commentaire"]?></p></div>
                    <!-- <div class="first_button"><button class="bouton"><img src="../icone/more-horizontal.svg" alt="more_info" height="20px" witdh="20px"></button></div> -->
                  </div>
                  <p> <?= $donnee_select_commentaire["texte_commentaire"]?></p>
                  <?php
                    // recupere le nombre de like du commentaire
                    $select_like_com = $pdo->prepare("SELECT * FROM aime_commentaire WHERE id_commentaire = '".$donnee_select_commentaire['id_commentaire']."'");
                    $select_like_com->execute();
                    $count_select_like_com = $select_like_com->rowCount();
                  ?>
                  <div class="second">
                    <form action="formulaires_profils.php" method="post">
                      <input type="hidden" name="id_com" value="<?= $donnee_select_commentaire["id_commentaire"]?>">
                      <button class="bouton" name="submit_like_com"><img src="../icone/heart.svg" alt="like" height="20px" witdh="20px"></button>
                    </form>
                    <!-- affiche le nombre de like du commentaire -->
                    <span><?= $count_select_like_com?></span>
                  </div>
                </div>
              <?php 
              endforeach; ?>
              <form action="formulaires_profils.php" method="post">
                <input type="hidden" name="id_poste" value="<?= $donnee_select_postes_user["id_poste"]?>">
                <input type="text" name="texte_commentaire" placeholder="commentaire">
                <input type="submit" name="submit_commentaire" value="commenter">
              </form>
            </div>
          </div>
          <div id="poste_<?= $donnee_select_postes_user['id_poste']?>" class="popup_poste">
            <div class="modification_publication">
              <div onclick="document.getElementById('poste_<?= $donnee_select_postes_user['id_poste']?>').style.display = 'none'">X</div>
              <form action="formulaires_profils.php" method="post">
                <input type="hidden" name="id_poste" value="<?= $donnee_select_postes_user["id_poste"]?>">
                <input type="submit" name="submit_delete_post" value="delete">
              </form>
              <form action="formulaires_profils.php" method="POST" enctype="multipart/form-data">
                
                    <label for="banniere">Modifier image poste</label>
                    <input type="hidden" name="id_poste" value="<?= $donnee_select_postes_user["id_poste"]?>">
                    <input type="file" name="banniere" value="<?= $donnee_select_postes_user["id_poste"]?>">

                    <button type="submit" name="submit_update_img_post" value="update">Enregistrer</button>
              </form>
              <form action="formulaires_profils.php" method="post">
                <input type="hidden" name="id_poste" value="<?= $donnee_select_postes_user["id_poste"]?>">
                <input type="text" name="new_texte_poste" placeholder="nouveau texte">
                <input type="submit" name="submit_update_post" value="update">
              </form>
            </div>
          </div>
        <?php endforeach; ?>

      </div>




      <?php
      // recupere la liste d'amis
      $select_friends = $pdo->prepare("SELECT * FROM contacts WHERE id_user = '".$_SESSION['id_user']."'");
      $select_friends->execute();
      $donnees_select_friends = $select_friends->fetchAll(PDO::FETCH_ASSOC);
      ?>
      <div id="suggestions">
      <?php
                $select_list_group_page = $pdo->prepare("SELECT * FROM group_page INNER JOIN group_page_user ON group_page.id_group_page = group_page_user.id_group_page WHERE group_page_user.id_user = '".$_SESSION['id_user']."'");
                $select_list_group_page->execute();
                $donnees_select_list_group_page = $select_list_group_page->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <p>
            <h2>Groupes</h2>
            </p>
            <?php foreach($donnees_select_list_group_page as $donnee_select_list_group_page):?>
                <div style="display:flex; align-items:center;" >
                <img style="height:30px; width:30px; border-radius:50%; margin-right:10px;" src="../upload/<?= $donnee_select_list_group_page["image"]?>" alt="">
                <a style="color:black;" href="../profil/group_page.php?id_group_page=<?= $donnee_select_list_group_page["id_group_page"]?>"><?= $donnee_select_list_group_page["nom"]?></a>
                </div>
            <?php endforeach; ?>

        <?php
          $select_friends = $pdo->prepare("SELECT * FROM contacts WHERE id_user = '".$_SESSION['id_user']."'");
          $select_friends->execute();
          $donnees_select_friends = $select_friends->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div style="width:120px; margin-top:20px;" class="create_group" onclick="document.getElementById('container_popup_create_group').style.display = 'flex'">Creer un groupe</div>
        <div class="container_popup_create_group" id="container_popup_create_group">
            <div class="popup_create_group">
              <div style="position:absolute; right:15px; top:15px;" onclick="document.getElementById('container_popup_create_group').style.display ='none'">X</div>
              <div>Creation du groupe</div>
              <form action="formulaires_profils.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_user" value="<?= $_SESSION['id_user']?>">
                <input style="margin-top:20px;" type="text" name="group_page_name" placeholder="nom de la page">
                <!-- <input type="file" name="group_page_image">
                <input type="file" name="group_page_banniere"> -->
                <input type="text" name="group_page_description" placeholder="description de la page">
                <input type="submit" name="submit_create_group_page" value="creer">
              </form>
            </div>
        </div>
        <p>
          <h2>Liste d'amis</h2>
        </p>
          <?php foreach($donnees_select_friends as $donnee_select_friends):
            $select_info_friends = $pdo->prepare("SELECT * FROM user INNER JOIN description ON user.id_user = description.id_user WHERE user.id_user = '".$donnee_select_friends["id_contact"]."'");
            $select_info_friends->execute();
            $donnees_select_info_friends = $select_info_friends->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php 
            // affiche ses amis
            foreach($donnees_select_info_friends as $donnee_select_info_friends):
              if($donnee_select_info_friends["id_user"] != $_SESSION['id_user']){
              ?>
              <div style="display:flex; align-items:center;" >
                <img style="height:30px; width:30px; border-radius:50%; margin-right:10px;" src="../upload/<?= $donnee_select_info_friends["image"]?>" alt="">
                <a style="color:black;" href="../profil/profil_other_user.php?id_other_user=<?= $donnee_select_info_friends["id_user"]?>"><?= $donnee_select_info_friends["prenom"]?> <?= $donnee_select_info_friends["nom"]?></p>
              </div>
              <?php 
              }
            endforeach; ?>
          <?php 
          endforeach; ?>
      </div>

    </div>

   
    <script src="profil.js"></script>
    <script src="../theme.js"></script>
    <script src="../HomePage/homepage.js"></script>
    
</body>
</html>
