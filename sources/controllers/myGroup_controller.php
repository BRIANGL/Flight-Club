<?php
/**
 * @author GOLAY Brian
 * @version 1.0 (2021/05/20)
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

//we filter the user input value of the button
$leaveGroup = filter_input(INPUT_POST, 'delete', FILTER_SANITIZE_NUMBER_INT);

//we initialize empty messages
$errorMessage = "";
$successMessage = "";

//check if the user clicked on leave group
if (!empty($leaveGroup)) {
    try {//we try to remove the user from the group
        GroupDAO::deletePendingInvite($leaveGroup, $_SESSION['userID']);
        $successMessage = "Vous avez quitter le groupe!";
    } catch (\Throwable $th) {
        //if there was an error
        $errorMessage = "Une erreur est survenue, veuillez réessayer";
    }
}




/**
 * Show all groups of the current user
 *
 * @return string
 */
function showGroups()
{
    //We get all of the groups that the user is in
    $MyGroups = GroupDAO::getAllOfMyGroup($_SESSION['userID']);
    //if he is in a group or more, we show him his groups
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
    }else {//if not, we show him a message saying that he isn't in any group yet
            echo "<p>Vous n'appartenez à aucun groupe!</p>";
    }
}

?>