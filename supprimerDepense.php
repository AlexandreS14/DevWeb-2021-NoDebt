<?php
session_start();
$title = "Supprimer dépense";
$did = $_GET['did'];
$gid = $_GET['gid'];
require_once 'inc/header.inc.php';
require_once 'php/depense.php';
require_once 'inc/db_link.inc.php';
require_once 'php/facture.php';
$facture = new Facture();
$depense = new depense();
$dep = $depense->afficherDepEdit($did);
$scan = $facture->getScanBYDid($did);
if (isset($_POST['buttsub1Non'])) {
    header('Location: consulterGroupeXXX.php?gid='.$gid);
} elseif (isset($_POST['buttsub1Oui']) && isset($did)) {
    $depense->deleteDepense($did);
    $_SESSION['delDep'] = "Dépense a été supprimé";
    header('Location: consulterGroupeXXX.php?gid='.$gid);
}

if (isset($_POST['buttScanNon'])) {
    header('Location: consulterGroupeXXX.php?gid='.$gid);
} elseif (isset($_POST['buttScanOui'])) {
    $scans = $_POST['scans'];
    $getScan = $facture->getScanByFid($scans);
    $facture->deleteScan($getScan['fid']);
    unlink("../uploads/".$scan['scan']);
    $_SESSION['delScan'] = "Scan a été supprimé";
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
            <a href="consulterGroupeXXX.php?gid=<?= $gid ?>" class="filArianne-lien">Consulter groupe Restaurant</a>
        </li>
        <li class="filArianne-items">
            <a href="supprimerDepense.php?gid=<?= $gid ?> &&did= <?= $did ?>" class="filArianne-lien--active">Supprimer dépense</a>
        </li>
    </ul>
    <section class="containerIn">
        <form method="post" class="addDep">
            <h1 class="titreAjDep">Supprimer une dépense</h1>
            <h2>Êtes-vous sûr de vouloir supprimer la dépense de : <?= $dep['membre'] ?></h2>
            <h3>Tag : <?= $dep['tag'] ?></h3>
            <input type="submit" name="buttsub1Oui" value="Oui" class="butValider">
            <input type="submit" name="buttsub1Non" value="Non" class="butRefuser">
        </form>
        <form method="post" class="addDep">
            <h1 class="titreAjDep">Supprimer un scan de la dépense de : <?= $dep['membre'] ?></h1>
            <h2>Tag : <?= $dep['tag'] ?></h2>
            <select class="caseedit" id="scans" name="scans">
                <?php foreach ($scan as $scans): ?>
                    <option value="<?= $scans['scan']; ?>"><?= $scans['scan']; ?></option>
                <?php endforeach; ?>
            </select>
            <h2 > Êtes - vous sûr de vouloir supprimer ce scan </h2 >
            <input type = "submit" name = "buttScanOui" value = "Oui" class="butValider" >
            <input type = "submit" name = "buttScanNon" value = "Non" class="butRefuser" >
        </form>
    </section>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>

</html>