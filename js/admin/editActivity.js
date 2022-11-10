const FORM = document.querySelector(".formEdit");
const CONTAINER_FILE_NAME = document.querySelector(".containFileName");
const FILE_NAME = document.querySelector(".containFileName span");
const DELETE_FILE_BUTTON = document.querySelector(".containFileName > div:nth-child(2)");
const DELETE_IMG = document.getElementById("needToDeleteImg");

const SUBMIT_BUTTON = document.getElementById("submitFom");

const HTTP = "http://";
let buildURL = HTTP;

if(SERVER_URL == "192.168.0.45"){
    buildURL += "192.168.0.45/php/votingSystem/voteActivity.php?uuid=" + QUERY_UUID;
}else if(SERVER_URL == "www.palabre.be"){
    buildURL = "http://palabre.be/vote/voteActivity.php?uuid=" + QUERY_UUID;
}


SUBMIT_BUTTON.addEventListener("click", (e) => {
    e.preventDefault();

    //si addQrCode existe et que la réponse est 1(oui) alors on génére un qrCode
    if(FORM.elements["addQrCode"]){
        let generateQrCode = parseInt(FORM.elements["addQrCode"].value);
        if(!isNaN(generateQrCode)){
            if(generateQrCode == 1){
                let qrcode = new QRCode(buildURL);
                let s = new XMLSerializer();
                let string = s.serializeToString(qrcode);
                //let b64 = window.btoa(string);
                FORM.elements["base64"].value = window.btoa(string);
            }
        }
    }

    FORM.submit();
});

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