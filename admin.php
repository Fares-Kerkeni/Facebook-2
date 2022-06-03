<?php
  include 'connexion_bdd.php';
  include 'deco.php';
  session_start();
  deconnexion();

if(isset($_GET['type']) AND $_GET['type'] == 'membre') {  
    if(isset($_GET['supprime']) AND !empty($_GET['supprime'])) {
        $supprime = $_GET['supprime'];

        $req = $pdo->prepare('DELETE FROM contacts WHERE id_user = ?');
        $req->execute(array($supprime));
        $req = $pdo->prepare('DELETE FROM user WHERE id_user = ?');
        $req->execute(array($supprime));
    }
} elseif(isset($_GET['type']) AND $_GET['type'] == 'commentaire') {
    if(isset($_GET['supprime']) AND !empty($_GET['supprime'])) {
        $supprime = $_GET['supprime'];

        $req = $pdo->prepare('DELETE FROM commentaire WHERE id_user = ?');
        $req->execute(array($supprime));
    }
}

$user = $pdo->query('SELECT * FROM user');
$commentaire = $pdo->query('SELECT * FROM commentaire')
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration</title>
</head>
<body>
    <ul>
        <?php while($u = $user->fetch()) { ?>
        <li><?= $u['id_user'] ?> : <?= $u['pseudo'] ?> - <a href="admin.php?type=membre&supprime=<?= $u['id_user'] ?>">Supprimer</a></li>
        <?php } ?> 
    </ul>

    <br><br>

    <ul>
        <?php while($c = $commentaire->fetch()) { ?>
        <li><?= $c['id_user'] ?> : <?= $c['pseudo'] ?> : <?= $c['commentaire'] ?> - <a href="admin.php?type=commentaire&supprime=<?= $c['id_user'] ?>">Supprimer</a></li>
        <?php } ?> 
    </ul>
</body>
</html>