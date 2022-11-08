const HAMBURGER_BUTTON = document.querySelector(".hamburgerMainNav");

HAMBURGER_BUTTON.addEventListener("click", () =>{
    let menuHamburgerButton = document.createElement("div");
    menuHamburgerButton.classList.add("menuHamburgerButton", "translateY");
    document.body.appendChild(menuHamburgerButton);
    document.body.style.overflow = "hidden";
    setTimeout(() => {
     menuHamburgerButton.classList.remove("translateY");
    },1);
})