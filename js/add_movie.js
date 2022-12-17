$(document).ready(function () {

    // On initialise les variables.
    let movie_title = $("#movie_title");
    let movie_img = $("#movie_img");
    let movie_genre = $("#movie_genre");
    let movie_actor = $("#movie_actor");
    let movie_director = $("#movie_director");
    let annee_sortie = $("#annee_sortie");
    let movie_duration = $("#movie_duration");
    let movie_synopsis = $("#movie_synopsis");
    let price_movie = $("#price_movie");

    // On initialise les variables de vérifications
    let movie_title_verif = false;
    let movie_img_verif = false;
    let movie_genre_verif = false;
    let movie_actor_verif = false;
    let movie_director_verif = false;
    let annee_sortie_verif = false;
    let movie_duration_verif = false;
    let movie_synopsis_verif = false;
    let price_movie_verif = false;

    let regexTime = new RegExp("^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$");

    // On vérifie les changement sur les champs en temps réel
    movie_title.keyup(function () {
        if (movie_title.val() != "") {
            movie_title.addClass("is-valid");
            movie_title.removeClass("is-invalid");
        } else {
            movie_title.removeClass("is-valid");
            movie_title.addClass("is-invalid");
        }
    });

    movie_img.change(function () {
        if (movie_img.val() != "") {
            $("#overlay").css("border", "solid 3px green")
            $("#overlay").html("<i class='bx bx-check-circle valid-img'></i>")
        } else {
            $("#overlay").css("border", "solid 3px red")
            $("#overlay").html("<i class='bx bx-error-circle error-img'></i>")
        }
    });

    movie_genre.keyup(function () {
        if (movie_genre.val() != "") {
            movie_genre.addClass("is-valid");
            movie_genre.removeClass("is-invalid");
        } else {
            movie_genre.removeClass("is-valid");
            movie_genre.addClass("is-invalid");
        }
    });

    movie_actor.keyup(function () {
        if (movie_actor.val() != "") {
            movie_actor.addClass("is-valid");
            movie_actor.removeClass("is-invalid");
        } else {
            movie_actor.removeClass("is-valid");
            movie_actor.addClass("is-invalid");
        }
    });

    movie_director.keyup(function () {
        if (movie_director.val() != "") {
            movie_director.addClass("is-valid");
            movie_director.removeClass("is-invalid");
        } else {
            movie_director.removeClass("is-valid");
            movie_director.addClass("is-invalid");
        }
    });

    annee_sortie.keyup(function () {
        if (annee_sortie.val() != "" && !isNaN(annee_sortie.val())) {
            annee_sortie.addClass("is-valid");
            annee_sortie.removeClass("is-invalid");
        } else {
            annee_sortie.removeClass("is-valid");
            annee_sortie.addClass("is-invalid");
        }
    });

    movie_duration.keyup(function () {
        if (movie_duration.val() != "" && regexTime.test(movie_duration.val())) {
            movie_duration.addClass("is-valid");
            movie_duration.removeClass("is-invalid");
        } else {
            movie_duration.removeClass("is-valid");
            movie_duration.addClass("is-invalid");
        }
    });

    movie_synopsis.keyup(function () {
        if (movie_synopsis.val() != "" && movie_synopsis.val().length > 20) {
            movie_synopsis.addClass("is-valid");
            movie_synopsis.removeClass("is-invalid");
        } else {
            movie_synopsis.removeClass("is-valid");
            movie_synopsis.addClass("is-invalid");
        }
    });

    price_movie.keyup(function () {
        if (price_movie.val() != "" && !isNaN(price_movie.val())) {
            price_movie.addClass("is-valid");
            price_movie.removeClass("is-invalid");
        } else {
            price_movie.removeClass("is-valid");
            price_movie.addClass("is-invalid");
        }
    });

    $("#add_movie_btn").click(function () {

        // On verifie tout les champs
        if (movie_title.val() != "") {
            movie_title.addClass("is-valid");
            movie_title.removeClass("is-invalid");
            movie_title_verif = true;
        } else {
            movie_title.removeClass("is-valid");
            movie_title.addClass("is-invalid");
            movie_title_verif = false;
        }

        if (movie_img.val() != "") {
            $("#overlay").css("border", "solid 3px green");
            $("#overlay").html("<i class='bx bx-check-circle valid-img'></i>");
            movie_img_verif = true;
        } else {
            $("#overlay").css("border", "solid 3px red");
            $("#overlay").html("<i class='bx bx-error-circle error-img'></i>");
            movie_img_verif = false;
        }

        if (movie_genre.val() != "") {
            movie_genre.addClass("is-valid");
            movie_genre.removeClass("is-invalid");
            movie_genre_verif = true;
        } else {
            movie_genre.removeClass("is-valid");
            movie_genre.addClass("is-invalid");
            movie_genre_verif = false;
        }

        if (movie_actor.val() != "") {
            movie_actor.addClass("is-valid");
            movie_actor.removeClass("is-invalid");
            movie_actor_verif = true;
        } else {
            movie_actor.removeClass("is-valid");
            movie_actor.addClass("is-invalid");
            movie_actor_verif = false;
        }

        if (movie_director.val() != "") {
            movie_director.addClass("is-valid");
            movie_director.removeClass("is-invalid");
            movie_director_verif = true;
        } else {
            movie_director.removeClass("is-valid");
            movie_director.addClass("is-invalid");
            movie_director_verif = false;
        }

        if (annee_sortie.val() != "" && !isNaN(annee_sortie.val())) {
            annee_sortie.addClass("is-valid");
            annee_sortie.removeClass("is-invalid");
            annee_sortie_verif = true;
        } else {
            annee_sortie.removeClass("is-valid");
            annee_sortie.addClass("is-invalid");
            annee_sortie_verif = false;
        }

        if (movie_duration.val() != "" && regexTime.test(movie_duration.val())) {
            movie_duration.addClass("is-valid");
            movie_duration.removeClass("is-invalid");
            movie_duration_verif = true;
        } else {
            movie_duration.removeClass("is-valid");
            movie_duration.addClass("is-invalid");
            movie_duration_verif = false;
        }

        if (movie_synopsis.val() != "" && movie_synopsis.val().length > 20) {
            movie_synopsis.addClass("is-valid");
            movie_synopsis.removeClass("is-invalid");
            movie_synopsis_verif = true;
        } else {
            movie_synopsis.removeClass("is-valid");
            movie_synopsis.addClass("is-invalid");
            movie_synopsis_verif = false;
        }

        if (price_movie.val() != "" && !isNaN(price_movie.val())) {
            price_movie.addClass("is-valid");
            price_movie.removeClass("is-invalid");
            price_movie_verif = true;
        } else {
            price_movie.removeClass("is-valid");
            price_movie.addClass("is-invalid");
            price_movie_verif = false;
        }

        if(movie_title_verif && movie_img_verif && movie_actor_verif && movie_director_verif && annee_sortie_verif && movie_duration_verif && movie_synopsis_verif && price_movie_verif){

            let fd = new FormData();

            let files = $('#movie_img')[0].files;
            let movie_title = $('#movie_title').val();
            let movie_genre = $('#movie_genre').val();
            let movie_actor = $('#movie_actor').val();
            let movie_director = $('#movie_director').val();
            let annee_sortie = $('#annee_sortie').val();
            let movie_duration = $('#movie_duration').val();
            let movie_synopsis = $('#movie_synopsis').val();
            let price_movie = $('#price_movie').val();

            fd.append('movie_img',files[0]);
            fd.append('movie_title',movie_title);
            fd.append('movie_genre',movie_genre);
            fd.append('movie_actor',movie_actor);
            fd.append('movie_director',movie_director);
            fd.append('annee_sortie',annee_sortie);
            fd.append('movie_duration',movie_duration);
            fd.append('movie_synopsis',movie_synopsis);
            fd.append('price_movie',price_movie);

            $.ajax({
                url: "php_assets/add_movie.php", // URL de la page
                type: "POST", // GET ou POST
                data: fd, // Paramètres envoyés à php
                dataType: "json", // Données en retour
                contentType: false,
                processData: false,
                cache: false,
                success: function(reponse) {
                    console.log(reponse)

                    if(reponse.status == "success"){
                        window.location.href = 'catalogue.php';
                    }

                    if(reponse.status == "errorIMG"){
                        $("#overlay").css("border", "solid 3px red");
                        $("#overlay").html("<i class='bx bx-error-circle error-img'></i>");
                    }

                    if(reponse.status == "emptyTitle"){
                        movie_title.removeClass("is-valid");
                        movie_title.addClass("is-invalid");
                    }

                    if(reponse.status == "emptyGenre"){
                        movie_genre.removeClass("is-valid");
                        movie_genre.addClass("is-invalid");
                    }

                    if(reponse.status == "emptyActor"){
                        movie_actor.removeClass("is-valid");
                        movie_actor.addClass("is-invalid");
                    }

                    if(reponse.status == "emptyDirector"){
                        movie_director.removeClass("is-valid");
                        movie_director.addClass("is-invalid");
                    }

                    if(reponse.status == "emptyAnnee"){
                        annee_sortie.removeClass("is-valid");
                        annee_sortie.addClass("is-invalid");
                    }

                    if(reponse.status == "emptyDuration"){
                        movie_duration.removeClass("is-valid");
                        movie_duration.addClass("is-invalid");
                    }

                    if(reponse.status == "movie_synopsis"){
                        movie_synopsis.removeClass("is-valid");
                        movie_synopsis.addClass("is-invalid");
                    }

                    if(reponse.status == "emptyPrice"){
                        price_movie.removeClass("is-valid");
                        price_movie.addClass("is-invalid");
                    }

                    if(reponse.status == "emptyIMG"){
                        movie_img.removeClass("is-valid");
                        movie_img.addClass("is-invalid");
                    }

                },
                error:function(error){
                    console.log(error)
                }
            });
        }

    });

});