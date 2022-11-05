const FORM = document.querySelector(".formCreate");
const CONTAINER_FILE_NAME = document.querySelector(".containFileName");
const FILE_NAME = document.querySelector(".containFileName span");
const DELETE_FILE_BUTTON = document.querySelector(".containFileName > div:nth-child(2)");

FORM.elements['mainImg'].addEventListener("change", () =>{
    CONTAINER_FILE_NAME.style.display = "flex";
    FILE_NAME.innerHTML = FORM.elements['mainImg'].files[0].name;
});

DELETE_FILE_BUTTON.addEventListener("click", () => {
    CONTAINER_FILE_NAME.style.display = "none";
    FORM.elements['mainImg'].value = "";
})

// FORM.elements["imgUpload"].addeven