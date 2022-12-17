$(document).ready(function() {
    let timeout = null;
    $("#search_user").keyup(function(e){
        clearTimeout(timeout);
        timeout = setTimeout(function () {

            $("#result-search-user").html('')

            var search =  $("#search_user").val();

            if(search != "") {
                //loading
                $("#loading").css("display", "block");
                $(".loader_search").animate({width: "100%"}, 1000, function() {
                    $("#loading").css("display", "none");
                });

                $("#title-search").css("display", "flex");
                $("#result-search-user").css("display", "flex");

                $.ajax({
                    type: 'GET',
                    url: 'php_assets/search_user.php',
                    data: 'search=' + encodeURIComponent(search),
                    success: function (data) {
                        console.log(data)
                        if (data.trim() != "") {
                            $("#result-search-user").append(data);
                        } else {
                            console.log("rien")
                            $("#result-search-user").html("<small class='text-light'>Aucun résultat</small>");
                        }
                    }
                });
            }else{
                $("#title-search").hide();
                $("#result-search-user").css("display", "none");
            }
        }, 100);
    });

    $("#search_user").click(function(){
        $("#title-search").css("display", "flex");
        $("#result-search-user").css("display", "flex");
    });

    // On détecte les clicks en dehors du champs de recherche pour le fermer.

    $("#current_locations").click(function(){
        $("#title-search").css("display", "none");
        $("#result-search-user").css("display", "none");
    });

    $("#user_infos_container").click(function(){
        $("#title-search").css("display", "none");
        $("#result-search-user").css("display", "none");
    });

    $("#past_locations").click(function(){
        $("#title-search").css("display", "none");
        $("#result-search-user").css("display", "none");
    });

});