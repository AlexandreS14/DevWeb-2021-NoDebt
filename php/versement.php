<?php
use DB\DBLink;
class Versement {
    const TABLE_NAME = 'nodebt_versement';

    /**
     * ajoute un versement au groupe
     * @param $gid id du groupe
     * @param $uid id du créditeur
     * @param $uid_1 id du débiteur
     * @param $date date du versement
     * @param $montant montant du versement
     * @param $estConfirme etat du versement
     * @return false|PDOStatement
     */
    public function addVersement($gid, $uid, $uid_1, $date, $montant, $estConfirme) {
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("INSERT INTO ".self::TABLE_NAME."(gid, uid, uid_1, date, montant, confirmation) VALUES(:gid, :uid, :uid_1, :date, :montant, :confirmation)");
            $q->bindValue(':gid', $gid);
            $q->bindValue(':uid', $uid);
            $q->bindValue(':uid_1', $uid_1);
            $q->bindValue(':date', $date);
            $q->bindValue(':montant', $montant);
            $q->bindValue(':confirmation', $estConfirme);
            $q->execute();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $q;
    }

    /**
     * selectionne tout les versements par groupe
     * @param $gid id du groupe
     * @return array|null
     */
    public function selectVersement($gid) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("SELECT montant, date, confirmation FROM ".self::TABLE_NAME." WHERE gid=:gid");
            $q->bindValue(':gid', $gid);
            $q->execute();
            $result = $q->fetchAll();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * supprimer un versement
     * @param $gid id du groupe
     * @return array|null
     */
    public function deleteVersement($gid) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("DELETE FROM ".self::TABLE_NAME." WHERE gid=:gid");
            $q->bindValue(':gid', $gid);
            $q->execute();
            $result = $q->fetchAll();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * modifie l'etat du versement en fonction du choix de utilisateur
     * @param $gid id du groupe
     * @return bool|null
     */
    public function refuserVersement( $gid) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("UPDATE ".self::TABLE_NAME." SET confirmation = 2 WHERE gid=:gid");
            $q->bindValue(':gid', $gid);
            $result = $q->execute();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * modifie l'etat du versement en fonction du choix de utilisateur
     * @param $gid id du groupe
     * @return bool|null
     */
    public function validerVersement($gid) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("UPDATE ".self::TABLE_NAME." SET confirmation = 1 WHERE gid=:gid");
            $q->bindValue(':gid', $gid);
            $result = $q->execute();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

}
