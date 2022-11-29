<?php

use DB\DBLink;

class Utilisateur
{
    const TABLE_NAME = 'nodebt_utilisateur';

    /**
     * @return bool retourne true si user est connécté sinon retourne false
     */
    public function is_connected()
    {
        return !empty($_SESSION['uid']);
    }

    /**
     * si user uid n'est pas connecté, redirection vers la page de connection
     */
    public function user_connected()
    {
        if (!isset($_SESSION['uid'])) {
            header('Location: index.php');
            exit();
        }
    }

    public function user_is_connected() {
        if (isset($_SESSION['uid'])) {
            header('Location: groupes.php');
            exit();
        }
    }

    /**
     * connecte l'utilisateur
     * @param $email email de l'utilisateur
     * @param $password mot de passe de l'utilisateur
     */
    public function authUser($email, $password)
    {
        $message = "Nous sommes désolé, une erreur est survenue";
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("SELECT uid, nom, prenom, email, password, cpassword FROM " . self::TABLE_NAME . " WHERE email= :email AND password= :password");
            $q->bindValue(':email', $email);
            $q->bindValue(':password', $password);
            $q->execute();
            if ($q->rowCount() > 0) {
                $row = $q->fetch(PDO::FETCH_ASSOC);
                if ($row['email'] === $email && $row['password'] === $password) {
                    $_SESSION = $row;
                }
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
    }

    /**
     * créer le compte d'un nouvel utilisateur
     * @param $nom nom de l'utilisateur
     * @param $prenom prenom de l'utilisateur
     * @param $email email de l'utilisateur
     * @param $password mot de passe de l'utilisateur
     * @param $cpassword confirmation du mot de passe de l'utilisateur
     */
    public function signUser($nom, $prenom, $email, $password, $cpassword)
    {
        $result = null;
        $link = null;
        $message = "Nous sommes désolé, une erreur est survenue";
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("INSERT INTO " . self::TABLE_NAME . "(nom, prenom, email, password, cpassword) VALUES(:nom, :prenom, :email, :password, :cpassword)");
            $q->bindValue(':nom', $nom);
            $q->bindValue(':prenom', $prenom);
            $q->bindValue(':email', $email);
            $q->bindValue(':password', $password);
            $q->bindValue(':cpassword', $cpassword);
            // vérifie si le mdp est égal au cmdp
            $protect = new Protect();
            $protect->identicalPassword($password, $cpassword) . '<br>';
            // vérifie le format de email et si email exist déjà en BDD
            $protect->verifyEmail($email) . '<br>';
            $protect->existsEmail($email) . '<br>';
            $result = $q->execute();
            if ($q->rowCount() > 0) {
                $row = $q->fetch(PDO::FETCH_ASSOC);
                if ($row['nom'] === $nom && $row['prenom'] === $prenom && $row['email'] === $email) {
                    $_SESSION = $row;
                }
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * Modifie le profil de l'user
     * @param $nom nom de l'utilisateur
     * @param $prenom prenom de l'utilisateur
     * @param $email email de l'utilisateur
     * @param $password mot de passe de l'utilisateur
     * @param $cpassword confirmation du mot de passe de l'utilisateur
     */
    public function editUser($nom, $prenom, $password, $cpassword)
    {
        $message = "Nous sommes désolé, une erreur est survenue";
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("UPDATE " . self::TABLE_NAME . " SET nom=:nom, prenom=:prenom, password=:password, cpassword=:cpassword WHERE uid=:uid");
            $q->bindValue(':nom', $nom);
            $q->bindValue(':prenom', $prenom);
            $q->bindValue(':password', $password);
            $q->bindValue(':cpassword', $cpassword);
            $q->bindValue(':uid', $_SESSION['uid']);
            // vérifie si le mdp est égal au cmdp
            $protect = new Protect();
            $protect->identicalPassword($password, $cpassword) . '<br>';
            $q->execute();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
    }

    /**
     * Selectionne le uid du créateur du groupe
     * @param $gid id du groupe
     * @return mixed
     */
    public function selectCreator($gid)
    {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("SELECT u.prenom FROM " . self::TABLE_NAME . " u 
                                       JOIN nodebt_groupe g ON u.uid = g.uid WHERE gid=:gid");
            $q->bindValue(':gid', $gid);
            $q->execute();
            $result = $q->fetch();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * change le mdp par un mdp généré aléatoirement
     * @param $mdpRandom mdp généré aléatoirement
     * @param $mdpRandom confirmation du mdp généré aléatoirement
     * @param $recup_email email du user
     */
    public function recupMdp($mdpRandom, $mdpRandom, $recup_email)
    {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("UPDATE " . self::TABLE_NAME . " SET password=:password, cpassword=:cpassword WHERE email=:email");
            $q->bindValue(':password', $mdpRandom);
            $q->bindValue(':cpassword', $mdpRandom);
            $q->bindValue(':email', $recup_email);
            $q->execute();
            $result = $q->fetch();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * selectionne uid à partir de email
     * @param $email email de user
     * @return mixed|null
     */
    public function selectUidByEmail($email)
    {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("SELECT uid FROM " . self::TABLE_NAME . " WHERE email=:email");
            $q->bindValue(':email', $email);
            if ($q->execute()) {
                $result = $q->fetch(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * selectionne tous les membres d'un groupe
     * @param $gid id du groupe
     * @return array retourne un tableau connenant les différents membres
     */
    public function selectAllMembersG($gid)
    {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->query("SELECT u.prenom, u.nom, u.uid FROM nodebt_utilisateur u JOIN nodebt_groupe g ON u.uid = g.uid
                                        WHERE g.gid= '$gid'
                                        UNION
                                        SELECT u.prenom, u.nom, u.uid FROM nodebt_utilisateur u JOIN Participer p ON u.uid = p.uid
                                        WHERE p.gid = '$gid' AND p.estConfirme = 1");
            $result = $q->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * selectionne uid du user a partir de son nom
     * @param $name nom du user
     * @return mixed|null
     */
    public function selectUidByName($name) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare ("SELECT uid FROM ". self::TABLE_NAME ." WHERE  nom=:nom");
            $q->bindValue(':nom', $name);
            if ($q->execute()) {
                $result = $q->fetch(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }
}

?>

