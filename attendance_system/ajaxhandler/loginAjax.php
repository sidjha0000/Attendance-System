<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path."/ATTENDANCE_SYSTEM/database/database.php";
require_once $path."/ATTENDANCE_SYSTEM/database/facultyDetails.php";

$action = $_REQUEST['action'] ?? '';

if (!empty($action)) {
    if ($action == "verifyUser") {
        $un = $_POST["username"] ?? ''; 
        $pw = $_POST["password"] ?? '';

        if (!empty($un) && !empty($pw)) {
            $dbo = new Database();
            $fdo = new faculty_details();
            $rv = $fdo->varifyUser($dbo, $un, $pw); 
            if ($rv['status'] == "ALL OK") {
                session_start();
                $_SESSION['current_user'] = $rv["id"];
            }

            echo json_encode($rv);
        } else {
            echo json_encode(["id" => -1, "status" => "MISSING CREDENTIALS"]);
        }
    }
}
?>
