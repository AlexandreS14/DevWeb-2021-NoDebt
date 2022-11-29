<?php
session_start();
$title = "Ajouter un scan";
require_once 'inc/db_link.inc.php';
require_once 'php/protect.php';
require_once 'php/utilisateur.php';
require_once 'php/groupe.php';
require_once 'php/depense.php';
require_once 'inc/header.inc.php';
require_once 'php/facture.php';
$members = new Utilisateur();
$groupes = new Groupe();
$depense = new depense();
$facture = new Facture();
$members->user_connected();
$protect = new protect();
$did = $_GET['did'];
$gid = $_GET['gid'];
$dep = $depense->afficherDepEdit($did);

if (isset($_POST['buttsubScanAdd'])) {
    $fileName = $protect->protectData($_FILES['scan']['name']);
    $maxSize = 50000;
    if (isset($_FILES['scan'])) {
        $tmpName = $_FILES['scan']['tmp_name'];
        $name = $_FILES['scan']['name'];
        $size = $_FILES['scan']['size'];
        if ($size > $maxSize) {
            echo "Fichier trop volumineux";
        }
        $error = $_FILES['scan']['error'];
        if ($error > 0) {
            echo "Erreur survenue";
        }
        $type = $_FILES['scan']['type'];

        $tabExtension = explode('.', $name);
        $extension = strtolower(end($tabExtension));
        $typeFile = array('jpg', 'png', 'pdf');

        if (in_array($extension, $typeFile) && $size <= $maxSize) {
            move_uploaded_file($tmpName, './uploads/'.$name);
            $facture->addScan($fileName,$did);
            $_SESSION['addScan'] = "Scan a été ajouté";
            header('Location: consulterGroupeXXX.php?gid='.$gid);
        }
    } else {
        echo "Veuillez insérer un scan";
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
            <a href="ajouterScan.php?gid=<?= $gid ?> &&did= <?= $did ?>" class="filArianne-lien--active">Ajouter dépense</a>
        </li>
    </ul>
    <div class="containerAd">
        <form method="post" class="addDep" enctype="multipart/form-data">
            <h1>Ajouter un scan à la dépense de : <?= $dep['membre'] ?></h1>
            <h2>Tag : <?= $dep['tag'] ?></h2>
            <label>Ajouter un scan</label><input class="choixFichier" type="file" id="scan" name="scan"
                                                 accept="image/*,.pdf, .docx" required>
            <input class="butValider" type="submit" name="buttsubScanAdd" value="Valider">
        </form>
    </div>
</main>
<?php
require("inc/footer.inc.php");
?>

</body>

</html>
