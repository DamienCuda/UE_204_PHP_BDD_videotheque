$(document).ready(function() {

    // On récupère la value dans l'email avant modification.
    let emailValue = $("#email_zone").text()

    let verifEmail = false;
    let verifPassword = false;
    let verifImg = false;

    const regexPassword = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
    const regexEmail = new RegExp(/^[a-zA-Z0-9][\-_\.\+\!\#\$\%\&\'\*\/\=\?\^\`\{\|]{0,1}([a-zA-Z0-9][\-_\.\+\!\#\$\%\&\'\*\/\=\?\^\`\{\|]{0,1})*[a-zA-Z0-9]@[a-zA-Z0-9][-\.]{0,1}([a-zA-Z][-\.]{0,1})*[a-zA-Z0-9]\.[a-zA-Z0-9]{1,}([\.\-]{0,1}[a-zA-Z]){0,}[a-zA-Z0-9]{0,}$/i);

    $("#btn_edit_profil").click(function(){

        // On gère l'affichage des boutons.
        $("#btn_edit_profil").addClass("d-none");
        $("#btn_edit_profil_skip").removeClass("d-none");
        $("#btn_edit_profil_valid").removeClass("d-none");

        // On gère l'affichage des inputs.
        $("#email_line").addClass("flex-column");
        $("#password_line").addClass("flex-column");
        $("#edit_zone_img").html("<label for='img_user'><i class='bx bxs-camera-plus'></i></label><input type='file' name='img_user' id='img_user' hidden>");
        $("#email_zone").html("<input type='text' class='form-control' name='email_user' id='email_user' value="+ emailValue +">");
        $("#email_zone").removeClass("ml-2");
        $("#password_line").removeClass("d-none");
        $("#password_zone").html("<input type='password' class='form-control' name='pass_user' id='pass_user' placeholder='********'>");


        $("#email_user").keyup(function(){
            if($("#email_user").val() != "" && regexEmail.test($("#email_user").val())){
                $("#email_user").removeClass("is-invalid");
                $("#email_user").addClass("is-valid");
            }else{
                $("#email_user").addClass("is-invalid");
                $("#email_user").removeClass("is-valid");
            }
        });

        $("#pass_user").keyup(function(){
            if($("#pass_user").val() != "" && regexPassword.test($("#pass_user").val())){
                $("#pass_user").removeClass("is-invalid");
                $("#pass_user").addClass("is-valid");
            }else{
                $("#pass_user").addClass("is-invalid");
                $("#pass_user").removeClass("is-valid");
            }
        });

        $("#btn_edit_profil_valid").click(function(){

            if($("#email_user").val() != "" && regexEmail.test($("#email_user").val())){
                $("#email_user").removeClass("is-invalid");
                $("#email_user").addClass("is-valid");
                verifEmail = true;
            }else{
                $("#email_user").addClass("is-invalid");
                $("#email_user").removeClass("is-valid");
                verifEmail = false;
            }

            if($("#pass_user").val() != "" && regexPassword.test($("#pass_user").val())){
                $("#pass_user").removeClass("is-invalid");
                $("#pass_user").addClass("is-valid");
                verifPassword = true;
            }else{
                $("#pass_user").addClass("is-invalid");
                $("#pass_user").removeClass("is-valid");
                verifPassword = false;
            }

            if($("#img_user").val != ""){
                verifImg = true;
            }else{
                verifImg = false;
            }


            if(verifPassword || verifEmail || verifImg){

                let fd = new FormData();

                let files = $('#img_user')[0].files;
                let email_val = $('#email_user').val();
                let password_val = $('#pass_user').val();

                fd.append('avatar',files[0]);
                fd.append('email',email_val);
                fd.append('password',password_val);

                // On traite les données
                $.ajax({
                    url: "php_assets/edit_profil.php", // URL de la page
                    type: "POST", // GET ou POST
                    data: fd, // Paramètres envoyés à php
                    dataType: "json", // Données en retour
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function(reponse) {
                        console.log(reponse)

                        // On affiche le resultat
                        if(reponse.status == "emailInvalid"){
                            $("#email_user").addClass("is-invalid")
                            $("#email_user").removeClass("is-valid")
                        }else{
                            $("#email_user").removeClass("is-invalid")
                            $("#email_user").addClass("is-valid")
                        }

                        if(reponse.status == "passInvalid"){
                            $("#pass_user").addClass("is-invalid")
                            $("#pass_user").removeClass("is-valid")
                        }else{
                            $("#pass_user").removeClass("is-invalid")
                            $("#pass_user").addClass("is-valid")
                        }

                        if(reponse.status == "emailEmpty"){
                            $("#email_user").addClass("is-invalid");
                            $("#email_user").removeClass("is-valid");
                        }

                        if(reponse.status == "passEmpty"){
                            $("#pass_user").addClass("is-invalid");
                            $("#pass_user").removeClass("is-valid");
                        }

                        if(reponse.status == "success"){
                            window.location.href = 'espace_perso_user.php';
                        }

                    },
                    error:function(error){
                        console.log(error)
                        //window.location.href = 'espace_perso_user.php';
                    }
                });
            }
        });

    });

    $("#btn_edit_profil_skip").click(function(){

        // On gère l'affichage des boutons.
        $("#btn_edit_profil").removeClass("d-none");
        $("#btn_edit_profil_skip").addClass("d-none");
        $("#btn_edit_profil_valid").addClass("d-none");

        // On gère la suppression des inputs.
        $("#edit_zone_img").html("");
        $("#email_zone").html(emailValue);
        $("#password_line").addClass("d-none");
        $("#edit_zone_img").html("");
        $("#email_line").removeClass("flex-column");
        $("#email_line").removeClass("d-flex align-items-start");
        $("#password_line").removeClass("flex-column");

    });




});