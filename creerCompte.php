<?php
session_start();
$title = "Créer un compte";
require_once 'inc/db_link.inc.php';
require_once 'php/protect.php';
require_once 'php/utilisateur.php';
require_once 'inc/header.inc.php';
$message = "Nous sommes désolés, une erreur est survenue";

if (isset($_POST['buttsub1'])) {
    if (!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['cpassword'])
    && isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['cpassword'])) {
        $protect = new Protect();
        $nom = $protect->protectData($_POST['nom']);
        $prenom = $protect->protectData($_POST['prenom']);
        $email = $protect->protectData($_POST['email']);
        $password = $protect->protectData($_POST['password']);
        $cpassword = $protect->protectData($_POST['cpassword']);
        if (!$protect->existsEmail($email)) {
            echo "Un compte existe déjà avec cette adresse email ! ";
        }
        // crypte le mdp
        $password = hash("sha512", $password);
        $cpassword = hash("sha512", $cpassword);
        // insert des données de l'utilisateur dans la bdd
        $members = new utilisateur();
        $create = $members->signUser($nom, $prenom, $email, $password, $cpassword);
        if ($create) {
            $_SESSION['compteCreee'] = "Votre compte a bien été créé";
            header('Location: index.php');
        }
    } else {
        echo "Tous les champs n'ont pas été complétés";
    }
}
 ?>
<main class="fondconnexion">
    <ul class="filArianne">
        <li class="filArianne-items">
            <a href="index.php" class="filArianne-lien">Acceuil</a>
        </li>
        <li class="filArianne-items">
            <a href="creerCompte.php" class="filArianne-lien--active">Créer compte</a>
        </li>
    </ul>
    <div class="containerIn">
        <form method="post" class="inscription">
            <h1>Créer votre compte</h1>
            <label>Prénom</label><input id="prenom" class="connexionbox" name="prenom" type="text"
                                                placeholder="Prénom" value="<?php if (isset($_POST['prenom'])){echo $_POST['prenom'];} ?>">
            <label>Nom</label><input id="nom" class="connexionbox" name="nom" type="text" placeholder="Nom" value="<?php if (isset($_POST['nom'])){echo $_POST['nom'];} ?>">
            <label>Email*</label><input id="email" class="connexionbox" name="email" type="email" required
                                       placeholder="Email" value="<?php if (isset($_POST['email'])){echo $_POST['email'];} ?>">
            <label>Password*</label><input id="password" class="connexionbox" name="password" type="password" required
                                          placeholder="Mot de passe">
            <label>Confirmer password*</label><input id="cpassword" class="connexionbox" name="cpassword" type="password"
                                                    required placeholder="Confirmer mot de passe">
            <label></label><input class="buttonEnvoyer" type="submit" name="buttsub1" value="Créer">
        </form>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>

</html>