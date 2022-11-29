<?php
use DB\DBLink;
class Facture {
    const TABLE_NAME = 'nodebt_facture';
    /**
     * Ajoute un scan en lien avec une dépense
     * @param $scan le fichier
     * @param $did id de la dépense
     * @return false|PDOStatement
     */
    public function addScan($scan, $did) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("INSERT INTO ".self::TABLE_NAME."(scan, did) VALUES (?,?)");
            $q->execute(array($scan, $did));
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * Supprimer un scan lié à une dépense
     * @param $did id de la dépense
     */
    public function deleteScan($fid) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("DELETE FROM ".self::TABLE_NAME." WHERE fid=:fid");
            $q->bindValue(':fid', $fid);
            if($q->execute()) {
                $result = $q->fetchAll();
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * sélectionne les scans lié a une dépense
     * @param $did id de la dépense
     * @return mixed|null return null
     */
    public function selectScan($did) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE did=:did");
            $q->bindValue(':did', $did);
            if($q->execute()) {
                while($result = $q->fetch()) {
                    echo '<img src="./uploads/'.$result['scan'].'" style="width: 20%;height: 20%">';
                }
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * sélectionne toute les infos relative à id d'une dépense
     * @param $did id de la dépense
     * @return array|null
     */
    public function getScanBYDid($did) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE did=:did");
            $q->bindValue(':did', $did);
            if($q->execute()) {
                $result = $q->fetchAll();
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * sélectionne le scan et son id
     * @param $fid id du scan
     * @return mixed|null
     */
    public function getScanByFid($fid) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("SELECT fid, scan FROM ".self::TABLE_NAME." WHERE scan=:scan");
            $q->bindValue(':scan', $fid);
            if($q->execute()) {
                $result = $q->fetch(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }
}
