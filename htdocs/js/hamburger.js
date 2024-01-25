"use strict";

const nav_button = document.getElementById("openbutton") ;
const g_nav = document.getElementById("g_nav");
const g_nav_list = document.getElementById("g_nav_list");
const search_form_container = document.getElementById("search_form_container");


nav_button.addEventListener("click", () => {

  if (nav_button.classList.contains('open')) {

    nav_button.classList.remove('open');
    g_nav.classList.remove("panelactive");
    g_nav_list.classList.remove("panelactive");

  } else {

    nav_button.classList.add('open');
    g_nav.classList.add("panelactive");
    g_nav_list.classList.add("panelactive");

  }

});
