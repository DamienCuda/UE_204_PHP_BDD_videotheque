$(document).ready(function() {

    // On initialise les variables.
    let usernameInputLogin = $("#usernameLogin");
    let passwordInputLogin = $("#passLogin");
    let verifUsernameLogin = false;
    let verifPasswordLogin = false;

    // On définit une expression régulière pour les mots de passe
    const regexPassword = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");

    // Lors du clique sur se connecter on vérifie les champs renseignés.
    $("#loginBtn").click(function(){
        event.preventDefault();

        $("#loginForm input").each(function(index){
            // On vérifie si l'input en cours est un mot de passe
            if($(this).attr("type") == "password"){

                // Si tout est ok on valide grâce à la class is-valid
                if($(this).val() != "" && regexPassword.test($(this).val())){
                    $(this).removeClass("is-invalid");
                    $(this).addClass("is-valid");
                    verifPasswordLogin = true;
                }else{
                    $(this).addClass("is-invalid");
                    $(this).removeClass("is-valid");
                    $("#validationPasswordFeedbackLogin").text("Le mot de passe doit contenir minimum 8 caractères, une majuscule, un chiffre et un caractère spécial.");
                    verifPasswordLogin = false;
                }

                // Si l'input en cours n'est pas un mot de passe on fait tout pareil sans l'expression régulière.
            }else{
                // Si l'input est vide on affiche une erreur via la class is-invalid.
                if($(this).val() == ""){
                    $(this).addClass("is-invalid");
                    $(this).removeClass("is-valid");
                    verifUsernameLogin = false;
                }else{
                    $(this).removeClass("is-invalid");
                    $(this).addClass("is-valid");
                    verifUsernameLogin = true;
                }
            }
        });

        // On check si tout est bon avant de faire le traitement PHP
        if(verifUsernameLogin && verifPasswordLogin){
            $("#errorLogin").removeClass("is-invalid")
            $("#errorLogin").addClass("is-valid")

            // On execute le script pour le login

            $.ajax({
                url: "php_assets/login.php", // URL de la page
                type: "POST", // GET ou POST
                data: {
                    username:usernameInputLogin.val(),
                    pass:passwordInputLogin.val()
                }, // Paramètres envoyés à php
                dataType: "json", // Données en retour
                success: function(reponse) {
                    console.log(reponse)

                    // On affiche le resultat
                    if(reponse.status == "passNotCorrect"){
                        $("#errorLogin").addClass("is-invalid");
                        $("#errorLogin").removeClass("is-valid");
                    }

                    if(reponse.status == "usernameNotCorrect"){
                        $("#errorLogin").addClass("is-invalid");
                        $("#errorLogin").removeClass("is-valid");
                    }

                    if(reponse.status == "usernameEmpty"){
                        usernameInputLogin.addClass("is-invalid");
                        usernameInputLogin.removeClass("is-valid");
                    }

                    if(reponse.status == "passEmpty"){
                        passwordInputLogin.addClass("is-invalid");
                        passwordInputLogin.removeClass("is-valid");
                    }

                    if(reponse.status == "success"){
                        window.location="catalogue.php";
                    }
                },
                error:function(error){
                    console.log(error)
                }
            });

        }else{
            $("#errorLogin").addClass("is-invalid")
            $("#errorLogin").removeClass("is-valid")
        }

    });

    // On vérifie les changement sur les champs en temps réel
    usernameInputLogin.keyup(function(){
        if(usernameInputLogin.val() != ""){
            usernameInputLogin.addClass("is-valid");
            usernameInputLogin.removeClass("is-invalid");
        }else{
            usernameInputLogin.removeClass("is-valid");
            usernameInputLogin.addClass("is-invalid");
        }
    });

    passwordInputLogin.keyup(function(){
        if(passwordInputLogin.val() != "" && regexPassword.test(passwordInputLogin.val())){
            passwordInputLogin.addClass("is-valid");
            passwordInputLogin.removeClass("is-invalid");
        }else{
            passwordInputLogin.removeClass("is-valid");
            passwordInputLogin.addClass("is-invalid");
        }
    });

});