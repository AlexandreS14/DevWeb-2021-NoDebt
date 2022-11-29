<?php
session_start();
$title = "Editer profil";
require 'inc/db_link.inc.php';
require 'php/protect.php';
require 'php/utilisateur.php';
require 'inc/header.inc.php';

if (isset($_SESSION['uid'])) {
    if (isset($_POST['buttModifier'])) {
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $protect = new protect();
            $nom = $protect->protectData($_POST['nom']);
            $prenom = $protect->protectData($_POST['prenom']);
            $email = $protect->protectData($_POST['email']);
            $password = $protect->protectData($_POST['password']);
            $cpassword = $protect->protectData($_POST['cpassword']);
            $protect->verifyEmail($email);
            // crypte le mdp
            $password = hash("sha512", $password);
            $cpassword = hash("sha512", $cpassword);
            // modifie le user
            $members = new utilisateur();
            if ($protect->identicalPassword($password, $cpassword)) {
                $members->editUser($nom, $prenom, $password, $cpassword);
                $_SESSION['editProf'] = "Votre profil a été modifié. Pour profiter pleinement des modifications, veuillez vous reconnectez";
                header('Location: groupes.php');
            }
        }
    }
}
?>
<main class="fondconnexion">
    <ul class="filArianne">
        <li class="filArianne-items">
            <a href="index.php" class="filArianne-lien">Acceuil</a>
        </li>
        <li class="filArianne-items">
            <a href="editerProfil.php" class="filArianne-lien--active">Editer profil</a>
        </li>
    </ul>
    <div class="containerIn">
        <form method="post" class="inscription">
            <h1>Editer mon profil</h1>
            </label>Prenom<input id="prenom" class="connexionbox" name="prenom" type="text" value="<?= $_SESSION['prenom']; ?>" placeholder="Prenom :">
            <label>Nom</label><input id="nom" class="connexionbox" name="nom" type="text" value="<?= $_SESSION['nom']; ?>" placeholder="Nom :">
            <label>Email*</label><input id="email" class="connexionbox" name="email" type="email" value="<?= $_SESSION['email']; ?>" required placeholder="Email* :">
            <label>Password</label><input id="password" class="connexionbox" name="password" type="password" required placeholder="Mot de passe">
            <label>Confirmation password</label><input id="cpassword" class="connexionbox" name="cpassword" type="password"
                                                       required placeholder="Confirmer mot de passe">
            <input class="buttonEnvoyer" type="submit" name="buttModifier" value="Modifier">
        </form>
        <button type="submit"><a href="supprimerProfil.php">Supprimer profil</a></button>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>
</html>