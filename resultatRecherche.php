<?php
session_start();
require_once 'inc/db_link.inc.php';
require_once 'php/protect.php';
require_once 'php/utilisateur.php';
require_once 'php/groupe.php';
require_once 'php/depense.php';
require_once 'php/facture.php';
require_once 'inc/header.inc.php';
$members = new Utilisateur();
$groupes = new Groupe();
$depense = new depense();
$protect = new protect();
$facture = new Facture();
$members->user_connected();
$gid = $_GET['gid'];
$group = $groupes->consulter($gid);
$recap = $depense->tableauRecap($gid);
$creator = $members->selectCreator($gid);
$devise = "";
if ($group['devise'] == "Euro") {
    $devise = "€";
} elseif ($group['devise'] == "Dollar US") {
    $devise = "$";
} else {
    $devise = "£";
}
$recherche = $protect->protectData($_GET['recherche']);
$montant_min = $protect->protectData($_GET['Min']);
$montant_max = $protect->protectData($_GET['Max']);
$date_debut = $protect->protectData($_GET['dateDebut']);
$date_fin = $protect->protectData($_GET['dateFin']);
$libelle = $protect->protectData($_GET['libelle']);
$tag = $protect->protectData($_GET['tags']);
if (empty($date_debut) || empty($date_fin) || empty($libelle) || empty($tag) || empty($montant_min) || empty($montant_max)) {
    if (!empty($recherche)) {
        setcookie("recherche", $recherche, time() + (3600 * 24 * 30), '/~q210028/');
        $filter = $depense->filter($gid, $recherche);
    }
} else {
    if (empty($recherche)) {
        setcookie("Min", $montant_min, time() + (3600 * 24 * 30), '/~q210028/');
        setcookie("Max", $montant_max, time() + (3600 * 24 * 30), '/~q210028/');
        setcookie("dateDebut", $date_debut, time() + (3600 * 24 * 30), '/~q210028/');
        setcookie("dateFin", $date_fin, time() + (3600 * 24 * 30), '/~q210028/');
        setcookie("libelle", $libelle, time() + (3600 * 24 * 30), '/~q210028/');
        setcookie("tags", $tag, time() + (3600 * 24 * 30), '/~q210028/');
        $filter = $depense->filterAvance($gid, $montant_min, $montant_max, $date_debut, $date_fin, $libelle, $tag);
    } elseif (empty($montant_min) || empty($montant_max) || empty($libelle) || empty($tag)) {
        setcookie("dateDebut", $date_debut, time() + (3600 * 24 * 30), '/~q210028/');
        setcookie("dateFin", $date_fin, time() + (3600 * 24 * 30), '/~q210028/');
        $filter = $depense->filterByDate($gid, $date_debut, $date_fin);
    } elseif (empty($date_debut) || empty($date_fin) || empty($libelle) || empty($tag)) {
        setcookie("Min", $montant_min, time() + (3600 * 24 * 30), '/~q210028/');
        setcookie("Max", $montant_max, time() + (3600 * 24 * 30), '/~q210028/');
        $filter = $depense->filterByMontant($gid, $montant_min, $montant_max);
    } elseif (empty($libelle) || empty($tag)) {
        setcookie("Min", $montant_min, time() + (3600 * 24 * 30), '/~q210028/');
        setcookie("Max", $montant_max, time() + (3600 * 24 * 30), '/~q210028/');
        setcookie("dateDebut", $date_debut, time() + (3600 * 24 * 30), '/~q210028/');
        setcookie("dateFin", $date_fin, time() + (3600 * 24 * 30), '/~q210028/');
        $filter = $depense->filterDateMontant($gid, $montant_min, $montant_max, $date_debut, $date_fin);
    }
}
?>
<main>
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
            <a href="resultatRecherche.php?gid=<?= $gid ?>" class="filArianne-lien--active">Consulter recherche</a>
        </li>
    </ul>
    <section class="titreG">
        <h3>Groupe : <?= $group['nom']; ?></h3>
        <h6>Créateur : <?= $creator['prenom'] ?></h6>
        <h6>Devise : <?= $group['devise'] ?></h6>
    </section>
    <table class="tableau">
        <thead>
        <tr>
            <th>Tags</th>
            <th>Membre</th>
            <th>Libellé</th>
            <th>Date</th>
            <th>Scan</th>
            <th>Prix</th>
            <th>Opération</th>
        </tr>
        </thead>
        <tbody>
        <?php if (empty($filter)) {
            $filter = array(); ?>
            <tr>
                <td colspan="7">Aucune dépense trouvée</td>
            </tr>
        <?php }
        foreach ($filter as $result): ?>
            <tr>
                <td><?= $result['tag'] ?></td>
                <td><?= $result['membre'] ?></td>
                <td><?= $result['libelle'] ?></td>
                <td><?= $result['date'] ?></td>
                <td><?php $scan = $facture->selectScan($result['did']); ?></td>
                <td><?= $result['montant'] . ' ' . $devise ?></td>
                <td>
                    <button type="submit" class="butedit"><a
                                href="editerDepense.php?did=<?= $result['did'] ?> &&gid=<?= $result['gid'] ?>">Editer</a>
                    </button>
                    <button type="submit" class="butedit"><a
                                href="ajouterScan.php?did=<?= $result['did'] ?> &&gid=<?= $result['gid'] ?>">Add
                            Scan</a>
                    </button>
                    <button type="submit" class="butSup"><a
                                href="supprimerDepense.php?did=<?= $result['did'] ?> &&gid=<?= $result['gid'] ?> &&fid=<?= $fact = $facture->getScanBYDid($result['did']); ?>">Supprimer</a>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>

</html>