<?php
session_start();
$title = "Editer un groupe";
require 'inc/db_link.inc.php';
require 'php/protect.php';
require 'php/utilisateur.php';
require 'php/groupe.php';
require 'inc/header.inc.php';
$members = new utilisateur();
$groupes = new Groupe();
$protect = new protect();
$link = \DB\DBLink::connect2db(MYDB, $message);
$members->user_connected();
$gid = $_GET['gid'];
$group = $groupes->consulter($gid);
// tableau des devises
$option = array('Euro', 'Dollar US', 'Livre Sterling');
if (isset($_POST['buttsub1'])) {
    if (isset($_POST['nomG']) && !empty($_POST['nomG']) && isset($_POST['devise']) && !empty($_POST['devise'])) {
        $nomG = $protect->protectData($_POST['nomG']);
        $devises = $protect->protectData($_POST['devise']);
        $modif = $groupes->editGroupe($nomG, $devises, $gid);
        $_SESSION['editGroupe'] = "Le groupe a été modifié";
        header("Location: consulterGroupeXXX.php?gid=".$gid);
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
            <a href="editerGroupe.php?gid=<?= $gid ?>" class="filArianne-lien--active">Editer groupe</a>
        </li>
    </ul>
    <form method="post" class="editerAjouterGr">
        <h1 class="editerG">Editer Groupe</h1>
        <label>Nom</label><input class="caseedit" id="nomG" name="nomG" type="text" required placeholder="Nom :" value="<?= $group['nom'] ?>">
        <label>Devise</label><select class="caseedit" id="devise" name="devise">
            <?php foreach ($option as $devises) { ?>
                <option value="<?= $devises ?>"><?=$devises ?></option>
            <?php } ?>
        </select>
        <input class="editer" type="submit" name="buttsub1" value="Editer">
    </form>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>

</html>

