<?php
namespace FlightClub\sql;

use FlightClub\sql\DBConnection;

// resources
require_once("dbConnection.php");
class MediaDAO
{


    /**
     * Function to add a media to the database
     *
     * @param string $path
     * @param int $id
     * @return void
     */
    public static function addmedia($path, $id)
    {
        $date = date("Y-m-d H:i:s");
        $db = DBConnection::getConnection();
        $sql = "INSERT INTO `tbl_Picture`(`Dttm_picture`,`Txt_File_Path`, `Id_Flight`)
        VALUES (:date, :path, :fk)";
        $q = $db->prepare($sql);
        $q->execute(array(
            ":path" => $path,
            ":fk" => $id,
            ":date" => $date
        ));
    }

    /**
     * Change file path in the database
     *
     * @param string $name
     * @param string $tempname
     * @return void
     */
    public static function changePath($name, $tempname)
    {
        $db = DBConnection::getConnection();
        $sql = "UPDATE `tbl_Picture` SET `Txt_File_Path` = :named WHERE `Txt_File_Path` = :tempname";

        $q = $db->prepare($sql);
        $q->execute(array(
            ':named' => $name,
            ':tempname' => substr($tempname, 0, 2)
        ));
    }


    public static function readAll_media()
    {
        try {
            $sql = "SELECT * FROM `media`";
            $db =  DBConnection::getConnection();
            $db->beginTransaction();
            $query = $db->prepare($sql);

            $query->execute();
            $db->commit();
            return $query->fetchAll();
        } catch (\Throwable $th) {
            $th->getMessage();
            $db->rollBack();
        }
    }

    /**
     * Get all the data from a picture id
     *
     * @param int $id
     * @return array
     */
    public static function read_media_by_id($id)
    {
        $sql = "SELECT * FROM `tbl_picture` WHERE `Id_Picture` = :id";

        $query = DBConnection::getConnection()->prepare($sql);

        $query->execute([
            ':id' => $id,
        ]);
        return $query->fetchall();
    }

    /**
     * Get all picture data from his path
     *
     * @param string $path
     * @return void
     */
    public static function read_media_by_Path($path)
    {
        $sql = "SELECT * FROM `tbl_picture` WHERE `Txt_File_Path` = :mypath";

        $query = DBConnection::getConnection()->prepare($sql);

        $query->execute([
            ':mypath' => $path,
        ]);
        return $query->fetch();
    }

    /**
     * Get all media from a flight with his id
     *
     * @param int $idFlight
     * @return array[mixed]
     */
    public static function readMediaByIdFlight($idFlight)
    {
        $sql = "SELECT * FROM `tbl_picture` WHERE `Id_Flight` = :id ORDER BY `Dttm_Picture` desc";

        $query = DBConnection::getConnection()->prepare($sql);

        $query->execute([
            ':id' => $idFlight,
        ]);
        return $query->fetchall();
    }

    /**
     * Remove a picture from a flight
     *
     * @param int $idPost
     * @return void
     */
    public static function del_mediaByIdFlight($idFlight)
    {
        $db = DBConnection::getConnection();
        $sql = "DELETE FROM `media` WHERE `media`.`idPost` = :id";
        $q = $db->prepare($sql);
        $q->execute([
            ':id' => $idFlight,
        ]);
    }

    /**
     * Remove a picture in the database identified by his id
     *
     * @param int $idMedia
     * @return void
     */
    public static function del_mediaByIdMedia($idMedia)
    {
        $db = DBConnection::getConnection();
        $sql = "DELETE FROM `tbl_picture` WHERE `Id_Picture` = :id";
        $q = $db->prepare($sql);
        $q->execute([
            ':id' => $idMedia,
        ]);
    }

    public static function readMediaLinkById($idPost)
    {
        $db = DBConnection::getConnection();
        $sql = "SELECT `pathImg` FROM `media` WHERE `media`.`idPost` = :id";
        $q = $db->prepare($sql);
        $q->execute([
            ':id' => $idPost,
        ]);
        $result = $q->fetchAll();
        return $result;
    }

    public static function GetView($idMedia)
    {
        $db = DBConnection::getConnection();
        $sql = "SELECT `nbCliques` FROM `media` WHERE `media`.`idMedia` = :id";
        $q = $db->prepare($sql);
        $q->execute([
            ':id' => $idMedia,
        ]);
        $result = $q->fetch();
        return $result;
    }
    #endregion
}
