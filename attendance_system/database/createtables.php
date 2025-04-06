<?php

$path = $_SERVER['DOCUMENT_ROOT'];
require_once $path . '/attendance_system/database/database.php';
// function to clear the table
function clearTable($dbo, $tabName)
{
    $c = "delete from :tabName";
    $s = $dbo->conn->prepare($c);
    try {
        $s->execute([":tabName" => $tabName]);
    } catch (PDOException $o) {

    }
}
$dbo = new Database();

$c="create table student_details
(
    id int auto_increment primary key,
    roll_no varchar(20) unique,
    name varchar(50)
)";

$s = $dbo->conn->prepare($c);
try{
    $s->execute();
    echo ("<br> student_details table created");
}
catch(PDOException $o){
    echo ("<br> student_details table not created");
}


$c="create table course_details
(
    id int auto_increment primary key,
    code varchar(20) unique,
    title varchar(50),
    credits int
)";

$s = $dbo->conn->prepare($c);
try{
    $s->execute();
    echo ("<br> course_details table created");
}
catch(PDOException $o){
    echo ("<br> course_details table not created");
}


$c="create table faculty_details
(
    id int auto_increment primary key,
    user_name varchar(20) unique,
    name varchar(100),
    password varchar(50)
)";

$s = $dbo->conn->prepare($c);
try{
    $s->execute();
    echo ("<br> faculty_details table created");
}
catch(PDOException $o){
    echo ("<br> faculty_details table not created");
}


$c="create table session_details
(
    id int auto_increment primary key,
    year int,
    term varchar(50),
    unique(year, term)
)";

$s = $dbo->conn->prepare($c);
try{
    $s->execute();
    echo ("<br> session_details table created");
}
catch(PDOException $o){
    echo ("<br> session_details table not created");
}



$c="create table course_registration
(
    student_id int,
    course_id int,
    session_id int,
    primary key(student_id, course_id, session_id)
)";

$s = $dbo->conn->prepare($c);
try{
    $s->execute();
    echo ("<br> course_registration table created");
}
catch(PDOException $o){
    echo ("<br> course_registration table not created");
}


$c="create table course_allotment
(
    faculty_id int,
    course_id int,
    session_id int,
    primary key(faculty_id, course_id, session_id)
)";

$s = $dbo->conn->prepare($c);
try{
    $s->execute();
    echo ("<br> course_allotment table created");
}
catch(PDOException $o){
    echo ("<br> course_allotment table not created");
}


$c="create table attendance_details
(
    faculty_id int,
    course_id int,
    session_id int,
    student_id int,
    on_date date,
    status varchar(10),
    primary key(faculty_id, course_id, session_id, student_id, on_date)
    
)";

$s = $dbo->conn->prepare($c);
try{
    $s->execute();
    echo ("<br> attendance_details table created");
}
catch(PDOException $o){
    echo ("<br> attendance_details table not created");
}

$c = "insert into student_details
(id, roll_no, name)
values
    (1, '18BCE001', 'Amit'),
    (2, '18BCE002', 'Bhavesh'),
    (3, '18BCE003', 'Chirag'),
    (4, '18BCE004', 'Dhruv'),
    (5, '18BCE005', 'Esha'),
    (6, '18BCE006', 'Fenil'),
    (7, '18BCE007', 'Gaurav'),
    (8, '18BCE008', 'Hemant'),
    (9, '18BCE009', 'Isha'),
    (10, '18BCE010', 'Jatin'),
    (11, '18BCE011', 'Karan'),
    (12, '18BCE012', 'Lalit'),
    (13, '18BCE013', 'Manish'),
    (14, '18BCE014', 'Nitin'),
    (15, '18BCE015', 'Ojas'),
    (16, '18BCE016', 'Piyush'),
    (17, '18BCE017', 'Raj'),
    (18, '18BCE018', 'Sagar'),
    (19, '18BCE019', 'Tushar'),
    (20, '18BCE020', 'Utkarsh')";
$s = $dbo->conn->prepare($c);
try{
    $s->execute();
}
catch(PDOException $o){
    echo ("<br> duplicate entry ");
}

$c = "insert into faculty_details
(id, user_name, password, name)
values
    (1, 'faculty1', 'faculty1', 'Faculty 1'),
    (2, 'faculty2', 'faculty2', 'Faculty 2'),
    (3, 'faculty3', 'faculty3', 'Faculty 3'),
    (4, 'faculty4', 'faculty4', 'Faculty 4'),
    (5, 'faculty5', 'faculty5', 'Faculty 5'),
    (6, 'faculty6', 'faculty6', 'Faculty 6'),
    (7, 'faculty7', 'faculty7', 'Faculty 7'),
    (8, 'faculty8', 'faculty8', 'Faculty 8'),
    (9, 'faculty9', 'faculty9', 'Faculty 9'),
    (10, 'faculty10', 'faculty10', 'Faculty 10')";

$s = $dbo->conn->prepare($c);
try{
    $s->execute();
}
catch(PDOException $o){
    echo ("<br> duplicate entry ");
}

$c = "insert into session_details
(id, year, term)
values
    (1, 2020, 'even'),
    (2, 2020, 'odd')";
$s = $dbo->conn->prepare($c);
try{
    $s->execute();
}
catch(PDOException $o){
    echo ("<br> duplicate entry ");
}

$c = "insert into course_details
(id,title, code, credits)
values
    (1, 'Data Structures', 'CS101', 4),
    (2, 'Algorithms', 'CS102', 4),
    (3, 'Operating Systems', 'CS103', 4),
    (4, 'Computer Networks', 'CS104', 4),
    (5, 'Database Management Systems', 'CS105', 4),
    (6, 'Software Engineering', 'CS106', 4),
    (7, 'Computer Graphics', 'CS107', 4),
    (8, 'Artificial Intelligence', 'CS108', 4),
    (9, 'Machine Learning', 'CS109', 4),
    (10, 'Deep Learning', 'CS110', 4)";

$s = $dbo->conn->prepare($c);
try{
    $s->execute();
}
catch(PDOException $o){
    echo ("<br> duplicate entry ");
}

// if any record is already present in the course_registration table then delete it
clearTable($dbo, "course_registration");

$c = "insert into course_registration
(student_id, course_id, session_id)
values
    (:sid ,:cid, :sessid)";
$s = $dbo->conn->prepare($c);
// iterate over all the students
// for each student can choose max 3 subjects

for ($i = 1; $i <= 20; $i++) {
    for($j = 0;$j<3;$j++){
        $cid = rand(1, 10);
        // insert the selected couese into the course_registration table
        try{
            $s->execute([":sid"=>$i, ":cid"=>$cid, ":sessid"=>1]);
        }
        catch(PDOException $pe) {
            echo ("<br> duplicate entry ");
        }
        // repeat the same for the next session
        $cid = rand(1, 10);
        // insert the selected couese into the course_registration table
        try{
            $s->execute([":sid"=>$i, ":cid"=>$cid, ":sessid"=>2]);
        }
        catch(PDOException $pe) {
            echo ("<br> duplicate entry ");
        }
    }
}





clearTable($dbo, "course_allotment");

$c = "insert into course_allotment
(faculty_id, course_id, session_id)
values
    (:fid ,:cid, :sessid)";
$s = $dbo->conn->prepare($c);
// iterate over all the faculty
// for each student can choose max 2 courses

for ($i = 1; $i <= 10; $i++) {
    for($j = 0;$j<2;$j++){
        $cid = rand(1, 10);
        // insert the selected couese into the course_allotment table
        try{
            $s->execute([":fid"=>$i, ":cid"=>$cid, ":sessid"=>1]);
        }
        catch(PDOException $pe) {
            echo ("<br> duplicate entry ");
        }
        // repeat the same for the next session
        $cid = rand(1, 10);
        // insert the selected couese into the course_allotment table
        try{
            $s->execute([":fid"=>$i, ":cid"=>$cid, ":sessid"=>2]);
        }
        catch(PDOException $pe) {
            echo ("<br> duplicate entry ");
        }
    }
}


?>
