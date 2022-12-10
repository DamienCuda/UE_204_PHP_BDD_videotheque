/*
    Ce script permet de gérer les li active pour le menu étant donner qu'on à un seul header pour toutes les pages
    il est assez difficile de mettre un active car on ne sais pas sur quel page est l'utilisateur.

    Grâce à ce code Javascript nous donne la page en cours et si cette page fais parti de notre menu on lui applique un classe active
    et la retire pour toutes les autres.
 */

window.addEventListener('load', (event) => {
    var page_courrante = window.location.pathname;
    page_courrante = page_courrante.split("/");
    page_courrante = page_courrante[page_courrante.length - 1];

    let menu = document.getElementById("nav_menu");
    let menuList = menu.children;

    for (let i = 0; i < menuList.length; i++) {
        if (menuList[i].children[0].getAttribute("href") == page_courrante) {
            menuList[i].children[0].classList.add("active");
        } else {
            menuList[i].children[0].classList.remove("active");

            menuList[i].addEventListener("mouseover", function(){
                menuList[i].children[0].classList.add("active");
            });

            menuList[i].addEventListener("mouseleave", function(){
                menuList[i].children[0].classList.remove("active");
            });
        }
    }
});