<?php
header('Content-Type: application/json'); // Ensure JSON response

$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path."/ATTENDANCE_SYSTEM/database/database.php";
require_once $path."/ATTENDANCE_SYSTEM/database/sessionDetails.php";
require_once $path."/ATTENDANCE_SYSTEM/database/facultyDetails.php";
require_once $path."/ATTENDANCE_SYSTEM/database/courseregistrationDetails.php";
require_once $path."/ATTENDANCE_SYSTEM/database/attendanceDetails.php";
function createCSVReport($list,$filename){
    $error=0;
    $path = $_SERVER['DOCUMENT_ROOT'];
    $finalFilename=$path.$filename;
    try{
        $fp=fopen($finalFilename,"w");
        foreach($list as $line){
            fputcsv($fp,$line);
        }
    }
    catch(Exception $e){
        $error=1;
    }
    
}
if(isset($_REQUEST['action'])) {
    try {
        $action = $_REQUEST['action'];

        if ($action == "getSession") {
            $dbo = new Database();
            $sobj  = new SessionDetails();
            $rv = $sobj->getSession($dbo);
            echo json_encode($rv);
        }

        if ($action == "getFacultyCourses") {
            if (!isset($_POST['facid'], $_POST['sesssionid'])) {
                throw new Exception("Missing required parameters.");
            }
            $facid = $_POST['facid'];
            $sesssionid = $_POST['sesssionid'];
            $dbo = new Database();
            $fo = new faculty_details();
            $rv = $fo->getCoursesInASession($dbo, $sesssionid, $facid);
            echo json_encode($rv);
        }

        if ($action == "getStudentList") {
            if (!isset($_POST['classid'], $_POST['sessionid'], $_POST['facid'], $_POST['ondate'])) {
                throw new Exception("Missing required parameters.");
            }
            $classid = $_POST['classid'];
            $sessionid = $_POST['sessionid'];
            $facid = $_POST['facid'];
            $ondate = $_POST['ondate'];
            $dbo = new Database();
            $crgo = new courseregistrationDetails();
            $allstudents = $crgo->getRegisteredStudents($dbo, $sessionid, $classid);
            $ado = new attendanceDetails();
            $presentStudents = $ado->getPresentListOfAClassByAFacOnADate($dbo, $sessionid, $classid, $facid, $ondate);

            for ($i = 0; $i < count($allstudents); $i++) { // Fixed loop syntax
                $allstudents[$i]['isPresent'] = 'NO'; // Default to NO
                foreach ($presentStudents as $present) {
                    if ($allstudents[$i]['id'] == $present['student_id']) {
                        $allstudents[$i]['isPresent'] = 'YES';
                        break;
                    }
                }
            }
            echo json_encode($allstudents);
        }

        if ($action == "saveattendance") {
            if (!isset($_POST['courseid'], $_POST['sessionid'], $_POST['studentid'], $_POST['facultyid'], $_POST['ondate'], $_POST['ispresent'])) {
                throw new Exception("Missing required parameters.");
            }
            $courseid = $_POST['courseid'];
            $sessionid = $_POST['sessionid'];
            $studentid = $_POST['studentid'];
            $facultyid = $_POST['facultyid'];
            $ondate = $_POST['ondate'];
            $status = $_POST['ispresent'];
            $dbo = new Database();
            $ado = new attendanceDetails();
            $rv = $ado->saveAttendance($dbo, $sessionid, $courseid, $facultyid, $studentid, $ondate, $status);
            echo json_encode($rv);
        }
    } 
    catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]); // Return JSON error message
    }
    // data:{sessionid:sessionid,classid:classid,facid:facid,action:"downloadReport"},
    if($action=="downloadReport"){
        $courseid=$_POST['classid'];
        $sessionid=$_POST['sessionid'];
        $facultyid=$_POST['facid'];

        $dbo=new Database();
        $ado = new attendanceDetails();

        $list=[
            [1,"csb21001",20.00],
            [2,"csb21002",30.00],
            [3,"csb21003",40.00]
        ];
        $list=$ado->getAttendanceReport($dbo,$sessionid,$courseid,$facultyid);
        $filename="/ATTENDANCE_SYSTEM/report.csv";
        $rv=["filename"=>$filename];
        createCSVReport($list,$filename);
        echo json_encode($rv);
    }
}
?>
