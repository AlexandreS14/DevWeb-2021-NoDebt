<?php
session_start();
$title = "Consulter Versement";
require_once 'inc/header.inc.php';
require_once 'inc/db_link.inc.php';
$gid = $_GET['gid'];
require_once 'php/versement.php';
$versement = new Versement();
$versements = $versement->selectVersement($gid);

if (isset($_POST['buttsub1ValiderVersement'])) {
    $versement->validerVersement($gid);
}
if (isset($_POST['buttsub1RefuserVersement'])) {
    $versement->refuserVersement($gid);
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
            <a href="consulterGroupeXXX.php?gid=<?= $gid ?>" class="filArianne-lien">Consulter groupe Restaurant</a>
        </li>
        <li class="filArianne-items">
            <a href="consulterVersement.php?gid=<?= $gid ?>" class="filArianne-lien--active">Consulter Versement</a>
        </li>
    </ul>
    <h1 class="tabGroupe">Tableau des versements</h1>
    <table class="tableau">
        <thead>
        <tr>
            <th>Date du versement</th>
            <th>Montant du paiement</th>
            <th>Etat du versement</th>
            <th>Confirmation</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($versements as $result): ?>
        <tr>
            <td><?= $result['date'] ?></td>
            <td><?= $result['montant'] ?></td>
            <td><?php if ($result['confirmation'] == 0) {echo "En attente";} elseif ($result['confirmation'] == 1) {echo "Validé";} else {echo "Refusé";} ?></td>
            <td>
                <form method="post">
                    <input type="submit" name="buttsub1ValiderVersement" class="buttVald" value="Valider">
                    <input type="submit" name="buttsub1RefuserVersement" class="buttRef" value="Refuser">
                </form>
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