<?php
session_start();
$title = "Supprimer profil";
require 'inc/db_link.inc.php';
require 'php/protect.php';
require_once 'php/groupe.php';
require_once 'php/depense.php';
require 'php/utilisateur.php';
require 'inc/header.inc.php';
if (isset($_POST['buttProfilNon'])) {
    header('Location: groupes.php');
}
if (isset($_POST['buttProfilOui'])) {
    $_SESSION['deleProfil'] = "Votre profil a été supprimé, il vous est donc impossible de vous reconnecter avec ce profil sans le recréer";
    session_destroy();
    header('Location: index.php');
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
            <a href="editerProfil.php" class="filArianne-lien">Consulter groupe Restaurant</a>
        </li>
        <li class="filArianne-items">
            <a href="supprimerProfil.php" class="filArianne-lien--active">Supprimer dépense</a>
        </li>
    </ul>
    <section class="containerIn">
        <form method="post" class="addDep">
            <h1 class="titreAjDep">Supprimer profil</h1>
            <h2>Êtes-vous sûr de vouloir supprimer votre profil</h2>
            <input type="submit" name="buttProfilOui" value="Oui" class="butValider">
            <input type="submit" name="buttProfilNon" value="Non" class="butRefuser">
        </form>
    </section>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>

</html>