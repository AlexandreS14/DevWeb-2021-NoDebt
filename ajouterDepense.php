<?php
session_start();
$title = "Ajouter une dépense";
require 'inc/db_link.inc.php';
require 'php/protect.php';
require 'php/utilisateur.php';
require 'php/groupe.php';
require 'php/depense.php';
require 'inc/header.inc.php';
$members = new Utilisateur();
$groupes = new Groupe();
$depense = new depense();
$protect = new protect();
$members->user_connected();
$gid = $_GET['gid'];
$participant = $members->selectAllMembersG($gid);

if (isset($_POST['buttsub1'])) {
    if (isset($_POST['date']) && isset($_POST['montant']) && isset($_POST['libelle']) && !empty($_POST['date'])
        && !empty($_POST['montant']) && !empty($_POST['libelle'])) {
        $tag = $protect->protectData($_POST['tags']);
        $participants = $protect->protectData($_POST['participant']);
        $date = $protect->protectData($_POST['date']);
        $montant = $protect->protectData(doubleval($_POST['montant']));
        $libelle = $protect->protectData($_POST['libelle']);
        $uid = $members->selectUidByName($participants);
        $add = $depense->addDepense($tag, $participants, $date, $montant, $libelle, $gid, $uid['uid']);
        if ($add) {
            $_SESSION['addDep'] = "Dépense ajoutée";
            header('Location: consulterGroupeXXX.php?gid='.$gid);
        } else {
            echo "Une erreur est survenue lors de l'ajout de la dépense, veuillez réessayer";
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
            <a href="consulterGroupeXXX.php?gid=<?= $gid ?>" class="filArianne-lien">Consulter groupe</a>
        </li>
        <li class="filArianne-items">
            <a href="ajouterDepense.php?gid=<?= $gid ?>" class="filArianne-lien--active">Ajouter dépense</a>
        </li>
    </ul>
    <div class="containerAd">
        <form method="post" class="addDep">
            <h1>Ajouter une dépense</h1>
            <label>Tag</label><input id="tags" name="tags" type="text" placeholder="Tags" value="<?php if (isset($tag)) {echo $tag;} ?>">
            <label>Participant*</label><select class="caseedit" id="participant" name="participant" required>
                <option value="" hidden>Choisissez un participant</option>
                <?php foreach ($participant as $participants) { ?>
                    <option value="<?= $participants['nom']; ?>"><?= $participants['nom']; ?></option>
                <?php } ?>
            </select>
            <label>Libellé*</label><input id="libelle" name="libelle" type="text" required placeholder="Libellé*" value="<?php if (isset($libelle) && !empty($libelle)) {echo $libelle;} ?>">
            <label>Date*</label><input id="date" name="date" type="date" value="<?php if (!isset($date)) { echo date('Y-m-d');} else {echo $date;} ?>">
            <label>Montant*</label><input id="montant" name="montant" type="number" step="0.01" required placeholder="Montant" value="<?php if (isset($montant)) {echo $montant;} ?>">
            <input class="butValider" type="submit" name="buttsub1" value="Valider">
        </form>
    </div>
</main>
<?php
require("inc/footer.inc.php");
?>

</body>

</html>