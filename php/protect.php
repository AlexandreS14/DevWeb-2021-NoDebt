<?php

class protect
{
    /* constante */
    const ERRORMDP = "Le mot de passe n'est pas identique ! ";
    const ERROREMAIL = "Format de l'email incorrect ! ";
    /**
     * Retourne la donnée convertissant les caractères spéciaux
     * @param $data donnée de l'utilisateur
     * @return string la donnée
     */
    public function protectData($data)
    {
        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = addslashes($data);
        return $data;
    }

    /**
     * vérifie si les deux mots de passe sont identiques
     * @param $password mot de l'utilisateur
     * @param $cpassword confirmation du mot de passe de l'utilisateur
     */
    public function identicalPassword($password, $cpassword)
    {
        if ($password != $cpassword) {
            echo self::ERRORMDP;
        }
    }

    /**
     * Vérifie le format de l'adresse email
     * @param $email email de l'utilisateur
     */
    public function verifyEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo self::ERROREMAIL;
        }
    }

    /**
     * Vérifier si l'email existe déjà en bdd
     * @param $email email de l'utilisateur
     */
    public function existsEmail($email)
    {
        $link = \DB\DBLink::connect2db(MYDB, $message);
        $q = $link->prepare("SELECT * FROM nodebt_utilisateur WHERE email=:email");
        $q->bindValue(':email', $email);
        $q->execute();
        $exist = $q->fetch();
    }
}


