<?php
session_start();
require_once 'inc/db_link.inc.php';
require_once 'php/protect.php';
require_once 'php/utilisateur.php';
$title = "Connexion";
require_once 'inc/header.inc.php';
$members = new utilisateur();
$protect = new protect();
$members->user_is_connected();
if (isset($_SESSION['compteCreee'])) {echo $_SESSION['compteCreee']; unset($_SESSION['compteCreee']); }
if (isset($_POST['buttsub1'])) {
    if (isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty('password')) {
        $email = $protect->protectData($_POST['email']);
        $protect->verifyEmail($email);
        $password = $protect->protectData($_POST['password']);
        // crypte le mdp
        $password = hash("sha512", $password);
        // authentication du user
        $members->authUser($email, $password);
        header('Location: groupes.php');
    } else {
        echo "Veuillez remplir tous les champs";
    }
}
?>
<main class="fondconnexion">
    <h1 class="bienvenue-titre">Bienvenue sur NoDebt</h1>
    <div class="containerCo">
        <form method="post" class="connexion">
            <h1>Connectez - Vous !</h1>
            <label>E-mail</label><input id="email" name="email" type="email" class="connexionbox" placeholder="Email : ">
            <label>Mot-de-passe</label><input id="password" name="password" type="password" class="connexionbox" placeholder="Mot de passe :">
            <input class="buttonEnvoyer" type="submit" name="buttsub1" id="buttsub1" value="Connexion">
            <p class="mdpoub"><a href="recupererMdp.php">Mot de passe oublié ?</a></p>
            <p class="mdpoub"><a href="creerCompte.php">Vous n'avez pas encore créez un compte ? Faite le
                    maintenant.</a></p>
        </form>
    </div>
</main>
<?php require("inc/footer.inc.php"); ?>
</body>

</html>