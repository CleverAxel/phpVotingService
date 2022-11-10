const FORM = document.getElementById("hiddenForm");
const QR_CODE_BUTTON = document.getElementById("qrCodeButton");
const HTTP = "http://";
let buildURL = HTTP;

if(SERVER_URL == "192.168.0.45"){
    buildURL += "192.168.0.45/php/votingSystem/voteActivity.php?uuid=" + QUERY_UUID;
}else if(SERVER_URL == "www.palabre.be"){
    buildURL = "http://palabre.be/vote/voteActivity.php?uuid=" + QUERY_UUID;
}
if(QR_CODE_BUTTON){
    QR_CODE_BUTTON.addEventListener("click", () => {
        let qrcode = new QRCode(buildURL);
        let s = new XMLSerializer();
        let string = s.serializeToString(qrcode);
        //let b64 = 'data:image/svg+xml;base64,'+window.btoa(string);
        //let b64 = ;
        FORM.elements["base64"].value = window.btoa(string);
        FORM.submit();
    });
}
