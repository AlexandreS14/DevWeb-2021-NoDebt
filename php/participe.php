<?php
use DB\DBLink;
class Participer {

    const TABLE_NAME = 'Participer';

    /**
     * ajoute un participant à un groupe
     * @param $uid id du participant
     * @param $gid id du groupe
     * @param $estConfirme état de la confirmation
     * @return false|PDOStatement
     */
    public function AddParticiper($uid, $gid, $estConfirme) {
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("INSERT INTO ".self::TABLE_NAME."(uid, gid, estConfirme) VALUES(:uid, :gid, :estConfirme)");
            $q->bindValue(':uid', $uid);
            $q->bindValue(':gid', $gid);
            $q->bindValue(':estConfirme', $estConfirme);
            $q->execute();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $q;
    }

    /**
     * selectionne toute les infos en fonction de la confirmation et de l'id du participant
     * @param $participant id du participant
     * @return array|null
     */
    public function participation($participant) {
        $result = null;
        $link = null;
        try {
        $link = \DB\DBLink::connect2db(MYDB, $message);
        $q = $link->query ("SELECT g.nom, g.gid, p.uid, u.prenom, p.estConfirme, g.devise
                                    FROM ".self::TABLE_NAME." p
                                    JOIN nodebt_groupe g ON p.gid = g.gid
                                    JOIN nodebt_utilisateur u ON p.uid = u.uid
                                    WHERE '$participant' = u.uid AND '$participant' <> g.gid AND p.estConfirme = 1");
        $result = $q->fetchAll();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * selectionne toute les inviation en fonction de la confirmation et de id du participant
     * @param $participant id du participant
     * @return array
     */
    public function invitation($participant) {
        try {
        $link = \DB\DBLink::connect2db(MYDB, $message);
        $q = $link->query ("SELECT g.nom, g.gid, p.uid, u.prenom, p.estConfirme, g.devise
                                    FROM ".self::TABLE_NAME." p
                                    JOIN nodebt_groupe g ON p.gid = g.gid
                                    JOIN nodebt_utilisateur u ON p.uid = u.uid
                                    WHERE '$participant' = u.uid AND '$participant' <> g.gid AND p.estConfirme = 0");
        $result = $q->fetchAll();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * modifié l'état de la participation si user accept
     * @param $estConfirme etat de la participation
     * @param $uid id du particiapnt
     * @return mixed|null
     */
    public function acceptInvitation($estConfirme, $uid) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("UPDATE ".self::TABLE_NAME." SET estConfirme=:estConfirme WHERE uid=:uid");
            $q->bindValue(':estConfirme', $estConfirme);
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
     * supprime la participation si le user refuse de rejoindre le groupe
     * @param $uid id du participant
     * @param $gid id du groupe
     * @return mixed|null
     */
    public function deleteInvitation($uid, $gid) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare ("DELETE FROM ".self::TABLE_NAME." WHERE uid=:uid AND gid=:gid");
            $q->bindValue(':uid', $uid);
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
     * selectionne toute les infos en fonction de id du participant
     * @param $uid id du participant
     * @return array|null
     */
    public function getParticipant($uid) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("SELECT * FROM".self::TABLE_NAME."WHERE uid=:uid");
            $q->bindValue(':uid', $uid);
            if ($q->execute()) {
                $result = $q->fetchAll();
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

}
