$(document).ready(function() {

    let emailValue = $("#email_zone").text()

    $("#btn_edit_profil").click(function(){
        $("#btn_edit_profil").addClass("d-none");
        $("#btn_edit_profil_skip").removeClass("d-none");
        $("#btn_edit_profil_valid").removeClass("d-none");

        $("#email_line").addClass("flex-column");
        $("#password_line").addClass("flex-column");
        $("#edit_zone_img").html("<label for='img_user'><i class='bx bxs-camera-plus'></i></label><input type='file' name='img_user' id='img_user' hidden>");
        $("#email_zone").html("<input type='text' class='form-control' name='email' id='email' value="+ emailValue +">");
        $("#email_zone").removeClass("ml-2");

        $("#password_line").removeClass("d-none");
        $("#password_zone").html("<input type='password' class='form-control' name='pass' id='pass' value='********'>");
    });

    $("#btn_edit_profil_skip").click(function(){
        $("#btn_edit_profil").removeClass("d-none");
        $("#btn_edit_profil_skip").addClass("d-none");
        $("#btn_edit_profil_valid").addClass("d-none");
        $("#edit_zone_img").html("");

        $("#email_zone").html(emailValue);

        $("#password_line").addClass("d-none");
        $("#edit_zone_img").html("");

        $("#email_line").removeClass("flex-column");
        $("#email_line").removeClass("d-flex align-items-start");
        $("#password_line").removeClass("flex-column");

    });
});