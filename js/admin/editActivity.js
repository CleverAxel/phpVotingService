const FORM = document.querySelector(".formEdit");
const CONTAINER_FILE_NAME = document.querySelector(".containFileName");
const FILE_NAME = document.querySelector(".containFileName span");
const DELETE_FILE_BUTTON = document.querySelector(".containFileName > div:nth-child(2)");
const DELETE_IMG = document.getElementById("needToDeleteImg");

if(FILE_NAME.innerHTML != ""){
    CONTAINER_FILE_NAME.style.display = "flex";
}

FORM.elements['mainImg'].addEventListener("change", () =>{
    CONTAINER_FILE_NAME.style.display = "flex";
    FILE_NAME.innerHTML = FORM.elements['mainImg'].files[0].name;
    DELETE_IMG.value = "1";
});

DELETE_FILE_BUTTON.addEventListener("click", () => {
    CONTAINER_FILE_NAME.style.display = "none";
    FORM.elements['mainImg'].value = "";
    DELETE_IMG.value = "1";
})