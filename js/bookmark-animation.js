let bookmark = document.getElementsByClassName("bookmark");

for(let i = 0; i < bookmark.length; i++){
    bookmark[i].addEventListener("mouseover", function(){
        bookmark[i].children[0].classList.add("bxs-bookmark")
        bookmark[i].children[0].classList.remove("bx-bookmark")
        bookmark[i].children[0].style.cursor = "pointer";
    });

    bookmark[i].addEventListener("mouseout", function(){
        bookmark[i].children[0].classList.remove("bxs-bookmark")
        bookmark[i].children[0].classList.add("bx-bookmark")
    });
}
