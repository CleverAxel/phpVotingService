const HAMBURGER_BUTTON = document.querySelector(".hamburgerMainNav");
const PATH = HAMBURGER_BUTTON.dataset.path;
HAMBURGER_BUTTON.addEventListener("click", () =>{
    let menuHamburgerButton = document.createElement("div");

    menuHamburgerButton.innerHTML = `
    <div class="closeMenu"><i class="fa-solid fa-xmark"></i></div>
    <nav class="hamburgerNav">
        <ul>
            <li><a href="${PATH}index.php">Accueil</a></li>
            <li><a href="${PATH}allActivities.php">Tous les projets</a></li>
        </ul>
    </nav>
    `;

    const CLOSE_MENU = menuHamburgerButton.querySelector(".closeMenu");
    CLOSE_MENU.addEventListener("click", () =>{
        document.body.style.overflow = "";
        document.body.removeChild(document.querySelector(".menuHamburgerButton"));
    });

    menuHamburgerButton.classList.add("menuHamburgerButton", "translateY");
    document.body.appendChild(menuHamburgerButton);
    

    setTimeout(() => {
        document.body.style.overflow = "hidden";
        menuHamburgerButton.classList.remove("translateY");
    },10);
})