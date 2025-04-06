/*
$.ajax({
        url:"",
        type:"",
        dataType:"",
        data:{},
        beforeSend:function(e)
        {

        },
        success:function(rv)
        {
            
        },
        error:function(e)
        {
            
        }
    });
*/

function getSessionHTML(rv){
    let x = ``;
    x=`<option value = -1>SELECT ONE</option>`;
    let i = 0;
    for(i=0;i<rv.length;i++){
        let cs=rv[i];
        x=x+`<option value = ${cs['id']}>${cs['year']+" "+cs['term']}</option>`;
    }
    return x;
}

function loadSession(classlist){
    $.ajax({
        url:"ajaxhandler/attendanceAJAX.php",
        type:"POST",
        dataType:"json",
        data:{action : "getSession"},
        beforeSend:function(e)
        {

        },
        success:function(rv)
        {
            //alert(JSON.stringify(rv));
            let x = getSessionHTML(rv);
            $("#ddlclass").html(x);
        },
        error:function(e)
        {
            alert("OOPS!")
        }
    });
}

function getCourseCardHTML(rv){
    let x = ``; 
    for(let i = 0; i < rv.length; i++){  
        let cc = rv[i];
        x += `<div class="classcard" data-classobject='${JSON.stringify(cc)}'>${cc['code']}</div>`;
    }
    return x;
}

function fetchFacultyCourses(facid,sesssionid){
    //get all the course taken by the logged in faculty
    $.ajax({
        url:"ajaxhandler/attendanceAJAX.php",
        type:"POST",
        dataType:"json",
        data:{facid:facid ,sesssionid:sesssionid, action:"getFacultyCourses"},
        beforeSend:function(e)
        {

        },
        success:function(rv)
        {
            //alert(JSON.stringify(rv));
            let x = getCourseCardHTML(rv);
            $("#classlistarea").html(x)
        },
        error:function(e)
        {
            
        }
    });

}

function getClassdetailsAreaHTML(classobject){
    let dobj = new Date();
    let year = dobj.getFullYear();
    let month = dobj.getMonth() + 1;
    let day = dobj.getDate();
    month = month < 10 ? "0" + month : month;
    day = day < 10 ? "0" + day : day;
    let ondate = `${year}-${month}-${day}`;



    let x = `<div class="classdetails">
            <div class="code-area">${classobject['code']}</div>
            <div class="title-area">${classobject['title']}</div>
            <div class="ondate-area">
                <input type="date" value = '${ondate}' id='dtpondate'>
            </div>
        </div>`;
    return x;
}

function getStudentListHTML(studentList){
    let x = `<div class="studentlist">
    <label>STUDENT LIST</label>
    </div>`;
    let i =0;
    for(i=0;i<studentList.length;i++){
        let cs=studentList[i];
        let checkedState=``;
        let rowcolor='absentcolor';
        if(cs['isPresent']=='YES'){
            checkedState=`checked`;
            rowcolor='presentcolor';
        }
        //we will make some change to look different when checked and unchacked

        x=x+`<div class="studentdetails ${rowcolor}" id="student${cs['id']}">
        <div class="slno-area">${(i+1)}</div>
        <div class="rollno-area">${cs['roll_no']}</div>
        <div class="name-area">${cs['name']}</div>
        <div class="checkbox-area" data-studentid = '${cs['id']}'>
            <input type="checkbox"class = "cbpresent" data-studentid = '${cs['id']}' ${checkedState}>
        </div>
        </div>`;
    }
    x=x+`<div class="reportsection">
            <button id="btnReport">REPORT</button>
        </div>
        <div id="divReport"></div>`;
   
    return x;
}

function fetchStudentList(sessionid,classid,facid,ondate){
    $.ajax({
        url:"ajaxhandler/attendanceAJAX.php",
        type:"POST",
        dataType:"json",
        data:{facid:facid,ondate:ondate,sessionid:sessionid,classid:classid,action:"getStudentList"},
        beforeSend:function(e)
        {

        },
        success:function(rv)
        {
           // alert(JSON.stringify(rv));
            //get the student list html
            let x = getStudentListHTML(rv);
            $("#studentlistarea").html(x);

        },
        error:function(e)
        {
            
        }
    });
}

function saveAttendance(studentid,courseid,facultyid,sessionid,ondate,ispresent){
    $.ajax({
        url:"ajaxhandler/attendanceAJAX.php",
        type:"POST",
        dataType:"json",
        data:{studentid:studentid,courseid:courseid,facultyid:facultyid,sessionid:sessionid,ondate:ondate,ispresent:ispresent,action:"saveattendance"},
        beforeSend:function(e)
        {

        },
        success:function(rv)
        {
            //alert(JSON.stringify(rv));
            if(ispresent=="YES"){
                $("#student"+studentid).removeClass('absentcolor');
                $("#student"+studentid).addClass('presentcolor');
            }
            else{
                $("#student"+studentid).removeClass('presentcolor');
                $("#student"+studentid).addClass('absentcolor');
            }
        },
        error:function(e)
        {
            alert("oooopppssss!!")
        }
    });
}

function downloadCSV(sessionid, classid, facid) {
    $.ajax({
        url: "ajaxhandler/attendanceAJAX.php",
        type: "POST",
        dataType: "json",
        data: { sessionid: sessionid, classid: classid, facid: facid, action: "downloadReport" },
        success: function (rv) {
            if (rv.filename) {
                let downloadLink = document.createElement("a");
                downloadLink.href = rv.filename; 
                downloadLink.download = "AttendanceReport.csv"; 
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            } else {
                alert("File not found!");
            }
        },
        error: function (e) {
            alert("Error downloading file!");
        }
    });
}


$(function(e)
{
    $(document).on("click","#btnLogout",function(ee)
    {
        $.ajax(
            {
                url:"ajaxhandler/logoutAjax.php",
                type:"POST",
                dataType:"json",
                data:{id:1},
                beforeSend:function(e){

                },
                success:function(e){
                    document.location.replace("login.php");
                },
                error:function(e){
                    alert("something went wrong!");
                }
            }
        );
    });

    loadSession();
    $(document).on("change","#ddlclass",function(e){
        $("#classlistarea").html(``);
        $("#classdetailsarea").html(``);
        $("#studentlistarea").html(``);
        let si = $("#ddlclass").val();
        
        if(si!=-1){
            //alert(si);
            let sesssionid = si;
            let facid = $("#hiddenFacId").val()
            fetchFacultyCourses(facid,sesssionid);
        }
    });

    $(document).on("click",".classcard",function(e){
        let classobject=$(this).data('classobject');
        //remember the class id
        $("#hiddenSelectedCourseID").val(classobject['id']);
        //alert(JSON.stringify(s));
        let x = getClassdetailsAreaHTML(classobject);
        $("#classdetailsarea").html(x)
        //now fill the student list for session and course
        let sessionid=$("#ddlclass").val();
        let classid=classobject['id'];
        let facid=$("#hiddenFacId").val();
        let ondate = $("#dtpondate").val(); 
        if(sessionid!=-1){
            fetchStudentList(sessionid,classid,facid,ondate);
        }
        
    });

    $(document).on("click",".cbpresent",function(e){
        // alert("ok");
        let ispresent=this.checked;
        if(ispresent){
            ispresent="YES";
        }
        else{
            ispresent="NO";
        }
        let studentid=$(this).data('studentid');
        let courseid=$("#hiddenSelectedCourseID").val();
        let facultyid=$("#hiddenFacId").val();
        let sessionid=$("#ddlclass").val();
        let ondate=$("#dtpondate").val();
        //alert(studentid+" "+courseid+" "+facultyid+" "+session);
        saveAttendance(studentid,courseid,facultyid,sessionid,ondate,ispresent);
    });
    $(document).on("change","#dtpondate",function(e){
        //alert($("#dtpondate").val());
        let sessionid=$("#ddlclass").val();
        let classid=$("#hiddenSelectedCourseID").val();
        let facid=$("#hiddenFacId").val();
        let ondate = $("#dtpondate").val(); 
        if(sessionid!=-1){
            fetchStudentList(sessionid,classid,facid,ondate);
        }
    });
    $(document).on("click","#btnReport",function(){
        alert("clicked");
        let sessionid=$("#ddlclass").val();
        let classid=$("#hiddenSelectedCourseID").val();
        let facid=$("#hiddenFacId").val(); 
        downloadCSV(sessionid,classid,facid);
    });
});