'use strict';
const handleNavbar = () => {
    let navbarLinks = document.querySelectorAll('.navbar-menu_item');
    navbarLinks.forEach( link => {
        link.addEventListener('click', e => {
            navbarLinks.forEach( i => i.classList.remove('active'));
            e.target.classList.add('active');
            e.preventDefault();
            let anchor = e.target.href.slice(e.target.href.lastIndexOf('#') + 1);
            document.getElementById(anchor).scrollIntoView({behavior: "smooth"});
        })
    })
}

document.addEventListener('DOMContentLoaded', () => {
    handleNavbar();
});
