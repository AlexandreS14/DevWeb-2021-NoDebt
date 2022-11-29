<?php
session_start();
$did = $_GET['did'];
$gid = $_GET['gid'];
$title = "Editer dépense";
require_once 'inc/header.inc.php';
require_once 'php/depense.php';
require 'php/utilisateur.php';
require_once 'inc/db_link.inc.php';
require_once 'php/protect.php';
require 'php/facture.php';
$depense = new depense();
$members = new Utilisateur();
$protect = new protect();
$dep = $depense->afficherDepEdit($did);
$participant = $members->selectAllMembersG($gid);

if (isset($_POST['buttsub1Edit'])) {
    if (isset($_POST['montant']) && isset($_POST['libelle']) && isset($_POST['tag']) && isset($_POST['participant'])
        && !empty($_POST['montant']) && !empty($_POST['libelle']) && !empty($_POST['tag']) && !empty($_POST['participant'])) {
        $montant = $protect->protectData(doubleval($_POST['montant']));
        $libelle = $protect->protectData($_POST['libelle']);
        $tag = $protect->protectData($_POST['tag']);
        $participants = $protect->protectData($_POST['participant']);
        $uid = $members->selectUidByName($participants);

        $update = $depense->updateDepense($montant, $libelle, $tag, $participants, $did, $uid['uid']);
        if ($update) {
            $_SESSION['editDep'] = "Dépense mise à jour";
            header('Location: consulterGroupeXXX.php?gid='.$gid);
        }
    } else {
        echo "Veuillez remplir tout les champs";
    }
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
            <a href="editerDepense.php?gid=<?= $gid ?> &&did= <?= $did ?>" class="filArianne-lien--active">Editer dépense</a>
        </li>
    </ul>
    <div class="containerAd">
        <form method="post" class="addDep">
            <h1>Editer la dépense de : <?= $dep['membre'] ?></h1>
            <label>Montant</label><input id="montant" name="montant" type="number" required placeholder="Montant :" value="<?= $dep['montant']; ?>">
            <label>Libellé</label><input id="libelle" name="libelle" type="text" required placeholder="libelle :" value="<?= $dep['libelle']; ?>">
            <label>Tag</label><input id="tag" name="tag" type="text" required placeholder="tag :" value="<?= $dep['tag']; ?>">
            <label>Participant</label><select class="caseedit" id="participant" name="participant" placeholder="Participant :">
                <?php foreach ($participant as $participants): ?>
                    <option value="<?= $participants['prenom'] ;?>"><?= $participants['prenom']  ;?></option>
                <?php endforeach; ?>
            </select>
            <input class="butValider" type="submit" name="buttsub1Edit" value="Valider">
        </form>
    </div>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>

</html>