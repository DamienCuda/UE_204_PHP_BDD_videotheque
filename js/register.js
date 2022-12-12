$(document).ready(function() {

    // On initialise les variables.
    let usernameInput = $("#username");
    let emailInput = $("#email");
    let passwordInput = $("#pass");
    let passwordConfirmInput = $("#passconfirm");
    let verifUsername = false;
    let verifEmail = false;
    let verifPassword = false;
    let verifPasswordConfirm = false;

    // On définit une expression régulière pour les mots de passe et les emails
    const regexPassword = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
    const regexEmail = new RegExp(/^[a-zA-Z0-9][\-_\.\+\!\#\$\%\&\'\*\/\=\?\^\`\{\|]{0,1}([a-zA-Z0-9][\-_\.\+\!\#\$\%\&\'\*\/\=\?\^\`\{\|]{0,1})*[a-zA-Z0-9]@[a-zA-Z0-9][-\.]{0,1}([a-zA-Z][-\.]{0,1})*[a-zA-Z0-9]\.[a-zA-Z0-9]{1,}([\.\-]{0,1}[a-zA-Z]){0,}[a-zA-Z0-9]{0,}$/i);

    // Lors du clique sur s'inscire on vérifie les champs renseignés.
    $("#registerBtn").click(function(){

        // On empêche le formulaire de s'éxecuter.
        event.preventDefault();

        // Fonction de traitement des erreurs de remplissage de tout les input présent dans le formulaire d'inscription.
        $("#registerForm input").each(function(index){

            // On vérifie si l'input en cours est un mot de passe
            if($(this).attr("type") == "password"){

                // Si tout est ok on valide grâce à la class is-valid
                if($(this).val() != "" && regexPassword.test($(this).val())){
                    $(this).removeClass("is-invalid");
                    $(this).addClass("is-valid");
                    verifPassword = true;
                }else{
                    $(this).addClass("is-invalid");
                    $(this).removeClass("is-valid");
                    $("#validationPasswordFeedback").text("Le mot de passe doit contenir minimum 8 caractères, une majuscule, un chiffre et un caractère spécial.");
                    verifPassword = false;
                }

                // Si l'input en cours n'est pas un mot de passe on fait tout pareil sans l'expression régulière.
            }else if($(this).attr("type") == "email"){

                // Si tout est ok on valide grâce à la class is-valid
                if($(this).val() != "" && regexEmail.test($(this).val())){
                    $(this).removeClass("is-invalid");
                    $(this).addClass("is-valid");
                    verifEmail = true;
                }else{
                    $(this).addClass("is-invalid");
                    $(this).removeClass("is-valid");
                    $("#validationEmailFeedback").text("L'adresse email n'est pas valide.");
                    verifEmail = false;
                }
            } else{

                // Si l'input est vide on affiche une erreur via la class is-invalid.
                if($(this).val() == ""){
                    $(this).addClass("is-invalid");
                    $(this).removeClass("is-valid");
                    verifUsername = false;
                }else{
                    $(this).removeClass("is-invalid");
                    $(this).addClass("is-valid");
                    verifUsername = true;
                }

            }

        });

        // On check si les mot de passe correspondent et on affiche une erreur si ce n'est pas le cas
        if(passwordInput.val() != passwordConfirmInput.val()){
            passwordInput.addClass("is-invalid")
            passwordInput.removeClass("is-valid")
            passwordConfirmInput.addClass("is-invalid")
            passwordConfirmInput.removeClass("is-valid")

            $("#validationPasswordFeedback").text("Les mots de passes ne correspondent pas.");
            verifPasswordConfirm = false;
        }else{
            verifPasswordConfirm = true;
        }

        // On check si tout est bon avant de faire le traitement PHP
        if(verifUsername && verifEmail && verifPassword && verifPasswordConfirm){
            $.ajax({
                url: "php_assets/register.php", // URL de la page
                type: "POST", // GET ou POST
                data: {
                    username:usernameInput.val(),
                    email:emailInput.val(),
                    pass:passwordInput.val(),
                    passconfirm:passwordConfirmInput.val()
                }, // Paramètres envoyés à php
                dataType: "json", // Données en retour
                success: function(reponse) {
                    console.log(reponse)

                    // On affiche le traitement des erreurs côté PHP même si normalement on ne peut rien envoyer à PHP si tout n'est pas bon
                    // mais on les traites quand même au cas où.
                    if(reponse.status == "passNotCorrect"){
                        passwordInput.addClass("is-invalid");
                        passwordInput.removeClass("is-valid");
                        passwordConfirmInput.addClass("is-invalid");
                        passwordConfirmInput.removeClass("is-valid");
                        $("#validationPasswordFeedback").text("Le mot de passe doit contenir minimum 8 caractères, une majuscule, un chiffre et un caractère spécial.");
                    }

                    if(reponse.status == "emailInvalid"){
                        emailInput.addClass("is-invalid");
                        emailInput.removeClass("is-valid");
                        $("#validationEmailFeedback").text("L'adresse email n'est pas une adresse email valide.");
                    }

                    if(reponse.status == "passNotSame"){
                        passwordInput.addClass("is-invalid");
                        passwordInput.removeClass("is-valid");
                        passwordConfirmInput.addClass("is-invalid");
                        passwordConfirmInput.removeClass("is-valid");
                        $("#validationPasswordFeedback").text("Les mots de passes ne correspondent pas.");
                    }

                    if(reponse.status == "usernameEmpty"){
                        usernameInput.addClass("is-invalid");
                        usernameInput.removeClass("is-valid");
                        $("#validationUsernameFeedback").text("Veuillez renseigner un nom d'utilisateur.");
                    }

                    if(reponse.status == "emailEmpty"){
                        usernameInput.addClass("is-invalid");
                        usernameInput.removeClass("is-valid");
                        $("#validationEmailFeedback").text("Veuillez renseigner une adresse email.");
                    }

                    if(reponse.status == "passEmpty"){
                        passwordInput.addClass("is-invalid");
                        passwordInput.removeClass("is-valid");
                    }

                    if(reponse.status == "passConfEmpty"){
                        passwordConfirmInput.addClass("is-invalid");
                        passwordConfirmInput.removeClass("is-valid");
                    }

                    if(reponse.status == "userExiste"){
                        usernameInput.addClass("is-invalid");
                        usernameInput.removeClass("is-valid");
                        $("#validationUsernameFeedback").text("Cet utilisateur existe déjà.");
                    }

                    if(reponse.status == "success"){

                        usernameInput.val("");
                        passwordInput.val("");
                        passwordConfirmInput.val("");
                        emailInput.val("");

                        usernameInput.removeClass("is-valid");
                        usernameInput.removeClass("is-invalid");
                        passwordInput.removeClass("is-valid");
                        passwordInput.removeClass("is-invalid");
                        passwordConfirmInput.removeClass("is-valid");
                        passwordConfirmInput.removeClass("is-invalid");
                        emailInput.removeClass("is-valid");
                        emailInput.removeClass("is-invalid");

                        $('#registerModal').modal('show');

                    }
                },
                error:function(error){
                    console.log(error)
                }
            });
        }

    });

    // On vérifie les changement sur les champs en temps réel
    usernameInput.keyup(function(){
       if(usernameInput.val() != ""){
           usernameInput.addClass("is-valid");
           usernameInput.removeClass("is-invalid");

           // On vérifie si l'username est disponible.
           $.ajax({
               url: "php_assets/verif_username.php", // URL de la page
               type: "POST", // GET ou POST
               data: {
                   username:usernameInput.val()
               }, // Paramètres envoyés à php
               dataType: "json", // Données en retour
               success: function(reponse) {
                   console.log(reponse)

                   // On affiche le resultat
                   if(reponse.status == "usernameExiste"){
                       usernameInput.addClass("is-invalid")
                       usernameInput.removeClass("is-valid")
                       $("#validationUsernameFeedback").text("Cet utilisateur existe déjà.");
                   }else{
                       usernameInput.removeClass("is-invalid")
                       usernameInput.addClass("is-valid")
                   }
               },
               error:function(error){
                   console.log(error)
               }
           });

       }else{
           usernameInput.removeClass("is-valid");
           usernameInput.addClass("is-invalid");
       }
    });

    emailInput.keyup(function(){
        if(emailInput.val() != "" && regexEmail.test(emailInput.val())){
            emailInput.addClass("is-valid");
            emailInput.removeClass("is-invalid");
        }else{
            emailInput.removeClass("is-valid");
            emailInput.addClass("is-invalid");
            $("#validationEmailFeedback").text("L'adresse email n'est pas une adresse email valide.");
        }
    });

    passwordInput.keyup(function(){
        if(passwordInput.val() != "" && regexPassword.test(passwordInput.val())){
            passwordInput.addClass("is-valid");
            passwordInput.removeClass("is-invalid");
        }else{
            passwordInput.removeClass("is-valid");
            passwordInput.addClass("is-invalid");
        }
    });

    passwordConfirmInput.keyup(function(){
        if(passwordConfirmInput.val() != "" && regexPassword.test(passwordConfirmInput.val()) && passwordConfirmInput.val() == passwordInput.val()){
            passwordConfirmInput.addClass("is-valid");
            passwordConfirmInput.removeClass("is-invalid");
        }else{
            passwordConfirmInput.removeClass("is-valid");
            passwordConfirmInput.addClass("is-invalid");

            if(passwordConfirmInput.val() != passwordInput.val()){
                $("#validationPasswordFeedback").text("Les mots de passes ne correspondent pas.");
            }else{
                $("#validationPasswordFeedback").text("Le mot de passe doit contenir minimum 8 caractères, une majuscule, un chiffre et un caractère spécial.");
            }
        }
    });


});