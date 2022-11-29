<?php
session_start();
$title = "Créer un groupe";
require_once 'inc/db_link.inc.php';
require_once 'php/protect.php';
require_once 'php/utilisateur.php';
require_once 'php/groupe.php';
require_once 'inc/header.inc.php';
$members = new utilisateur();
$members->user_connected();
// tableau des devises
$option = array('Euro', 'Dollar US', 'Livre Sterling');
if (isset($_POST['buttsub1'])) {
    if (isset($_POST['devise']) && !empty($_POST['nomG']) && !empty($_POST['devise']) && isset($_POST['nomG'])) {
        $protect = new protect();
        $nomG = $protect->protectData($_POST['nomG']);
        $devises = $_POST['devise'];
        $uid = $_SESSION['uid'];
        // création du groupe
        $groupe = new groupe();
        $groupe->creerGroupe($nomG, $devises, $uid);
        $_SESSION['createGr'] = "Groupe a été créé";
        header('Location: groupes.php');
    } else {
        echo "Veuillez remplir tout les champs et sélectionner une devise";
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
            <a href="creerGroupe.php" class="filArianne-lien--active">Créer Groupe</a>
        </li>
    </ul>
    <form method="post" class="editerAjouterGr">
        <h1 class="editerG">Créer un Groupe</h1>
        <label>Nom*</label><input class="caseedit" id="nomG" name="nomG" type="text" required placeholder="Nom du groupe" value="<?php if (isset($_POST['nomG'])){echo $_POST['nomG'];} ?>">
        <label>Devise*</label><select class="caseedit" id="devise" name="devise" required>
            <option value="" hidden>Choisissez une devise</option>
            <?php foreach ($option as $devises) { ?>
                <option value="<?= $devises; ?>"><?= $devises; ?></option>
            <?php } ?>
        </select>
        <input class="editer" type="submit" name="buttsub1" value="Créer">
    </form>
</main>
<?php
require("inc/footer.inc.php");
?>

</body>

</html>