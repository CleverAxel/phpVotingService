const DELETE = document.querySelectorAll(".delete");
let isPopUpOn = false;
let deleteHref = null;

document.addEventListener("keydown", (e) =>{
    if(e.key == "Enter" && isPopUpOn){
        location.href = deleteHref;
    }else if(e.key == "Escape"){
        isPopUpOn = false;
        document.body.removeChild(document.querySelector(".alertDelete"));
    }
});

for(let i = 0; i < DELETE.length; i++){
    DELETE[i].addEventListener("click", (e) =>{
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
        div.querySelector("button:nth-child(1)").addEventListener("click", () =>{
            location.href = deleteHref;
        });

        div.querySelector("button:nth-child(2)").addEventListener("click", () =>{
            isPopUpOn = false;
            document.body.removeChild(document.querySelector(".alertDelete"));
        });
    });
}