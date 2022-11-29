<?php
session_start();
$title = "Supprimer groupe";
$gid = $_GET['gid'];
require_once 'inc/header.inc.php';
require_once 'php/groupe.php';
require_once 'php/depense.php';
require_once 'inc/db_link.inc.php';
$groupes = new Groupe();
$depense = new depense();
$recap = $depense->tableauRecap($gid);
$group = $groupes->consulter($gid);
if (isset($_POST['buttsubGOui']) && $recap == null) {
    $deleteG = $groupes->deleteGroup($gid);
    $_SESSION['deleGroupe'] = "Le groupe ".$group['nom']." a été supprimé";
    header('Location: groupes.php');
} else {
    echo "!! Sachez qu'il est impossible de supprimer un groupe non soldé ou dont tous les versements ne sont pas confirmés !!";
}
if (isset($_POST['buttsubGNon'])) {
    header('Location: groupes.php');
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
            <a href="consulterGroupeXXX.php?gid=<?= $gid ?>" class="filArianne-lien">Consulter groupe Restaurant</a>
        </li>
        <li class="filArianne-items">
            <a href="supprimerGroupe.php?gid=<?= $gid ?>" class="filArianne-lien--active">Supprimer dépense</a>
        </li>
    </ul>
    <section class="containerIn">
        <form method="post" class="addDep">
            <h1 class="titreAjDep">Supprimer groupe</h1>
            <h2>Êtes-vous sûr de vouloir supprimer le groupe : <?= $group['nom'] ?></h2>
            <input type="submit" name="buttsubGOui" value="Oui" class="butValider">
            <input type="submit" name="buttsubGNon" value="Non" class="butRefuser">
        </form>
    </section>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>

</html>