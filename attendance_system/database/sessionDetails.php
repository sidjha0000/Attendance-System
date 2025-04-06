<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path."/ATTENDANCE_SYSTEM/database/database.php";

class SessionDetails {
    public function getSession($dbo) {
        $rv = [];
        $c = "SELECT * FROM session_details";
        $s = $dbo->conn->prepare($c);
        try {
            $s->execute();
            $rv = $s->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Database error: " . $e->getMessage()); // Log the error
            $rv = ["error" => "Database query failed"];
        }
        return $rv;
    }
}
?>
