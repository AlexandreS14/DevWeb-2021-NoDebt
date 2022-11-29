<?php
session_start();
$title = "Groupe";
require_once 'inc/db_link.inc.php';
require_once 'php/utilisateur.php';
require_once 'php/groupe.php';
require_once 'php/depense.php';
require_once 'php/participe.php';
require_once 'inc/header.inc.php';
$members = new Utilisateur();
$groupe = new Groupe();
$depense = new depense();
$participe = new Participer();
$members->user_connected();
$donne = $groupe->afficheGroupe($_SESSION['uid']);
$result = $participe->participation($_SESSION['uid']);
$invation = $participe->invitation($_SESSION['uid']);
$devise = "";
if (isset($_SESSION['createGr'])) { echo $_SESSION['createGr']; unset($_SESSION['createGr']);
} elseif (isset($_SESSION['deleGroupe'])) { echo $_SESSION['deleGroupe']; unset($_SESSION['deleGroupe']);
} elseif (isset($_SESSION['editProf'])) { echo $_SESSION['editProf']; unset($_SESSION['editProf']);
} elseif (isset($_SESSION['refusInv'])) { echo $_SESSION['refusInv']; unset($_SESSION['refusInv']); }
?>
<main>
    <ul class="filArianne">
        <li class="filArianne-items">
            <a href="index.php" class="filArianne-lien">Acceuil</a>
        </li>
        <li class="filArianne-items">
            <a href="groupes.php" class="filArianne-lien--active">Groupes</a>
        </li>
    </ul>
    <h1 class="newG">
        <a href="creerGroupe.php"><i class="fas fa-plus-circle"></i>Nouveau Groupe</a>
    </h1>
    <section class="allGr">
        <?php
        if (($donne == null && $result == null && $invation == null)) { ?>
            <h1>Vous n'avez aucun groupe et aucune invitation en attente.</h1>
        <?php } else {
            foreach ($donne as $donnes):
                if ($donnes['devise'] == "Euro") { $devise = "€";
                } elseif ($donnes['devise'] == "Dollar US") { $devise = "$";
                } else { $devise = "£"; }
                ?>
                <article class="casegroupe">
                    <h3 class="groupexxxtitre"><?= $donnes['nom']; ?></h3>
                    <h4>Créateur du groupe : <?php $gid = $donnes['gid'];
                                                    $creator = $members->selectCreator($gid);
                                                    foreach($creator as $creators) {
                                                    $name = $creators;
                                                    } echo $name; ?></h4>
                    <h4>Montant total : <?php $total = $depense->recupTotal($gid);
                                        foreach ($total as $totals) {
                                            if ($totals == null) { echo "0 ".$devise; } else {
                                             echo $totals.' '.$devise; } }?></h4>
                    <details open>
                        <summary>Historique dépense</summary>
                        <ul>
                            <?php
                                $histo = $depense->recupDep($gid);
                            ?>
                        </ul>
                    </details>
                    <button class="consultergroupe" type="submit"><a href="consulterGroupeXXX.php?gid=<?= $gid ?>">CONSULTER</a></button>
                </article>
            <?php endforeach; }
        ?>
        <?php
        foreach ($result as $results):
            if ($results['devise'] == "Euro") { $devise = "€";
            } elseif ($results['devise'] == "Dollar US") { $devise = "$";
            } else { $devise = "£"; }
            ?>
            <article class="casegroupe">
                <h3 class="groupexxxtitre"><?= $results['nom']; ?></h3>
                <h4>Créateur du groupe : <?php
                    $creator = $members->selectCreator($results['gid']);
                    foreach($creator as $creators) {
                        $name = $creators;
                    }
                    echo $name; ?></h4>
                <h4>Montant total : <?php $gid = $results['gid'];
                    $total = $depense->recupTotal($gid);
                    foreach ($total as $totals) { if ($totals == null) { echo "0 ".$devise; } else {
                        echo $totals.' '.$devise; } }?></h4>
                <details open>
                    <summary>Historique dépense</summary>
                    <ul>
                        <?php
                        $gid = $results['gid'];
                        $histo = $depense->recupDep($gid);
                        ?>
                    </ul>
                </details>
                <button class="consultergroupe" type="submit"><a href="consulterGroupeXXX.php?gid=<?= $gid ?>">CONSULTER</a>
                </button>
            </article>
        <?php endforeach; ?>
        <?php
        foreach ($invation as $invitations):
            if ($invitations['devise'] == "Euro") { $devise = "€";
            } elseif ($invitations['devise'] == "Dollar US") { $devise = "$";
            } else { $devise = "£"; }
            ?>
        <article class="casegroupe">
            <h3 class="groupexxxtitre"><?= $invitations['nom']; ?></h3>
            <h4>Créateur du groupe : <?php
                $creator = $members->selectCreator($invitations['gid']);
                foreach($creator as $creators) {
                    $name = $creators;
                }
                echo $name;
                ?></h4>
            <h4>Montant total: <?php $gid = $invitations['gid'];
                $total = $depense->recupTotal($gid);
                foreach ($total as $totals) { if ($totals == null) { echo "0 ".$devise; } else {
                    echo $totals.' '.$devise; } } ?></h4>
            <details open>
                <summary>Historique dépense</summary>
                <ul>
                    <?php
                    $gid = $invitations['gid'];
                    $histo = $depense->recupDep($gid);
                    ?>
                </ul>
            </details>
            <p><?= $name; ?> vous a invitez à rejoindre son groupe</p>
            <form class="butGrInv" method="post">
                <input type="submit" name="buttsubAccept" value="Accepter" class="buttValider">
            </form>
            <button type="submit" name="buttsubRefuse" class="buttSupprimer"><a href="refuserInvitation.php?gid=<?= $gid ?>">Refuser</a></button>
        </article>
        <?php
            if (isset($_POST['buttsubAccept'])) {
                $estConfirme = 1;
                $participe->acceptInvitation($estConfirme, $_SESSION['uid']);
                header('Location: consulterGroupeXXX.php?gid='.$gid);
            }
        endforeach; ?>
    </section>
</main>

<?php
require("inc/footer.inc.php");
?>

</body>

</html>