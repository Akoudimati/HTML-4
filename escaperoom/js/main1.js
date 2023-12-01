// Haal verwijzingen op naar het modal, de knop en het sluitingspictogram
let modal = document.getElementById("myModal");

let btn = document.getElementById("myBtn");

let span = document.getElementsByClassName("close")[0];

// Wanneer op de knop wordt geklikt, toon het modal

btn.onclick = function () {
  modal.style.display = "block";
};

// Wanneer op het sluitingspictogram wordt geklikt, verberg het modal
span.onclick = function () {
  modal.style.display = "none";
};

// Wanneer er buiten het modal wordt geklikt, verberg het modal
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};
// Definieer twee moeilijkheidsniveaus voor het spel
const GameDifficulty = [50, 70];
// Definieer een klasse voor het spel
class Game {
  // Klasse-eigenschappen
  difficulty;
  cols = 3;
  rows = 3;
  count;
  blocks;
  emptyBlockCoords = [2, 2];
  indexes = [];

  // Constructor voor de Game-klasse
  constructor(difficultyLevel = 1) {
    this.difficulty = GameDifficulty[difficultyLevel - 1];
    this.count = this.cols * this.rows;
    this.blocks = document.getElementsByClassName("puzzle_block");
    this.init();
  }
  // Initialiseer het spel
  init() {
    for (let y = 0; y < this.rows; y++) {
      for (let x = 0; x < this.cols; x++) {
        let blockIdx = x + y * this.cols;
        if (blockIdx + 1 >= this.count) break;
        let block = this.blocks[blockIdx];
        this.positionBlockAtCoord(blockIdx, x, y);
        block.addEventListener("click", (e) => this.onClickOnBlock(blockIdx));
        this.indexes.push(blockIdx);
      }
    }
    this.indexes.push(this.count - 1);
    this.randomize(this.difficulty);
  }
  // Randomiseer de puzzelblokken
  randomize(iterationCount) {
    for (let i = 0; i < iterationCount; i++) {
      let randomBlockIdx = Math.floor(Math.random() * (this.count - 1));
      let moved = this.moveBlock(randomBlockIdx);
      if (!moved) i--;
    }
  }
  // Verplaats een puzzelblok
  moveBlock(blockIdx) {
    let block = this.blocks[blockIdx];
    let blockCoords = this.canMoveBlock(block);
    if (blockCoords != null) {
      this.positionBlockAtCoord(
        blockIdx,
        this.emptyBlockCoords[0],
        this.emptyBlockCoords[1]
      );
      this.indexes[
        this.emptyBlockCoords[0] + this.emptyBlockCoords[1] * this.cols
      ] = this.indexes[blockCoords[0] + blockCoords[1] * this.cols];
      this.emptyBlockCoords[0] = blockCoords[0];
      this.emptyBlockCoords[1] = blockCoords[1];
      return true;
    }
    return false;
  }
  // Controleer of een puzzelblok verplaatst kan worden
  canMoveBlock(block) {
    let blockPos = [parseInt(block.style.left), parseInt(block.style.top)];
    let blockWidth = block.clientWidth;
    let blockCoords = [blockPos[0] / blockWidth, blockPos[1] / blockWidth];
    let diff = [
      Math.abs(blockCoords[0] - this.emptyBlockCoords[0]),
      Math.abs(blockCoords[1] - this.emptyBlockCoords[1]),
    ];
    let canMove =
      (diff[0] == 1 && diff[1] == 0) || (diff[0] == 0 && diff[1] == 1);
    if (canMove) return blockCoords;
    else return null;
  }
  // Positioneer een puzzelblok op een specifieke coördinaat
  positionBlockAtCoord(blockIdx, x, y) {
    let block = this.blocks[blockIdx];
    block.style.left = x * block.clientWidth + "px";
    block.style.top = y * block.clientWidth + "px";
  }
  // Behandel klikgebeurtenissen op puzzelblokken
  onClickOnBlock(blockIdx) {
    if (this.moveBlock(blockIdx)) {
      if (this.checkPuzzleSolved()) {
        setTimeout(() => {
          alert(
            "Gefeliciteerd! je hebt de puzzel opgelost. Ga naar de volgende kamer om verder te gaan met de escape room."
          );
          window.location.href = "kamer2.html"; // Navigeer naar kamer2.html na de alert
        }, 600);
      }
    }
  }
  // Controleer of de puzzel is opgelost
  checkPuzzleSolved() {
    for (let i = 0; i < this.indexes.length; i++) {
      if (i == this.emptyBlockCoords[0] + this.emptyBlockCoords[1] * this.cols)
        continue;
      if (this.indexes[i] != i) return false;
    }
    return true;
  }
}

// Maak een instantie van de Game-klasse met een moeilijkheidsniveau van 1

let game = new Game(1);
