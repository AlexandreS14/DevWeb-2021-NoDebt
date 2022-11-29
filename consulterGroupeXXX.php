<?php
session_start();
$title = "Consulter mes groupes";
require_once 'inc/db_link.inc.php';
require_once 'php/protect.php';
require_once 'php/utilisateur.php';
require_once 'php/groupe.php';
require_once 'php/depense.php';
require_once 'php/facture.php';
require_once 'php/participe.php';
require_once 'inc/header.inc.php';
require_once 'php/versement.php';
$link = \DB\DBLink::connect2db(MYDB, $message);
$members = new Utilisateur();
$groupes = new Groupe();
$depense = new depense();
$protect = new protect();
$facture = new Facture();
$participe = new Participer();
$members->user_connected();
$gid = $_GET['gid'];
$group = $groupes->consulter($gid);
$recap = $depense->tableauRecap($gid);
$creator = $members->selectCreator($gid);
$mem = $members->selectAllMembersG($gid);
$devise = "";
if ($group['devise'] == "Euro") {
    $devise = "€";
} elseif ($group['devise'] == "Dollar US") {
    $devise = "$";
} else {
    $devise = "£";
}
if (isset($_SESSION['addDep'])) { echo $_SESSION['addDep']; unset($_SESSION['addDep']);
} elseif (isset($_SESSION['addScan'])) { echo $_SESSION['addScan']; unset($_SESSION['addScan']);
} elseif (isset($_SESSION['editDep'])) { echo $_SESSION['editDep']; unset($_SESSION['editDep']);
} elseif (isset($_SESSION['delDep'])) { echo $_SESSION['delDep']; unset($_SESSION['delDep']);
} elseif (isset($_SESSION['delScan'])) { echo $_SESSION['delScan']; unset($_SESSION['delScan']);
} elseif (isset($_SESSION['invPart'])) { echo $_SESSION['invPart']; unset($_SESSION['invPart']);
} elseif (isset($_SESSION['editGroupe'])) { echo $_SESSION['editGroupe']; unset($_SESSION['editGroupe']);} ?>
<main class="men">
    <ul class="filArianne">
        <li class="filArianne-items">
            <a href="index.php" class="filArianne-lien">Acceuil</a>
        </li>
        <li class="filArianne-items">
            <a href="groupes.php" class="filArianne-lien">Groupes</a>
        </li>
        <li class="filArianne-items">
            <a href="consulterGroupeXXX.php?gid=<?= $gid ?>" class="filArianne-lien--active">Consulter groupe</a>
        </li>
    </ul>
    <section class="titreG">
        <h3>Groupe : <?= $group['nom']; ?></h3>
        <h6>Créateur : <?= $creator['prenom'] ?></h6>
        <h6>Devise : <?= $group['devise'] ?></h6>
    </section>
    <section class="menuGroupe">
        <ul>
            <li><a href="ajouterDepense.php?gid=<?= $gid ?>">Ajouter dépense</a></li>
        </ul>
        <ul>
            <li><a href="inviterPart.php?gid=<?= $gid ?>">Inviter participant</a></li>
        </ul>
        <ul>
            <li><a href="consulterVersement.php?gid=<?= $gid ?>">Consulter versement</a></li>
        </ul>
        <ul>
            <li><a href="editerGroupe.php?gid=<?= $gid ?>">Editer groupe</a></li>
        </ul>
        <ul>
            <li><a href="supprimerGroupe.php?gid=<?= $gid ?>">Supprimer groupe</a></li>
        </ul>
    </section>
    <h1 class="tabGroupe">Tableau des dépenses</h1>
    <form method="get" class="titreR" action="resultatRecherche.php">
        <input type="hidden" name="gid" value="<?= $gid ?>">
        <input class="rechercheBut" id="recherche" name="recherche" type="search" placeholder="Recherche ..." value="<?php if (isset($_COOKIE['recherche'])) {echo $_COOKIE['recherche'];} ?>">
        <input type="submit" name="buttonSearch" class="navSearch" value="rechercher">
        <details class="rechercheAv">
            <summary>Recherche avancée</summary>
            <label for="montantMinimum">Montant min</label><input id="montantMinimum" min="0" step="0.001" name="Min" type="number" placeholder="0" value="<?php if (isset($_COOKIE['Min'])) {echo $_COOKIE['Min'];} ?>">
            <label for="montantMaximum">Montant max</label><input id="montantMaximum" min="0" step="0.001" name="Max" type="number" placeholder="0" value="<?php if (isset($_COOKIE['Max'])) {echo $_COOKIE['Max'];} ?>">
            <label for="dateDebut">Date début</label><input id="dateDebut" name="dateDebut" type="date" value="<?php if (isset($_COOKIE['dateDebut'])) {echo $_COOKIE['dateDebut'];} ?>">
            <label for="dateFin">Date fin</label><input id="dateFin" name="dateFin" type="date" value="<?php if (isset($_COOKIE['dateFin'])) {echo $_COOKIE['dateFin'];} ?>">
            <label for="libelles">Libellé</label><input id="libelles" name="libelle" type="text" placeholder="Libellé" value="<?php if (isset($_COOKIE['libelle'])) {echo $_COOKIE['libelle'];} ?>">
            <label for="tags">Tags</label><input id="tags" name="tags" type="text" placeholder="Tags" value="<?php if (isset($_COOKIE['tags'])) {echo $_COOKIE['tags'];} ?>">
            <input type="submit" name="buttonSearchAv" class="navSearch" value="rechercher avancée">
        </details>
    </form>
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
        <?php
            $total = 0;
            foreach ($recap as $tabRecap): ?>
                <tr>
                    <td><?= $tabRecap['tag'] ?></td>
                    <td><?= $tabRecap['membre'] ?></td>
                    <td><?= $tabRecap['libelle'] ?></td>
                    <td><?= $tabRecap['date'] ?></td>
                    <td><?php $scan = $facture->selectScan($tabRecap['did']); ?></td>
                    <td><?= $tabRecap['montant'].' '.$devise ?></td>
                    <td>
                        <button type="submit" class="butedit"><a href="editerDepense.php?did=<?= $tabRecap['did'] ?> &&gid=<?= $tabRecap['gid'] ?>">Editer</a></button>
                        <button type="submit" class="butedit"><a href="ajouterScan.php?did=<?= $tabRecap['did'] ?> &&gid=<?= $tabRecap['gid'] ?>">Add Scan</a></button>
                        <button type="submit" class="butSup"><a href="supprimerDepense.php?did=<?= $tabRecap['did'] ?> &&gid=<?= $tabRecap['gid'] ?>">Supprimer</a></button>
                    </td>
                </tr>
                <?php
                $total += $tabRecap['montant'];
            endforeach;
            $t = sizeof($mem);
            $moyenne = round($total / $t, 3);
        ?>
        </tbody>
    </table>
    <form method="post" class="containerG">
        <h1 class="titreRecap">Solde des comptes</h1>
        <button type="submit" class="buttonSolder" name="solder"><a href="refuserSolder.php?gid=<?= $gid ?>">Solder</a>
        </button>
        <?php foreach ($mem as $mems): ?>
            <section class="containersec">
                <ul>
                    <li>
                        <h2><?= $mems['prenom'] . ' ' . $mems['nom'] ?></h2>
                        <p><span>Total du Groupe : </span><?= $total.' '.$devise; ?></p>
                        <p><span>Moyenne par participant : </span><?= $moyenne.' '.$devise; ?></p>
                        <p class="barre"><span>Total par personne : </span><?php
                            $a = $depense->depParId($gid, $mems['uid']);
                            foreach ($a as $as):
                                if ($as == null) {
                                    echo $depParPers = "0 ".$devise;
                                } else {
                                    echo $depParPers = $as.' '.$devise;
                                };
                            endforeach;
                            $debit = 0;
                            $credit = 0;
                            ?></p>
                        <?php if ($depParPers - $moyenne < 0): ?>
                            <p class="aPayer">
                                <span>Ecart (A payer) : <?php $debit = $depParPers - $moyenne.' '.$devise;
                                                                echo $debit; $_SESSION['debit'] = $debit; ?></span></p>
                        <?php else: ?>
                            <p class="aRecup">
                                <span>Ecart (A récupérer) : <?php $credit = $depParPers - $moyenne.' '.$devise;
                                                                echo $credit; $_SESSION['credit'] = $credit; ?></span></p>
                        <?php endif; ?>
                    </li>
                </ul>
            </section>
        <?php endforeach; ?>
    </form>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>

</html>