<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$title = "Récupérer mot-de-passe";
require_once 'inc/db_link.inc.php';
require_once 'php/protect.php';
require_once 'inc/header.inc.php';
require_once 'php/utilisateur.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/Exception.php';
$link = \DB\DBLink::connect2db(MYDB, $message);
$protect = new protect();
$members = new Utilisateur();
$mail = new PHPMailer(true);

try {
    if (isset($_POST['buttsubEnvo'])) {
        if (isset($_POST['rec_email'])) {
            $recupEmail = $protect->protectData($_POST['rec_email']);
            $mdpRandom = uniqid();
            $objet = "Nouveau mot de passe";
            $messages = "Voici votre nouveau mot de passe : $mdpRandom";

            $mail->CharSet = 'UTF_8';
            $mail->setFrom('a.siaudeau@student.helmo.be');
            $mail->addAddress($recupEmail);
            $mail->isHTML(false);
            $mail->Subject = $objet;
            $mail->Body = $messages;

            if ($protect->existsEmail($recupEmail)) {
                $mail->send();
                $mdpRandom = hash("sha512", $mdpRandom);
                $members->recupMdp($mdpRandom, $mdpRandom, $recupEmail);
                echo "Nouveau mdp envoyé";
            } else {
                echo "Erreur lors de l'envoie, aucun compte n'est associé a cet email";
            }
        }
    }
} catch (Exception $e) {
    $message .= $e->getMessage() . '<br>';
}
?>
<main class="fondconnexion">
    <ul class="filArianne">
        <li class="filArianne-items">
            <a href="index.php" class="filArianne-lien">Se connecter</a>
        </li>
        <li class="filArianne-items">
            <a href="recupererMdp.php" class="filArianne-lien--active">Récupérer Mot-De-Passe</a>
        </li>
    </ul>
    <div class="containerIn">
        <form method="post" class="inscription">
            <h4>Mot de passe oublié ?</h4>
            <h5>Saisissez votre adresse email</h5>
            <label>Email</label><input class="connexionbox" id="rec_email" name="rec_email" type="email"
                                       placeholder="Email">
            <input class="buttonEnvoyer" type="submit" name="buttsubEnvo" value="Envoyer">
        </form>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>

</html>