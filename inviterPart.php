<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$title = "Inviter un participant";
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require_once 'inc/db_link.inc.php';
require_once 'inc/header.inc.php';
require_once 'php/utilisateur.php';
require_once 'php/groupe.php';
require_once 'php/protect.php';
require_once 'php/participe.php';
$link = \DB\DBLink::connect2db(MYDB, $message);
$members = new Utilisateur();
$groupes = new Groupe();
$protect = new protect();
$participe = new Participer();
$members->user_connected();
$gid = $_GET['gid'];
$mail = new PHPMailer(true);

try {
    if (isset($_POST['buttsubEnv'])) {
        if (isset($_POST['invEmail']) && !empty($_POST['invEmail'])) {
            $protect->verifyEmail($_POST['invEmail']);
            $email = $_POST['invEmail'];
            $dataMail = array($_SESSION['email'], $email);
            $uidPart = $members->selectUidByEmail($_POST['invEmail']);
            $uidPart = implode("", $uidPart);
            $inviteur = $_SESSION['prenom'] . " " . $_SESSION['nom'];
            $objet = "Invitation de la part de $inviteur";
            $messages = "Vous avez reçu une nouvelle invitation pour rejoindre un groupe
                         Clique sur ce lien http://192.168.128.13/~q210028/EVAL_V4/index.php pour consulter tes invitations";

            $mail->CharSet = 'UTF_8';
            $mail->setFrom($_SESSION['email']);
            foreach ($dataMail as $emailDate) {
                $mail->addAddress($_POST['invEmail']);
            }
            $mail->isHTML(false);
            $mail->Subject = $objet;
            $mail->Body = $messages;
            if ($uidPart != $_SESSION['uid']) {
                if ($participe->AddParticiper($uidPart, $gid, $estConfirmee = 0) && $mail->send()) {
                    $_SESSION['invPart'] = "L'invitation a été envoyé";
                    header('Location: consulterGroupeXXX.php?gid='.$gid);
                }
            }
            if ($uidPart == $_SESSION['uid']) {
                echo "Vous ne pouvez pas vous invitez vous-mêmes à un groupe";
            } else {
                echo "Une erreur est survenue lors de l'envoie de l'email";
            }
        } else {
            echo "Veuillez inséré un email";
        }
    }
} catch (Exception $e) {
    $message .= $e->getMessage() . '<br>';
}
?>
<main class="fondconnexion">
    <ul class="filArianne">
        <li class="filArianne-items">
            <a href="index.php" class="filArianne-lien">Acceuil</a>
        </li>
        <li class="filArianne-items">
            <a href="groupes.php" class="filArianne-lien">Groupes</a>
        </li>
        <li class="filArianne-items">
            <a href="consulterGroupeXXX.php?gid=<?= $gid ?>" class="filArianne-lien">Consulter groupe</a>
        </li>
        <li class="filArianne-items">
            <a href="inviterPart.php" class="filArianne-lien--active">Inviter participant</a>
        </li>
    </ul>
    <div class="containerIn">
        <form method="post" class="inscription">
            <h1>Inviter un participant</h1>
            <label>Email</label><input class="connexionbox" id="invEmail" name="invEmail" type="email"
                                       placeholder="E-mail">
            <input class="buttonEnvoyer" type="submit" name="buttsubEnv" value="Envoyer">
        </form>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>

</html>
