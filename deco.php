<?php
    function deconnexion(){
        if (isset($_POST['submit_deconnexion'])) {
            $_SESSION = array();
            session_destroy();
            unset($_SESSION);
            header('Location: ../landing_page/landing_page.php');
        }elseif (isset($_POST['submit_profil'])){
            header('Location: ../profil/profil.php');
        }
    };
    
?>