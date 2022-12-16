$(document).ready(function() {
    let timeout = null;
    $("#search-movie").keyup(function(e){
        clearTimeout(timeout);
        timeout = setTimeout(function () {

            $("#result-search").html('')

            var search =  $("#search-movie").val();

            if(search != "") {
                //loading
                $("#loading").css("display", "block");
                $(".loader_search").animate({width: "100%"}, 1000, function() {
                    $("#loading").css("display", "none");
                });

                $("#movie_display").hide();

                $("#title-search").show();
                $("#result-search").css("display", "flex");
                $.ajax({
                    type: 'GET',
                    url: 'php_assets/search_movie.php',
                    data: 'search=' + encodeURIComponent(search),
                    success: function (data) {
                        console.log(data)
                        if (data.trim() != "") {
                            $("#result-search").append(data);
                        } else {
                            console.log("rien")
                            $("#result-search").html("<small class='text-light'>Aucun résultat</small>");
                        }
                    }
                });
            }else{
                $("#movie_display").show();
                $("#title-search").hide();
                $("#result-search").css("display", "none");
            }
        }, 100);
    });
});