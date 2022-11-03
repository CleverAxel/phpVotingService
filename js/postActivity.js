const FORM = document.forms[0];
const HTTP = "http://";
let buildURL = HTTP;

if(SERVER_URL == "192.168.0.45"){
    buildURL += "192.168.0.45/php/votingSystem/voteActivity.php?uuid=" + QUERY_UUID;
}

document.getElementById("qrCodeButton").addEventListener("click", () => {
    let qrcode = new QRCode(buildURL);
    let s = new XMLSerializer();
    let string = s.serializeToString(qrcode);
    //let b64 = 'data:image/svg+xml;base64,'+window.btoa(string);
    let b64 = window.btoa(string);
    FORM.elements["base64"].value = b64;
    FORM.submit();
});
