const cursor = document.querySelector(".cursor");
const holes = [...document.querySelectorAll(".hole")];
const scoreEl = document.querySelector(".score span");
let score = 0;

const sound = new Audio("assets/smash.mp3");

function run() {
  const i = Math.floor(Math.random() * holes.length);
  const hole = holes[i];

  let timer = null;

  const img = document.createElement("img");
  img.classList.add("mole");
  img.src = "assets/mole.png";

  img.addEventListener("click", () => {
    score += 10;
    sound.play();
    scoreEl.textContent = score;
    img.src = "assets/mole-whacked.png.png";
    clearTimeout(timer);
    setTimeout(() => {
      hole.removeChild(img);
      run();
    }, determineTimeoutDuration(score));

    
  });

  hole.appendChild(img);

  timer = setTimeout(() => {
    hole.removeChild(img);
    run();
  }, determineTimeoutDuration(score));
}

run();

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

function determineTimeoutDuration(score) {
  if (score >= 300) {
    document.body.style.backgroundColor = 'red';
    return 450;
  } else if (score >= 200) {
    document.body.style.backgroundColor = 'brown';
    return 650;
  } else if (score >= 100) {
    document.body.style.backgroundColor = 'indigo';
    return 800;
  } else {
    return 1000;
  }
}

function resetGame() {
  score = 0;
  scoreEl.textContent = score;
  run();
}

let modal = document.getElementById("myModal");

let btn = document.getElementById("myBtn");

let span = document.getElementsByClassName("close")[0];

btn.onclick = function () {
  modal.style.display = "block";
};

span.onclick = function () {
  modal.style.display = "none";
};

window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};




