$(document).ready(function() {

    let id = [];
    let btnOptions = $("#invoice-options-btn");

    // On detect un clique sur une checkbox
    $(".checkbox_table").click(function(){

        // Si une checkbox vaut false avant le click on determiné qu'elle devient true.
        if($(this).parent().hasClass("selected") == false){
            // Checkbox cochée.

            // On ajoute l'ID dans un array.
            id.push($(this).attr("id"));

            // On active le bouton des options.
            $("#invoice-options-btn").removeAttr("disabled");
        }else{
            // Checkbox décochée.

            // On récupère l'index ou se trouve la valeur à retirer.
            let index = id.indexOf($(this).attr("id"))
            if (index != -1) {
                // On retire la valeur de l'array.
                id.splice(index, 1);
            }

            // Si plus aucune checkbox n'est coché on re disabled le bouton des options
            if($(".selected").length === 1){
                $("#invoice-options-btn").attr("disabled", "true");
            }
        }
    });

    // On définit un timout car la blibliothèque "datatable" doit d'abord générer l'html.
    setTimeout(() => {
        $("#multiDelete").click(function(){

            // On vérifie que les champs sont bien séléctionné et que le tableau des ID n'est pas vide
            if($(".selected").length >= 1 && id.length >= 1) {

                // On transmet le tableau d'ID à AJAX
                $.ajax({
                    url: "php_assets/delete_user_multi.php", // URL de la page
                    type: "POST", // GET ou POST
                    data: {
                        id:id
                    }, // Paramètres envoyés à php
                    dataType: "json", // Données en retour
                    success: function(reponse) {
                        console.log(reponse)

                        if(reponse.status == "permissionError"){
                            $('#permissionError').modal('show');

                            $("#valid_permissionError").click(function(){
                                window.location.href = 'gestion_user.php';
                            });

                        }else{
                            window.location.href = 'gestion_user.php';
                        }


                    },
                    error:function(error){
                        console.log(error)
                    }
                });
            }
        });
    }, "100")

});