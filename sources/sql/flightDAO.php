<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * Flight-related functions
 */

namespace FlightClub\sql;

use FlightClub\sql\DBConnection;

require_once 'dbConnection.php';

/**
 * Flight related class that contain flight related functions with database interraction
 */
class FlightDAO
{

    /**
     * Get all flight of an user with his id
     *
     * @param int $idUser
     * @return array[mixed]
     */
    public static function getAllUserFlightByUserId($idUser)
    {
        $db = DBConnection::getConnection();
        $sql = "SELECT * FROM `tbl_flight` join `tbl_user-flight` on `tbl_flight`.`Id_Flight` = `tbl_user-flight`.`Id_Flight` WHERE `Id_User` = :idUser";
        $request = $db->prepare($sql);
        $request->execute([
            ':idUser' => $idUser
        ]);
        return $request->fetchAll();
    }
}
