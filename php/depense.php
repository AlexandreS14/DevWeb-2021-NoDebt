<?php
use DB\DBLink;
class depense {
    const TABLE_NAME = 'nodebt_depense';
    /**
     * affiche l'historique des 3 dernières depenses du groupe
     * @return mixed
     */
    public function recupDep($gid) {
        $link = \DB\DBLink::connect2db(MYDB, $message);
        $q = $link->query("SELECT *
                                    FROM ".self::TABLE_NAME." d
                                    JOIN nodebt_groupe g ON d.gid = g.gid
                                    WHERE d.gid = '$gid'
                                    ORDER BY d.gid, d.date DESC LIMIT 3");
        if ($q) {
            while($histo = $q->fetch()) {
                if ($histo['devise'] == "Euro") { $devise = "€";
                } elseif ($histo['devise'] == "Dollar US") { $devise = "$";
                } else { $devise = "£"; } ?>
                <li><?php echo $histo['libelle'] . ' : ' . $histo['montant'] .' '.$devise; ?></li>
           <?php }
        }
        return $histo;
    }

    /**
     * récupére le total du groupe
     * @param $gid id du groupe
     * @return mixed|null
     */
    public function recupTotal($gid) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("select sum(d.montant)
                                        from ".self::TABLE_NAME." d
                                        join nodebt_groupe g on d.gid = g.gid 
                                        WHERE d.gid = :gid");
            $q->bindValue(':gid', $gid);
            $q->execute();
            $result = $q->fetch(PDO::FETCH_NUM);
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * Affiche toutes les dépenses du groupes
     * @param $gid id du groupe
     * @return array tableau contenant toutes les dépenses du groupe
     */
    public function tableauRecap($gid) {
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->query("SELECT * 
                                        FROM ".self::TABLE_NAME."
                                        WHERE gid='$gid'");
            $result = $q->fetchAll();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        return $result;
    }

    /**
     * affiche les dépenses par rapport a son id
     * @param $did id d'une dépense
     * @return mixed
     */
    public function afficherDepEdit($did) {
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->query("SELECT * FROM ".self::TABLE_NAME." WHERE did = '$did'");
            $q->bindValue(':did', $did);
            if($q->execute()) {
                $result = $q->fetch();
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * Modifie la dépense
     * @param $montant montant de la dépense
     * @param $libelle libellé de la dépense
     * @param $tag tag de la dépense
     * @param $membre le user qui effectue la dépense
     * @param $did id de la dépense
     * @return false|PDOStatement
     */
    public function updateDepense($montant, $libelle, $tag, $membre, $did, $uid) {
        $link = null;
        $result = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("UPDATE ".self::TABLE_NAME." SET montant=:montant, libelle=:libelle, tag=:tag, membre=:membre, uid=:uid WHERE did=:did");
            $q->bindValue(':montant', $montant);
            $q->bindValue(':libelle', $libelle);
            $q->bindValue(':tag', $tag);
            $q->bindValue(':membre', $membre);
            $q->bindValue(':did', $did);
            $q->bindValue(':uid', $uid);
            $result = $q->execute();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * Ajoute une dépense au groupe
     * @param $tag tag de la dépense
     * @param $participant nom du participant
     * @param $date date de la dépense
     * @param $montant montant de la dépense
     * @param $libelle libelle de la dépense
     * @param $gid id du groupe
     * @param $uid id du participant
     * @return false|PDOStatement
     */
    public function addDepense( $tag, $participant, $date, $montant, $libelle, $gid, $uid) {
        $link = null;
        $result = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("INSERT INTO ".self::TABLE_NAME."(tag, membre, date, montant, libelle, gid, uid) VALUES(:tag, :membre, :date, :montant, :libelle, :gid, :uid)");
            $q->bindValue(':tag', $tag);
            $q->bindValue(':membre', $participant);
            $q->bindValue(':date', $date);
            $q->bindValue(':montant', $montant);
            $q->bindValue(':libelle', $libelle);
            $q->bindValue(':gid', $gid);
            $q->bindValue(':uid', $uid);
            $result = $q->execute();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * Supprimer une dépense d'un groupe
     * @param $did id de la dépense
     */
    public function deleteDepense($did) {
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->query("DELETE FROM ".self::TABLE_NAME." WHERE did='$did'");
            $q->execute();

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
    }

    /**
     * fait la somme des montant de chaque user
     * @param $gid id du groupe
     * @param $uid id du user
     * @return mixed
     */
    public function depParId($gid, $uid) {
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare ("select sum(montant)
                                        from ".self::TABLE_NAME."
                                        WHERE gid=:gid AND uid=:uid");
            $q->bindValue(':gid', $gid);
            $q->bindValue(':uid', $uid);
            if ($q->execute()) {
                $result = $q->fetch(PDO::FETCH_NUM);
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * permet de filtrer sur base du libelle ou du tag
     * @param $gid id du groupe
     * @param $label nom de la recherche
     * @return array|null
     */
    public function filter($gid, $label) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->query("SELECT * FROM ".self::TABLE_NAME." WHERE gid='$gid' AND CONCAT(libelle, tag) LIKE '%$label%' ");
            if ($q->rowCount() > 0) {
                $result = $q->fetchAll();
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * filtre a partir de toute les données du formulaire avancé
     * @param $gid id du groupe
     * @param $min montant min
     * @param $Max montant max
     * @param $debut date de début
     * @param $fin date de fin
     * @param $libelle libelle de la dépense
     * @param $tag tag de la dépense
     * @return array|null
     */
    public function filterAvance($gid, $min, $Max, $debut, $fin, $libelle, $tag) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE gid=:gid AND CONCAT(montant BETWEEN :min AND :max) AND CONCAT(date BETWEEN :debut AND :fin) AND libelle=:libelle AND tag=:tag");
            $q->bindValue(':gid', $gid);
            $q->bindValue(':min', $min);
            $q->bindValue(':max', $Max);
            $q->bindValue(':debut', $debut);
            $q->bindValue(':fin', $fin);
            $q->bindValue(':libelle', $libelle);
            $q->bindValue(':tag', $tag);
            $q->execute();
            $result = $q->fetchAll();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * filtre a partir de la date de début et de fin
     * @param $gid id du groupe
     * @param $debut date de début
     * @param $fin date de fin
     * @return array|null
     */
    public function filterByDate($gid, $debut, $fin) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE gid=:gid AND (date BETWEEN :debut AND :fin)");
            $q->bindValue(':gid', $gid);
            $q->bindValue(':debut', $debut);
            $q->bindValue(':fin', $fin);
            if ($q->execute()) {
                $result = $q->fetchAll();
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * filtre à partir d'un intervalle min et max
     * @param $gid id ud groupe
     * @param $min montant min
     * @param $max montant max
     * @return array|null
     */
    public function filterByMontant($gid,$min, $max) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE gid=:gid AND (montant BETWEEN :min AND :max)");
            $q->bindValue(':gid', $gid);
            $q->bindValue(':min', $min);
            $q->bindValue(':max', $max);
            $q->execute();
            $result = $q->fetchAll();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }

    /**
     * filtre à partir d'un intervalle min et max, date début et fin
     * @param $gid id du groupe
     * @param $min montant min
     * @param $Max montant max
     * @param $debut date de début
     * @param $fin date de fin
     * @return array|null
     */
    public function filterDateMontant($gid, $min, $Max, $debut, $fin) {
        $result = null;
        $link = null;
        try {
            $link = \DB\DBLink::connect2db(MYDB, $message);
            $q = $link->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE gid=:gid AND CONCAT(montant BETWEEN :min AND :max) AND CONCAT(date BETWEEN :debut AND :fin)");
            $q->bindValue(':gid', $gid);
            $q->bindValue(':min', $min);
            $q->bindValue(':max', $Max);
            $q->bindValue(':debut', $debut);
            $q->bindValue(':fin', $fin);
            $q->execute();
            $result = $q->fetchAll();
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($link);
        return $result;
    }
}
