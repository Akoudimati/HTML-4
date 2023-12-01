console.log("main loaded");
//komt de code //


const diceArray = ["&#9856;", "&#9857;", "&#9858;", "&#9859;", "&#9860;", "&#9861;"];

let playerCredits = 3;
let computerCredits = 3;
let gameOver = false;

const goButton = document.querySelector(".go-button");
const messageParagraph = document.querySelector(".message-box p");
const computerDiceOneElement = document.querySelector(".computer-dice-one");

const computerDiceTwoElement = document.querySelector(".computer-dice-two");
const playerDiceOneElement = document.querySelector(".player-dice-one");
const playerDiceTwoElement = document.querySelector(".player-dice-two");
const computerCreditsElement = document.querySelector(".computer-credits");
const playerCreditsElement = document.querySelector(".player-credits");
const diceButton = document.querySelector(".dice-button");
const higherButton = document.querySelector(".higher-button");
const lowerButton = document.querySelector(".lower-button");

// Function to get a random integer between 0 and max (inclusive)
function getRandomInt(max) {
    return Math.floor(Math.random() * (max + 1));
}




// Function to update the display of computer and player credits
function updateCreditsDisplay() {
computerCreditsElement.textContent = computerCredits;
playerCreditsElement.textContent = playerCredits;

if ( computerCredits == 0 || playerCredits == 0) {
let winner = computerCredits == 0 ? "Player wins!" : "Computer wins!";
alert(winner + " Game Over");
initializeGame();  // Optionally reset the game
}
}

// Initialize the game state
function initializeGame() {
    playerCredits = 3;
    computerCredits = 3;
    updateCreditsDisplay();
    gameOver = false;
    messageParagraph.textContent = "Druk op Go om het spel te starten.";
    goButton.disabled = false;
    diceButton.disabled = true;
    higherButton.disabled = true;
    lowerButton.disabled = true;
}

goButton.addEventListener("click", function () {
    if (gameOver) {
        initializeGame();
    }
    messageParagraph.textContent = "Het spel is gestart. De computer mag beginnen.";
    goButton.disabled = true;
    diceButton.disabled = false;
    

    // speler turn 
    computerDiceOne = getRandomInt(5);
    computerDiceTwo = getRandomInt(5);
    computerDiceOneElement.innerHTML = diceArray[computerDiceOne];
    computerDiceTwoElement.innerHTML = diceArray[computerDiceTwo];
    messageParagraph.textContent = " de speler mag beginen..";
    higherButton.disabled = false;
    lowerButton.disabled = false;
});

diceButton.addEventListener("click", function () {
    if (computerTurn) {
        // Computer's logic 
        computerDiceOne = getRandomInt(5);
        computerDiceTwo = getRandomInt(5);
        computerDiceOneElement.innerHTML = diceArray[computerDiceOne];
        computerDiceTwoElement.innerHTML = diceArray[computerDiceTwo];

        // Determine the winner of this round
        const computerTotal = computerDiceOne + computerDiceTwo;
        const playerTotal = playerDiceOne + playerDiceTwo;
        if (computerTotal > playerTotal) {
            messageParagraph.textContent = "De computer heeft gewonnen!";
            computerCredits += 1;
            playerCredits -= 1;
        } else if (computerTotal < playerTotal) {
            messageParagraph.textContent = "Jij hebt gewonnen!";
            computerCredits -= 1;
            playerCredits += 1;
        } else {
            messageParagraph.textContent = "Het is een gelijkspel!";
        }

        updateCreditsDisplay();
        diceButton.disabled = true;
        computerTurn = false;
    }
});

higherButton.addEventListener("click", function () {
    messageParagraph.textContent = "Je hebt 'Hoger' gekozen.";
    playerDiceOne = getRandomInt(5);
    playerDiceTwo = getRandomInt(5);
    playerDiceOneElement.innerHTML = diceArray[playerDiceOne];
    playerDiceTwoElement.innerHTML = diceArray[playerDiceTwo];
    computerTurn = true;
    diceButton.disabled = false;
});

lowerButton.addEventListener("click", function () {
    messageParagraph.textContent = "Je hebt 'Lager' gekozen.";
    playerDiceOne = getRandomInt(5);
    playerDiceTwo = getRandomInt(5);
    playerDiceOneElement.innerHTML = diceArray[playerDiceOne];
    playerDiceTwoElement.innerHTML = diceArray[playerDiceTwo];
    computerTurn = true;
    diceButton.disabled = false;
});

// Initial game setup


initializeGame();

