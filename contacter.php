<?php
session_start();
$title = "Contact";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'php/protect.php';
require_once 'inc/header.inc.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/Exception.php';
require_once 'php/utilisateur.php';

$user = new Utilisateur();
$mail = new PHPMailer(true);

try {
    if (isset($_POST['envoyer'])) {
        if (isset($_POST['objet']) && isset($_POST['email']) && isset($_POST['message'])) {
            $protect = new protect();
            if (isset($_SESSION['uid'])) {
                $objet = $protect->protectData($_POST['objet']).' '.$_SESSION['prenom'].' '.$_SESSION['nom'];
            } else {
                $objet = $protect->protectData($_POST['objet']);
            }
            $email = $protect->protectData($_POST['email']);
            $message = $protect->protectData($_POST['message']);
            $protect->verifyEmail($email);
            $dataMail = array('a.siaudeau@student.helmo.be', $email);

            $mail->CharSet = 'UTF_8';
            $mail->setFrom($email);
            foreach ($dataMail as $emailDate) {
                $mail->addAddress($emailDate);
            }
            $mail->isHTML(false);
            $mail->Subject = $objet;
            $mail->Body = $message;

            if ($mail->send()) {
                echo "Nous avons reçu votre message et nous y travaillons.";
            }
        } else {
            echo "Veuillez remplir tout les champs";
        }
    }
} catch (Exception $e) {
    $message .= $e->getMessage() . '<br>';
}
$send = 'Erreur survenue lors de l\'envoie de l\'email' . $mail->ErrorInfo;
?>
<main class="contactIm">
    <ul class="filArianne">
        <li class="filArianne-items">
            <a href="index.php" class="filArianne-lien">Acceuil</a>
        </li>
        <li class="filArianne-items">
            <a href="contacter.php" class="filArianne-lien--active">Nous contacter</a>
        </li>
    </ul>
    <div class="containerCa">
        <h1>Contactez - Nous !</h1>
        <form method="post" class="form">
            <label>Objet</label><input type="text" class="contactNous" name="objet" required
                                       placeholder="Objet du message*" value="<?php if (isset($_POST['objet'])) {echo $_POST['objet'];} ?>">
            <label>Email</label><input type="email" class="contactNous" name="email" value="<?php if ($user->is_connected()) { echo $_SESSION['email']; } ?>" required placeholder="Email :*">
            <label>Message</label><textarea class="contactNous textareacontactNous" name="message" rows="8" cols="21" required placeholder="Message*"><?php if (isset($_POST['message'])) {echo $_POST['message'];} ?></textarea>
            <input class="buttonEnvoyer" type="submit" name="envoyer" value="Envoyer">
        </form>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>
</html>