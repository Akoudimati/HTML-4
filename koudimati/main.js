const cursor = document.querySelector(".cursor");
const holes = [...document.querySelectorAll(".hole")];
const scoreEl = document.querySelector(".score span");
const board = document.querySelector(".board");

let score = 0;

const sound = new Audio("assets/smash.mp3");
const sound1 = new Audio("assets/error-windows-xp.mp3");

function createMole() {
  const moleImg = document.createElement("img");
  moleImg.classList.add("mole");
  moleImg.src = "assets/mole.png";

  moleImg.addEventListener("click", () => {
    score += 10;
    sound.play();
    scoreEl.textContent = score;
    moleImg.src = "assets/mole-whacked.png";
    clearTimeout(timer);
    setTimeout(() => {
      hole.removeChild(moleImg);
      run();
    }, gametime(score));
  });

  return moleImg;
}

function createBomb() {
  const bombImg = document.createElement("img");
  bombImg.classList.add("mole");
  bombImg.src = "assets/bom.png";

  bombImg.addEventListener("click", () => {
    score -= 100;
    removeExtraDivs(10);

    sound1.play();
    scoreEl.textContent = score;
    bombImg.src = "assets/bom1.png";
    clearTimeout(timer);
    setTimeout(() => {
      hole.removeChild(bombImg);
      run();
    }, gametime(score));
  });

  return bombImg;
}

function run() {
  const i = Math.floor(Math.random() * holes.length);
  const hole = holes[i];

  let timer = null;

  let imgToDisplay;

  if (Math.random() < 0.5) {
    imgToDisplay = createMole();
  } else {
    imgToDisplay = createBomb();
  }

  hole.appendChild(imgToDisplay);

  timer = setTimeout(() => {
    hole.removeChild(imgToDisplay);
    run();
  }, gametime(score));
}

run();

let totalDivs = 9;

function gametime(score) {
  if (score >= 500) {
    alert("Jij bent gewonnen " + score);
    window.location.reload();
    return 800;
  } else if (score >= 460) {
    document.body.style.cursor = "default";
    return 800;
  } else if (score >= 300) {
    score300(4);
    document.body.style.backgroundColor = "indigo";
    board.style.gridTemplateColumns = "repeat(6, 1fr)";
    board.style.gridTemplateRows = "repeat(6, 1fr)";

    return 800;
  } else if (score >= 200) {
    document.body.style.backgroundColor = "brown";
    board.style.gridTemplateColumns = "repeat(5, 1fr)";
    board.style.gridTemplateRows = "repeat(5, 1fr)";
    score200(4);

    return 800;
  } else if (score >= 100) {
    score100(4);
    document.body.style.backgroundColor = "indigo";
    board.style.gridTemplateColumns = "repeat(4, 1fr)";
    board.style.gridTemplateRows = "repeat(4, 1fr)";
    return 800;
  } else {
    document.body.style.backgroundColor = "rgb(71, 47, 63)";
    board.style.gridTemplateColumns = "repeat(3, 1fr)";
    board.style.gridTemplateRows = "repeat(3, 1fr)";
    return 800;
  }
}

function score100(count) {
  for (let i = 0; i < count; i++) {
    if (totalDivs < 12) {
      const newDiv = document.createElement("div");
      newDiv.classList.add("hole");
      board.appendChild(newDiv);
      totalDivs++;
    }
  }
}
function score200(count) {
  for (let i = 0; i < count; i++) {
    if (totalDivs < 15) {
      const newDiv = document.createElement("div");
      newDiv.classList.add("hole");
      board.appendChild(newDiv);
      totalDivs++;
    }
  }
}
function score300(count) {
  for (let i = 0; i < count; i++) {
    if (totalDivs < 18) {
      const newDiv = document.createElement("div");
      newDiv.classList.add("hole");
      board.appendChild(newDiv);
      totalDivs++;
    }
  }
}
function removeExtraDivs(count) {
  const holes = document.querySelectorAll(".hole");
  for (let i = 0; i < count && totalDivs > 12; i++) {
    const lastHole = holes[totalDivs - 1];
    lastHole.parentNode.removeChild(lastHole);
    totalDivs--;
  }
}

window.addEventListener("mousemove", (e) => {
  cursor.style.top = e.pageY + "px";
  cursor.style.left = e.pageX + "px";
});
window.addEventListener("mousedown", () => {
  cursor.classList.add("active");
});
window.addEventListener("mouseup", () => {
  cursor.classList.remove("active");
});

let modal = document.getElementById("myModal");

let btn = document.getElementById("myBtn");

let span = document.getElementsByClassName("close")[0];

btn.onclick = function () {
  modal.style.display = "block";
  document.body.style.cursor = "default";
};

span.onclick = function () {
  modal.style.display = "none";

  document.body.style.cursor = "none";
};

window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};