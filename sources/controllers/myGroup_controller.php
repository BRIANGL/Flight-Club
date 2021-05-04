<?php

/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/04)
 * My group logic here
 */
require_once "./sql/userDAO.php";
require_once "./sql/groupDAO.php";

use FlightClub\sql\GroupDAO;
use FlightClub\sql\userDAO;

//we check if the user is already loged-in
if (!isset($_SESSION['userID'])) {
    header("Location: ./index.php?page=homepage");
    exit();
}

$leave = filter_input(INPUT_POST, "delete");

if ($leave) {
    GroupDAO::deletePendingInvite($leave, $_SESSION['userID']);
}


function showGroups()
{
    $MyGroups = GroupDAO::getAllOfMyGroup($_SESSION['userID']);
    if (!empty($MyGroups)) {
        foreach ($MyGroups as $key => $value) {
            echo "<div class=\"card-group\">";
?>

            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?= $value['Nm_Group'] ?></h5>
                </div>
                <div class="card-body">
                    <a class="btn btn-outline-primary" href="?page=aboutGroup&id=<?= $value['Id_Group'] ?>" class="card-link">Plus d'infos</a>
                    <button name='delete' class='btn btn-outline-danger' value='<?= $value['Id_Group'] ?>' type="submit">X</button>
                </div>
            </div>
<?php
            echo "</div>";
        }
    }else {
            echo "<p>Vous n'appartenez à aucun groupe!</p>";
    }
}

?>