$(document).ready(function() {

    // On détecte le click sur le bouton start du trailer.
   $("#movie_trailer_btn").click(function(){

       // On format le titre du film pour l'url de la requête pour l'API.
       let title = $(".movie-title")[0].textContent;
       let tite_custom = title.replace(/ /g,"+").trim();

        // On récupère l'ID du film grâce à l'API.
       $.ajax({
           url: 'https://api.themoviedb.org/3/search/movie?api_key=81aa13e0da8dedca01153512a297a51b&query='+ tite_custom +'', // URL de la page
           type: "GET", // GET ou POST
           data: {}, // Paramètres envoyés à php
           dataType: "json", // Données en retour
           success: function(reponse) {
               let movie_id = reponse.results[0].id;

               // On récupère l'ID de la vidéo youtube grâce à l'ID du film précédement récupéré.
               $.ajax({
                   url: 'https://api.themoviedb.org/3/movie/'+ movie_id +'/videos?api_key=81aa13e0da8dedca01153512a297a51b&language=fr-FR', // URL de la page
                   type: "GET", // GET ou POST
                   data: {}, // Paramètres envoyés à php
                   dataType: "json", // Données en retour
                   success: function(reponse) {
                       console.log(reponse)

                       // Si on aucune bande d'annonce en français à été trouvé, on cherche une bande d'annonce sans sépcifier la langue.
                       if(reponse.results == ""){
                           $.ajax({
                               url: 'https://api.themoviedb.org/3/movie/'+ movie_id +'/videos?api_key=81aa13e0da8dedca01153512a297a51b', // URL de la page
                               type: "GET", // GET ou POST
                               data: {}, // Paramètres envoyés à php
                               dataType: "json", // Données en retour
                               success: function(reponse) {
                                   console.log(reponse)

                                   // Si toujours rien on le notifie à l'utilisateur
                                   if(reponse.results == ""){
                                       $('#trailerModal').modal('show');
                                       $("#trailerModal .modal-body").html("<p>Aucune bande d'annonce n'a été trouvé pour ce film.</p>")
                                   }else{
                                       if(reponse.results[0].site == "YouTube"){

                                           // On affiche la vidéo dans une modal large.
                                           let url = "https://www.youtube.com/embed/" + reponse.results[0].key
                                           $("#trailer_player").attr("src", url);
                                           $('#trailerModal').modal('show');
                                       }
                                   }
                               },
                               error:function(error){
                                   console.log(error)
                               }
                           });
                       }else{
                           if(reponse.results[0].site == "YouTube"){

                               // On affiche la vidéo dans une modal large.
                               let url = "https://www.youtube.com/embed/" + reponse.results[0].key
                               $("#trailer_player").attr("src", url);
                               $('#trailerModal').modal('show');
                           }
                       }
                   },
                   error:function(error){
                       console.log(error)
                   }
               });
           },
           error:function(error){
               console.log(error)
           }
       });
   });

   // Fonction de fermture des trailer lorsqu'on quitte la modal
    $('#trailerModal').on('hidden.bs.modal', function (e) {

        // On récupère l'ID du film dans le GET
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        let movie_id = urlParams.get('movie');

        window.location.href = 'catalogue.php?movie=' + movie_id;
    });

});