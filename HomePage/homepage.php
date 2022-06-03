<?php
include '../connexion_bdd.php';
include '../deco.php';
session_start();
deconnexion();
$select_profil = $pdo->prepare('SELECT * from user WHERE id_user="'.$_SESSION['id_user'].'"');
$select_profil->execute();
$donnees_select_profil= $select_profil->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['submit_search_user'])){
  header('Location: homepage.php?search_user='.$_POST['search_user']);
}

$search_user = $_GET['search_user'];

// barre de recherche pour filtrer les personnes recherchées
$select_all_user = $pdo->prepare("SELECT * from user INNER JOIN description ON user.id_user = description.id_user WHERE pseudo LIKE '%{$search_user}%';");
$select_all_user->execute();
$donnees_select_all_user= $select_all_user->fetchAll(PDO::FETCH_ASSOC);

// selectionner les postes de l'utilisateur
$select_postes_user = $pdo->prepare('SELECT * FROM `postes` INNER JOIN user ON postes.id_user = user.id_user INNER JOIN description ON user.id_user = description.id_user ORDER BY postes.date_poste DESC' );
$select_postes_user->execute();
$donnees_select_postes_user = $select_postes_user->fetchAll(PDO::FETCH_ASSOC); 

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>homepage</title>

  <link rel="stylesheet" href="../root.css">
  <link rel="stylesheet" href="homepage.css">
  <link rel="stylesheet" href="boutonsheader.css">

</head>

<body>
  <div id="header">

    <div id="navbar">
      <div id="logo">
        <p>FaceBook2</p>
      </div>

      <div>
        <form id="search" action="homepage.php" method="POST">
          <div><input type="text" name="search_user" id="input" placeholder="Rechercher" class="recherche"></div>
          <div><input type="submit"  name="submit_search_user"></div>
        </form>
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
  <center>
    <div style="margin-top:100px; margin-bottom:20px;">Personnes trouvées :</div>
  </center>
  <div style="display:flex; justify-content: center">
    <?php 
    // afficher tous les utilisateurs du site, et on peut filtrer avec la barre de recherche
    foreach($donnees_select_all_user as $donnee_select_all_user): 
      if($donnee_select_all_user["id_user"] != $_SESSION['id_user']){
    ?>
      <a href="../profil/profil_other_user.php?id_other_user=<?= $donnee_select_all_user["id_user"]?>">
        <img style="height:50px; width:50px; border-radius:50px;" src="../upload/<?= $donnee_select_all_user["image"]?>" alt="">
      </a>
    <?php 
     }
    endforeach; 
    ?>
  </div>
  <div id="new_post">

    <div id="new">
    <h2>Nouveau poste</h2>
      <form action="formulaires_homepage.php" method="POST" enctype="multipart/form-data">
          <input type="text" name="text_poste">
          <label for="file">Fichier</label>
          <input type="file" name="file">
          <button type="submit"  name="submit_post">Enregistrer</button>
      </form>
    </div>

  </div>



  <div id="page">
    <div id="publications">
      <!-- Une publication -->
        <?php 
        // afficher tous les postes de l'utilisateur et de ses amis
        foreach($donnees_select_postes_user as $donnee_select_postes_user): 
          $verif_amitie = $pdo->prepare('SELECT * FROM `contacts` WHERE (id_user = "'.$_SESSION['id_user'].'" AND id_contact = "'.$donnee_select_postes_user['id_user'].'") OR (id_user = "'.$donnee_select_postes_user['id_user'].'" AND id_contact = "'.$_SESSION['id_user'].'")');
          $verif_amitie->execute();
          $count_amitie = $verif_amitie->rowCount();
          // si ils sont amis le poste s'affiche sinon rien
          if($count_amitie >= 1){
            // recupere les like d'un poste
            $select_like = $pdo->prepare("SELECT * FROM aime_poste WHERE id_poste = '".$donnee_select_postes_user['id_poste']."'");
            $select_like->execute();
            $count_select_like = $select_like->rowCount();
            // recupere le nombre de commentaire d'un poste
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
              <?php
              // bouton pour modifié le poste que si c'est son poste
              if($donnee_select_postes_user['id_user'] == $_SESSION['id_user']){
              ?>
              <div class="settings">
                <button onclick="document.getElementById('poste_<?= $donnee_select_postes_user['id_poste']?>').style.display = 'flex'" class="bouton"><img src="../icone/more-horizontal.svg" alt="more_info" height="20px" witdh="20px"></button>
              </div>
              <?php
              }
              ?>
            </div>  

            <div class="content">
              <p class="texte"><?= $donnee_select_postes_user["texte_poste"]?></p>  
              <img style="width:200px; margin-top: 20px;" src='../upload/<?= $donnee_select_postes_user["image_poste"]?>'>       
            </div>

            <div class="reaction">
              <form action="formulaires_homepage.php" method="post">
                <input type="hidden" name="id_poste" value="<?= $donnee_select_postes_user["id_poste"]?>">
                <button class="bouton" name="submit_like"><img src="../icone/heart.svg" alt="like" height="20px" witdh="20px"></button>
              </form>
              <!-- affiche nombre like -->
              <p style="margin-right:20px;"><?= $count_select_like ?></p>
              <button onclick="open_com('commentaire_poste_<?= $donnee_select_postes_user['id_poste']?>');" class="bouton"><img src="../icone/message-square.svg" alt="like" height="20px" witdh="20px"></button>
              <!-- affiche nombre commentaire -->
              <p style="margin-right:20px;"><?= $count_nb_com ?></p>
            </div>
            <div class="commentaires" id="commentaire_poste_<?= $donnee_select_postes_user['id_poste']?>">
              <?php 
              // recupere tous les commentaires
              $select_commentaire = $pdo->prepare('SELECT * FROM commentaire INNER JOIN user ON commentaire.id_user = user.id_user INNER JOIN description ON user.id_user = description.id_user WHERE id_poste = "'.$donnee_select_postes_user['id_poste'].'" ORDER BY commentaire.date_commentaire DESC');
              $select_commentaire->execute();
              $donnees_select_commentaire = $select_commentaire->fetchAll(PDO::FETCH_ASSOC);  
              foreach($donnees_select_commentaire as $donnee_select_commentaire): ?>
              <div class="com">
                  <div class="first">
                    <div class="first_txt" style="margin-top:20px; margin-bottom:20px;"><p><?= $donnee_select_commentaire["prenom"]?> <?= $donnee_select_commentaire["nom"]?> - <?= $donnee_select_commentaire["date_commentaire"]?></p></div>
                    <!-- <div class="first_button"><button class="bouton"><img src="../icone/more-horizontal.svg" alt="more_info" height="20px" witdh="20px"></button></div> -->
                  </div>
                  <p> <?= $donnee_select_commentaire["texte_commentaire"]?></p>
                  <?php
                    // recupere les likes des commentaires
                    $select_like_com = $pdo->prepare("SELECT * FROM aime_commentaire WHERE id_commentaire = '".$donnee_select_commentaire['id_commentaire']."'");
                    $select_like_com->execute();
                    $count_select_like_com = $select_like_com->rowCount();
                  ?>
                  <div class="second">
                    <form action="formulaires_homepage.php" method="post">
                      <input type="hidden" name="id_com" value="<?= $donnee_select_commentaire["id_commentaire"]?>">
                      <button class="bouton" name="submit_like_com"><img src="../icone/heart.svg" alt="like" height="20px" witdh="20px"></button>
                    </form>
                    <!-- affiche nombre like par com -->
                    <span><?= $count_select_like_com?></span>
                  </div>
              </div>
              <?php 
              endforeach; ?>
              <form action="formulaires_homepage.php" method="post">
                <input type="hidden" name="id_poste" value="<?= $donnee_select_postes_user["id_poste"]?>">
                <input type="text" name="texte_commentaire">
                <input type="submit" name="submit_commentaire" value="commenter">
              </form>
            </div>
          </div>
          <?php
          }
          ?>
          
          <div id="poste_<?= $donnee_select_postes_user['id_poste']?>" class="popup_poste">
            <div class="modification_publication">
            <div onclick="document.getElementById('poste_<?= $donnee_select_postes_user['id_poste']?>').style.display = 'none'">X</div>
              <form action="formulaires_homepage.php" method="post">
                <input type="hidden" name="id_poste" value="<?= $donnee_select_postes_user["id_poste"]?>">
                <input type="submit" name="submit_delete_post" value="delete">
              </form>
              <form action="formulaires_homepage.php" method="POST" enctype="multipart/form-data">
                
                    <label for="banniere">Modifier image poste</label>
                    <input type="hidden" name="id_poste" value="<?= $donnee_select_postes_user["id_poste"]?>">
                    <input type="file" name="banniere" value="<?= $donnee_select_postes_user["id_poste"]?>">

                    <button type="submit" name="submit_update_img_post" value="update">Enregistrer</button>
                </form>
              <form action="formulaires_homepage.php" method="post">
                <input type="hidden" name="id_poste" value="<?= $donnee_select_postes_user["id_poste"]?>">
                <input type="text" name="new_texte_poste">
                <input type="submit" name="submit_update_post" value="update">
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      <!-- Une publication -->
    </div>


      <?php
      // recupere les amis de l'utilisateur 
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
        <p>
          <h2>Liste d'amis</h2>
        </p>
          <?php 
            // affiche les amis de l'utilisateur
            foreach($donnees_select_friends as $donnee_select_friends):
            $select_info_friends = $pdo->prepare("SELECT * FROM user INNER JOIN description ON user.id_user = description.id_user WHERE user.id_user = '".$donnee_select_friends["id_contact"]."'");
            $select_info_friends->execute();
            $donnees_select_info_friends = $select_info_friends->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php foreach($donnees_select_info_friends as $donnee_select_info_friends):
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
</body>

<script src="../theme.js"></script>
<script src="homepage.js"></script>

</body>

</html>