<?php
session_start();
$title = "Refuser Invitation";
require_once 'inc/header.inc.php';
require_once 'php/groupe.php';
require_once 'php/depense.php';
require_once 'inc/db_link.inc.php';
require_once 'php/participe.php';
$participer = new Participer();
$gid = $_GET['gid'];
if (isset($_POST['buttsubRefuserYes'])) {
    $participer->deleteInvitation($_SESSION['uid'], $gid);
    $_SESSION['refusInv'] = "L'invitation du groupe a été refusé";
    header('Location: groupes.php');
}
if (isset($_POST['buttsubRefuserNo'])) {
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
            <a href="refuserInvitation.php" class="filArianne-lien--active">Supprimer dépense</a>
        </li>
    </ul>
    <section class="containerIn">
        <form method="post" class="addDep">
            <h1 class="titreAjDep">Refuser Invitation</h1>
            <h2>Êtes-vous sûr de vouloir refuser l'invitation de ce groupe</h2>
            <input type="submit" name="buttsubRefuserYes" value="Oui" class="butValider">
            <input type="submit" name="buttsubRefuserNo" value="Non" class="butRefuser">
        </form>
    </section>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>

</html>
