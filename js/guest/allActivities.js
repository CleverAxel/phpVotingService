const READ_MORE = document.querySelectorAll(".readFullResume");
let CONTROLLER = new AbortController();
let urlFetch = "";
if(SERVER_URL == "192.168.0.45"){
    urlFetch = "http://192.168.0.45/php/votingSystem/API_resume.php?uuid=";
}else if(SERVER_URL == "palabre.be"){
    urlFetch = "http://palabre.be/vote/API_resume.php?uuid="
}

if(READ_MORE){
    for(let i = 0; i < READ_MORE.length; i++){
        READ_MORE[i].addEventListener("click", () =>{


            let mainDiv = document.createElement("div");
            mainDiv.classList.add("containerFullResume");
            mainDiv.innerHTML = `
            <div>
                <div class="closeMenu"><i class="fa-solid fa-xmark"></i></div>
                <div class="resume">
                    <div class="spinner"></div>
                </div>
            </div>
            `
            document.body.appendChild(mainDiv);
            document.body.style.overflow = "hidden";
            mainDiv.querySelector(".closeMenu").addEventListener("click",() =>{
                CONTROLLER.abort();
                CONTROLLER = new AbortController();
                document.body.removeChild(mainDiv);
                document.body.style.overflow = "";
            });

            fetchResume(READ_MORE[i].dataset.uuid)
                .then((response) => {
                    mainDiv.querySelector(".spinner").style.display = "none";
                    if(response.error == false){
                        let paragraphes = response.resume.split("\n");
                        for(let i = 0; i < paragraphes.length; i++){
                            let p = document.createElement("p");
                            p.innerText = paragraphes[i];
                            mainDiv.querySelector(".resume").appendChild(p);
                        }
                    }
                    
                })
                .catch((err) => {mainDiv.querySelector(".resume").innerText = "Une erreur est survenue D:<"});
        });
    }

}
 async function fetchResume(uuid){
    let url = urlFetch + uuid;
    return new Promise((resolve, reject) => {
        fetch(url, { signal : CONTROLLER.signal })
            .then((response) =>{
                if(response.ok){
                    return response.json();
                }
            })
            .then((json) =>{
                resolve(json);
            })
            .catch((err =>{
                reject(err);
            }))
    });
}

