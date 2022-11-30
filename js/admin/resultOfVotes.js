const CONTAINER_LEGEND = document.querySelector(".containerLegend");
const CHART = document.querySelector(".chart");
const COLORS = ["#b0b0ff", "#ff9292", "#bfff6c", "#2c8544", "#3f5b83", "#51285d", "#c22777", "#676767", "#9e8c00", "#73a87f"];
createLineInCharts();

VOTES.sort((a, b) => {
    return parseInt(b.countVoteByUser) - parseInt(a.countVoteByUser);
});

const TOTAL_VOTE = counteVote();
const ARRAY_PERCENT = new Array();
calculPercentage();
createBatonnet();
createLegend();

const BATONNETS = CHART.querySelectorAll(".batonnet");
setTimeout(() => {
    for(let i = 0; i < BATONNETS.length; i++){
        BATONNETS[i].style.height = ARRAY_PERCENT[i].toString() + "%";
    }
}, 1);

function createLegend(){
    ARRAY_PERCENT.forEach((percentage, index) => {
        if(index < 3){
            let legend = document.createElement("div");
            legend.classList.add("legend");
        
            let colorLegend = document.createElement("div");
            colorLegend.style.backgroundColor = index < COLORS.length ? COLORS[index] : "black";
            legend.appendChild(colorLegend);

            let containerTitle = document.createElement("div");
            containerTitle.innerHTML = `<h3>${VOTES[index].title} : ${Math.floor(percentage)}% des voix avec ${VOTES[index].countVoteByUser} vote(s) !</h3>`;
            legend.appendChild(containerTitle);

            CONTAINER_LEGEND.appendChild(legend);
        }
    });
    // for(let i = 0; i < ARRAY_PERCENT.length; i++){
    //     let legend = document.createElement("div");
    //     legend.classList.add("legend");
    
    //     let colorLegend = document.createElement("div");
    //     colorLegend.style.backgroundColor = i < COLORS.length ? COLORS[i] : "black";
    //     legend.appendChild(colorLegend);

    //     let containerTitle = document.createElement("div");
    //     containerTitle.innerHTML = `<h3>${VOTES[i].title} : ${ARRAY_PERCENT[i]}%</h3>`;
    //     legend.appendChild(containerTitle);

    //     CONTAINER_LEGEND.appendChild(legend);
    // }
}

function createLineInCharts(){
    for(let i = 0; i < 11; i++){
        let percentageBottom = i * 10;
        let div = document.createElement("div");
        div.classList.add("line");
        div.style.bottom = percentageBottom.toString() + "%";
        let span = document.createElement("span");
        span.innerText = percentageBottom.toString() + "%";
        div.appendChild(span);
        CHART.appendChild(div);
    }
}

function createBatonnet(){
    ARRAY_PERCENT.forEach((percentage, index) => {
        if(index < 3){
            let div = document.createElement("div");
            div.classList.add("batonnet");
            div.style.height = "0%";
            div.style.backgroundColor = index < COLORS.length ? COLORS[index] : "black";
            CHART.appendChild(div);
        }
    });
}

function counteVote(){
    let total = 0;
    for(let i = 0; i < VOTES.length; i++){
        total += parseInt(VOTES[i].countVoteByUser);
    }
    return total;
}

function calculPercentage(){
    for(let i = 0; i < VOTES.length; i++){
        let percent = (parseInt(VOTES[i].countVoteByUser) / TOTAL_VOTE) * 100;
        ARRAY_PERCENT[i] = percent;
    }
}
