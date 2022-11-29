<?php
session_start();
$title = "Solder Groupe";
require_once 'inc/header.inc.php';
require_once 'php/groupe.php';
require_once 'php/depense.php';
require_once 'inc/db_link.inc.php';
require_once 'php/versement.php';
$versement = new Versement();
$groupes = new Groupe();
$gid = $_GET['gid'];
$group = $groupes->consulter($gid);
$date = date('Y-m-d');

if (isset($_POST['buttsubRefuserYesSolder'])) {
    if (abs($_SESSION['debit']) == abs($_SESSION['credit'])) {
        $addV = $versement->addVersement($gid, $_SESSION['uid'], $_SESSION['uid'], $date, $_SESSION['debit'], 0);
    } elseif ($_SESSION['debit'] == 0 && $_SESSION['credit'] == 0) {} else {
        $maxDebit = max(array($_SESSION['debit'])) . '<br>';
        $maxCredit = max(array($_SESSION['credit'])) . '<br>';
        $minBoth = min($maxDebit, $maxCredit);
    }
    header('Location: consulterVersement.php?gid='.$gid);
}
if (isset($_POST['buttsubRefuserNoSolder'])) {
    $versement->deleteVersement($gid);
    header('Location: consulterGroupeXXX.php?gid='.$gid);
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
            <a href="consulterGroupeXXX.php?gid=<?= $gid ?>" class="filArianne-lien">Consulter Groupe</a>
        </li>
        <li class="filArianne-items">
            <a href="refuserSolder.php?gid=<?= $gid ?>" class="filArianne-lien--active">Confirmation Solder</a>
        </li>
    </ul>
    <section class="containerIn">
        <form method="post" class="addDep">
            <h1 class="titreAjDep">Solder Groupe</h1>
            <h2>Êtes-vous sûr de vouloir solder le groupe : <?= $group['nom']; ?></h2>
            <input type="submit" name="buttsubRefuserYesSolder" value="Oui" class="butValider">
            <input type="submit" name="buttsubRefuserNoSolder" value="Non" class="butRefuser">
        </form>
    </section>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>

</html>
