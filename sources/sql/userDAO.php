<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * User-related functions
 */

namespace FlightClub\sql;

use FlightClub\sql\DBConnection;

require_once 'dbConnection.php';

/**
 * User related class that contain user related functions with database interraction
 */
class UserDAO
{

    /**
     * Get users for making a pagination
     *
     * @param [type] $offset
     * @param [type] $limit
     * @return void
     */
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

    /**
     * Count the number of users
     *
     * @return int number of users
     */
    public static function countUsers()
    {
        $db = DBConnection::getConnection();
        $sql = 'SELECT count(*) FROM tbl_user';
        $q = $db->prepare($sql);
        $q->execute();
        $information = $q->fetch();
        return $information;
    }

    /**
     * Get all data from an user with his ID
     *
     * @param int $userID
     * @return array[mixed]
     */
    public static function getUserByID($userID)
    {
        $db = DBConnection::getConnection();
        $sql = "SELECT * FROM tbl_user WHERE Id_User = :userID";
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

    /**
     * Get all user data from their email
     *
     * @param string $email
     * @return array[mixed]
     */
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

    /**
     * Change the password of an user identified by his ID
     *
     * @param int $id
     * @param string $password
     * @param string $salt
     * @return void
     */
    public static function changePassword($id, $password, $salt)
    {
        $db = DBConnection::getConnection();
        $sql = "UPDATE `tbl_user` SET `Txt_Password_Hash` = :password, `Txt_Password_Salt` = :salt WHERE `tbl_user`.`Id_User` = :id";


        $query = $db->prepare($sql);

        $query->execute([
            ':id' => $id,
            ':salt' => $salt,
            ':password' => $password
        ]);
    }
}
