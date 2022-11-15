const DELETE = document.querySelectorAll(".delete");
const PDF_BUTTONS = document.querySelectorAll(".pdfButtons");
let isPopUpOn = false;
let deleteHref = null;

document.addEventListener("keydown", (e) => {
    if (e.key == "Enter" && isPopUpOn) {
        location.href = deleteHref;
    } else if (e.key == "Escape") {
        isPopUpOn = false;
        document.body.removeChild(document.querySelector(".alertDelete"));
    }
});

for (let i = 0; i < DELETE.length; i++) {
    DELETE[i].addEventListener("click", (e) => {
        e.preventDefault();
        deleteHref = DELETE[i].href;
        document.activeElement.blur();
        isPopUpOn = true;
        let div = document.createElement("div");
        div.classList.add("alertDelete");
        div.innerHTML =
            `
        <div>
            <h3>Vous allez supprimer définitivement cette activité, continuer ?</h3>
            <div class="containerButton">
                <button>OUI</button>
                <button>NON</button>
            </div>
        </div>
        `;
        document.body.appendChild(div);
        div.querySelector("button:nth-child(1)").addEventListener("click", () => {
            location.href = deleteHref;
        });

        div.querySelector("button:nth-child(2)").addEventListener("click", () => {
            isPopUpOn = false;
            document.body.removeChild(document.querySelector(".alertDelete"));
        });
    });
}

/************************************* */
const { jsPDF } = window.jspdf;
for (let i = 0; i < PDF_BUTTONS.length; i++) {
    PDF_BUTTONS[i].addEventListener("click", () => {
        getBase64FromSVG("../assets/images/"+PDF_BUTTONS[i].dataset.qrcode)
            .then((base64) =>{
                let image = new Image();
                image.src = base64;

                    image.addEventListener("load", () =>{
                    let canvas = document.createElement("canvas");
                    canvas.width = 100;
                    canvas.height = 100;
                    let ctx = canvas.getContext("2d");
                    ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
                    const doc = new jsPDF();
                    doc.setFontSize(27);
                    let text = PDF_BUTTONS[i].dataset.title.toUpperCase();
                    doc.text(text, 105, 21, {maxWidth:210, align:"center"}, null);
                    //const textWidth = doc.getTextWidth(text);
                    doc.addImage(canvas, "PNG", (doc.internal.pageSize.width / 2) - 50, (doc.internal.pageSize.height / 2) - 50, 100, 100);
                    //CONTAINER.src = doc.output("datauristring");
                    doc.save("pdf_file_"+Date.now().toString()+".pdf");
                })
            })
    });
}

function getBase64FromSVG(url) {
    return new Promise((resolve, reject) => {
        fetch(url)
            .then(response => { return response.blob() })
            .then(blob => {
                fileReader(blob)
                    .then(base64 => resolve(base64))
            });
    });
}

function fileReader(blob) {
    return new Promise((resolve, reject) => {
        const READER = new FileReader();
        READER.readAsDataURL(blob);
        READER.addEventListener("load", () => {
            resolve(READER.result);
        });
    });
}