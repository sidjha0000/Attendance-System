function tryLogin() {
    let un = $("#txtUsername").val();
    let pw = $("#txtPassword").val();

    if (un.trim() !== "" && pw.trim() !== "") {
        $("#lockscreen").addClass("applylockscreen");
        

        $.ajax({
            url: "ajaxhandler/loginAjax.php",
            type: "POST",
            dataType: "json",
            data: { username: un, password: pw, action: "verifyUser" },
            beforeSend: function () {
                $("#diverror").removeClass("applyerrordiv");
            },
            success: function (rv) {
                $("#lockscreen").removeClass("applylockscreen"); 
                if (rv["status"] == "ALL OK") {
                    document.location.replace("attendance.php");
                } else {
                    $("#diverror").addClass("applyerrordiv");
                    $("#errormessage").text(rv["status"]);
                }
            },
            error: function (xhr, status, error) {
                $("#lockscreen").removeClass("applylockscreen");
                alert("Oops, something went wrong: " + error);
            },
        });
    }
}

$(function () {
    $(document).on("keyup", "input", function (e) {
        $("#diverror").removeClass("applyerrordiv");

        let un = $("#txtUsername").val();
        let pw = $("#txtPassword").val();
        if (un.trim() !== "" && pw.trim() !== "") {
            $("#btnLogin").removeClass("inactivecolor").addClass("activecolor");
        } else {
            $("#btnLogin").removeClass("activecolor").addClass("inactivecolor");
        }
    });

    $(document).on("click", "#btnLogin", function () {
        tryLogin();
    });
});
