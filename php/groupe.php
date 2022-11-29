<?php
use DB\DBLink;
class Groupe
{
    const TABLE_NAME = 'nodebt_groupe';
    /**
     * Créer un groupe
     * @param $nomG nom du groupe
     * @param $devises type de la devise choisie
     * @param $uid uid du user
     */
    public function creerGroupe($nomG, $devises, $uid)
    {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("INSERT INTO ".self::TABLE_NAME."(nom, devise, uid) VALUES(:nom, :devise, :uid)");
            $q->bindValue(':nom', $nomG);
            $q->bindValue(':devise', $devises);
            $q->bindValue(':uid', $uid);
            $q->execute();
            $result = $q->fetch();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * Sélectionne les données nécessaire à l'affichage du groupe
     * @param $uid uid de utilisateur
     * @param $creator prenom du créateur du groupe
     * @return array return toute les données du groupe
     */
    public function afficheGroupe($uid)
    {
        $message = "Nous sommes désolés, une erreur est survenue";
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->query("SELECT g.nom, u.prenom, g.gid, g.devise
                                         FROM ".self::TABLE_NAME." g
                                         JOIN nodebt_utilisateur u ON g.uid = u.uid
                                         WHERE g.uid = '$uid'");
            $donne = $q->fetchAll();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $donne;
    }

    /**
     * update le groupe
     * @param $nomG nom du groupe
     * @param $devises devise du groupe
     * @param $gid gid du groupe
     * @return null return resultat si pas null
     */
    public function editGroupe($nomG, $devises, $gid)
    {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("UPDATE ".self::TABLE_NAME." SET nom=:nom, devise=:devise WHERE gid=:gid");
            $q->bindValue(':nom', $nomG);
            $q->bindValue(':devise', $devises);
            $q->bindValue(':gid', $gid);
            $q->execute();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * consulte le groupe les donnée du groupe sur base de son gid
     * @param $gid id du groupe
     * @return mixed|null
     */
    public function consulter($gid) {
        $link = null;
        $res = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE gid=:gid");
            $q->bindValue(':gid', $gid);
            if ($q->execute()) {
                $res = $q->fetch();
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $res;
    }

    /**
     * Supprime le groupe sélectionné
     * @param $gid id du groupe
     */
    public function deleteGroup($gid) {
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("DELETE FROM ".self::TABLE_NAME." WHERE gid='$gid'");
            $q->execute();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
    }


}