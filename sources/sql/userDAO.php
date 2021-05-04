<?php
/*
FICHIER CONTENANT TOUTES LES FONCTIONS EN RAPPORT AVEC LA TABLE "user"
*/

namespace FlightClub\sql;

use FlightClub\sql\DBConnection;

require_once 'dbConnection.php';

class UserDAO
{

    public static function getUsers($offset = null, $limit = null)
    {
        $db = DBConnection::getConnection();
        $sql = "SELECT * FROM tbl_user";

        if ($limit !== null) {
            $sql .= " LIMIT $limit";
        }
        if ($offset !== null) {
            $sql .= " OFFSET $offset";
        }

        $request = $db->prepare($sql);
        $request->execute();
        return $request->fetchAll();
    }

    public static function countUsers()
    {
        $db = DBConnection::getConnection();
        $sql = 'SELECT count(*) FROM tbl_user';
        $q = $db->prepare($sql);
        $q->execute();
        $information = $q->fetch();
        return $information;
    }

    public static function getUserByID($userID)
    {
        $db = DBConnection::getConnection();
        $sql = "SELECT * FROM tbl_user WHERE idUser = :userID";
        $request = $db->prepare($sql);
        $request->execute([':userID' => $userID]);
        return $request->fetch();
    }

    /**
     * Get all user data from their email
     *
     * @param string $email
     * @return array[mixed]
     */
    public static function getUserByEmail($email)
    {
        $db = DBConnection::getConnection();
        $sql = "SELECT * FROM tbl_user WHERE Txt_Email = :email";
        $request = $db->prepare($sql);
        $request->execute([':email' => $email]);
        return $request->fetch();
    }

    /**
     * Add an user to the database
     *
     * @param string $email
     * @param string $password
     * @param string $salt
     * @return void
     */
    public static function AddUsers($email, $password, $salt)
    {
        $db = DBConnection::getConnection();
        $sql = "INSERT INTO `tbl_user`(`Txt_Email`,`Txt_Password_Hash`,`Txt_Password_Salt`)
    VALUES (:email, :password, :salt)";
        $request = $db->prepare($sql);
        $request->execute([
            ':email' => $email,
            ':password' => $password,
            ':salt' => $salt
        ]);
    }

    /**
     * Check if there is an user with the email provided in parameter
     *
     * @param string $email
     * @return string
     */
    public static function readEmail($email)
    {
        $db = DBConnection::getConnection();
        $sql = "SELECT `Txt_Email` FROM `tbl_user` WHERE `Txt_Email` = :email";

        $request = $db->prepare($sql);
        $request->execute([
            ':email' => $email
        ]);

        $result = $request->fetch();
        return $result;
    }

    public static function readUsersByEmail($email)
    {
        $db = DBConnection::getConnection();
        $sql = "SELECT * FROM `tbl_user` WHERE `Txt_Email` = :email";

        $request = $db->prepare($sql);
        $request->execute([
            ':email' => $email
        ]);

        $result = $request->fetch();
        return $result;
    }

    public static function readPhone($phone)
    {
        $db = DBConnection::getConnection();
        $sql = "SELECT `Tel` FROM `tbl_user` WHERE `Tel` = :tel";

        $query = $db->prepare($sql);

        $query->execute([
            ':tel' => $phone
        ]);

        $result = $query->fetch();
        return $result;
    }
    public static function changeUser($id, $newEmail, $newAdresse, $newNpa, $newLocalite, $newTelephone, $role)
    {
        $db = DBConnection::getConnection();
        $sql = "UPDATE `tbl_user` SET `Email` = :email, `Rue` = :adresse, `Npa` = :npa, `Lieu` = :localite, `Tel` = :telephone, `Role` = :role WHERE `tbl_user`.`idUser` = :id";


        $query = $db->prepare($sql);

        $query->execute([
            ':id' => $id,
            ':email' => $newEmail,
            ':adresse' => $newAdresse,
            ':npa' => $newNpa,
            ':localite' => $newLocalite,
            ':telephone' => $newTelephone,
            ':role' => $role
        ]);
    }
    public static function changePassword($id, $password)
    {
        $db = DBConnection::getConnection();
        $sql = "UPDATE `tbl_user` SET `Password` = :password WHERE `tbl_user`.`idUser` = :id";


        $query = $db->prepare($sql);

        $query->execute([
            ':id' => $id,
            ':password' => $password
        ]);
    }

    public static function getUserOrders($idUser)
    {
        $db = DBConnection::getConnection();
        $sql = "SELECT * FROM `t_commande` WHERE `idUser` = :idUser ORDER BY `dateCommande` DESC";

        $request = $db->prepare($sql);
        $request->execute([
            ':idUser' => $idUser
        ]);

        $result = $request->fetchAll();
        return $result;
    }

    public static function getAllUserOrders()
    {
        $db = DBConnection::getConnection();
        $sql = "SELECT * FROM `t_commande` ORDER BY `dateCommande` DESC";

        $request = $db->prepare($sql);
        $request->execute();

        $result = $request->fetchAll();
        return $result;
    }
}
