<?php
require_once 'class.user.php';
require_once 'db_connection.php';



if (!isset($_SESSION["uid"])) {
    $user->redirect("login.php");
}
elseif($_SESSION["urole"] != "Admin"){
    $user->redirect("login.php");

}


$user = new USER($conn);

if (isset($_GET['term'])) {
    $searchTerm = $_GET['term'];
    $user->searchBooks($searchTerm);
} else {
    echo json_encode([]);
}

?>
