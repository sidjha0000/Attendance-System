<?php
$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path."/ATTENDANCE_SYSTEM/database/database.php";

class faculty_details
{
    public function varifyUser($dbo, $un, $pw)
    {
        $rv = ['id' => -1, 'status' => 'ERROR']; 
        $c = "SELECT id, password FROM faculty_details WHERE user_name = :un";
        $s = $dbo->conn->prepare($c);
        
        try {
            $s->execute([":un" => $un]);
            if ($s->rowCount() > 0) {
                $result = $s->fetch(PDO::FETCH_ASSOC);
                if ($result['password'] == $pw) { 
                    $rv = ["id" => $result['id'], "status" => "ALL OK"];
                } else {
                    $rv = ["id" => -1, "status" => "WRONG PASSWORD"];
                }
            } else {
                $rv = ["id" => -1, "status" => "USER NAME DOES NOT EXIST"];
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage()); 
        }
        return $rv;
    }
    public function getCoursesInASession($dbo,$sessionid,$facid){
        $rv=[];
        $c="select cd.id,cd.code,cd.title from course_allotment as ca,
        course_details as cd where ca.course_id = cd.id and faculty_id=:facid and 
        session_id=:sessionid";
        $s=$dbo->conn->prepare($c);
        try{
            $s->execute([":facid"=>$facid,":sessionid"=>$sessionid]);
            $rv=$s->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(Exception $e){

        }
        return $rv;
    }
}
?>
