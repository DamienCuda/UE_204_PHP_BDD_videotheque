let is_edit = false;

$(".edit_btn").click(function(){

    if(is_edit === false){

        // On met is_edit sur true pour notifier qu'on edit une ligne.
        is_edit = true;

        // On récupère la ligne à éditer
        let tr_user = this.parentNode.parentNode.parentNode;

        // Onrécupère les infos pour les mettre dans des inputs.
        let userID = tr_user.children[1].textContent;
        let utilisateur = tr_user.children[3];
        let utilisateur_value = tr_user.children[3].textContent;
        let email = tr_user.children[4];
        let email_value = tr_user.children[4].textContent;
        let password = tr_user.children[5];

        // On détermine les regex pour le mot de passe et l'adresse email
        const regexEmail = new RegExp(/^[a-zA-Z0-9][\-_\.\+\!\#\$\%\&\'\*\/\=\?\^\`\{\|]{0,1}([a-zA-Z0-9][\-_\.\+\!\#\$\%\&\'\*\/\=\?\^\`\{\|]{0,1})*[a-zA-Z0-9]@[a-zA-Z0-9][-\.]{0,1}([a-zA-Z][-\.]{0,1})*[a-zA-Z0-9]\.[a-zA-Z0-9]{1,}([\.\-]{0,1}[a-zA-Z]){0,}[a-zA-Z0-9]{0,}$/i);
        const regexPassword = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");

        let verifUsername = false;
        let verifEmail = false;

        tr_user.setAttribute("id", "editing_user")


        // On insert un input avec les différentes valeurs récupérer.
        utilisateur.innerHTML =
            '<input type="text" name="username" id="username" class="form-control" placeholder="Nom d\'utilisateur..." value="'+ utilisateur_value +'" autocomplete="off">';

        email.innerHTML =
            '<input type="email" name="email" id="email" class="form-control" placeholder="Adresse email..." value="'+ email_value +'" autocomplete="off">';
        password.innerHTML =
            '<input type="password" name="pass" id="password" class="form-control" placeholder="Mot de passe (optionnel)" autocomplete="off">';

        // On remplace les bouton par 1 bouton de validation et un autre d'annulation.
        let btnZone = tr_user.children[7].children[0];
        btnZone.innerHTML = '<button><i class=\'bx bxs-check-circle\' title="valider" id="valid_edit"></i><i class=\'bx bxs-x-circle\' title="Annuler" id="skip_edit"></i></button>';

        // Si on valide on vérifie que tous les champs son bon
        $("#valid_edit").click(function(){
            // Fonction de traitement des erreurs de remplissage de tout les input présent dans le formulaire d'inscription.
            $("#editing_user input").each(function(index){

                // On vérifie si l'input en cours est un mot de passe
                if($(this).attr("type") == "password"){

                    // Dans ce cas là on fait rien car le mot de passe est optionnel.

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

            // Si toutes les verif sont ok.
            if(verifUsername && verifEmail){
                let usernameInput = $("#username");
                let emailInput = $("#email");
                let passwordInput = $("#password");

                $.ajax({
                    url: "php_assets/edit_user.php?id=" + userID, // URL de la page
                    type: "POST", // GET ou POST
                    data: {
                        username:usernameInput.val(),
                        email:emailInput.val(),
                        pass:passwordInput.val()
                    }, // Paramètres envoyés à php
                    dataType: "json", // Données en retour
                    success: function(reponse) {
                        console.log(reponse)

                        // On affiche le traitement des erreurs côté PHP même si normalement on ne peut rien envoyer à PHP si tout n'est pas bon
                        // mais on les traites quand même au cas où.
                        if(reponse.status == "passNotCorrect"){
                            passwordInput.addClass("is-invalid");
                            passwordInput.removeClass("is-valid");
                        }

                        if(reponse.status == "emailInvalid"){
                            emailInput.addClass("is-invalid");
                            emailInput.removeClass("is-valid");
                        }

                        if(reponse.status == "usernameEmpty"){
                            usernameInput.addClass("is-invalid");
                            usernameInput.removeClass("is-valid");
                        }

                        if(reponse.status == "emailEmpty"){
                            usernameInput.addClass("is-invalid");
                            usernameInput.removeClass("is-valid");
                        }

                        if(reponse.status == "userExiste"){
                            usernameInput.addClass("is-invalid");
                            usernameInput.removeClass("is-valid");
                        }

                        if(reponse.status == "success"){
                            console.log(reponse.status)
                            is_edit = false;
                            window.location.href = 'gestion_user.php';
                        }
                    },
                    error:function(error){
                        console.log(error)
                    }
                });
            }
        });

        $("#skip_edit").click(function(){
            window.location.href = 'gestion_user.php';
        })

        let usernameInput = $("#username");
        let emailInput = $("#email");
        let passwordInput = $("#password");

        // On vérifie les changement sur les champs en temps réel
        usernameInput.keyup(function(){
            if(usernameInput.val() != ""){
                usernameInput.addClass("is-valid");
                usernameInput.removeClass("is-invalid");

                // On vérifie si l'username est disponible.
                $.ajax({
                    url: "php_assets/verif_username_edit_user.php", // URL de la page
                    type: "POST", // GET ou POST
                    data: {
                        username:usernameInput.val(),
                        id:userID,
                    }, // Paramètres envoyés à php
                    dataType: "json", // Données en retour
                    success: function(reponse) {
                        console.log(reponse)

                        // On affiche le resultat
                        if(reponse.status == "usernameExiste"){
                            usernameInput.addClass("is-invalid")
                            usernameInput.removeClass("is-valid")
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

    }
});

