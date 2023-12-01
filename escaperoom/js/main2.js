console.log ("vraag 1  Ierland");
console.log ("vraag 2 7000");
console.log ("vraag 3  heilgenavond");
console.log ("vraag 4 kelten");
console.log ("vraag 5  oranje");





let modal = document.getElementById("myModal");

let btn = document.getElementById("myBtn");

let span = document.getElementsByClassName("close")[0];

// Toon het modal wanneer de knop wordt geklikt
btn.onclick = function () {
    modal.style.display = "block";
}
 
// Verberg het modal wanneer op het sluitingspictogram wordt geklikt
span.onclick = function () {
    modal.style.display = "none";
}
// Verberg het modal wanneer de gebruiker ergens buiten het modal klikt

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
// Definieer een array van knopelementen voor vragen
const buttons = [
    document.querySelector('.vraag-button-kamer2'),
    document.querySelector('.vraag-button2-kamer2'),
    document.querySelector('.vraag-button3-kamer2'),
    document.querySelector('.vraag-button4-kamer2'),
    document.querySelector('.vraag-button5-kamer2'),
    document.querySelector('.vraag-button6-kamer2'),
    document.querySelector('.vraag-button7-kamer2')
];


// Schakel de 'Klaar'-knop aanvankelijk uit
const klaarKnop = document.querySelector('.klaar');
klaarKnop.disabled = true; // de knop is aanvankelijk uitgeschakeld



// Teller voor het aantal correct beantwoorde vragen
let correctAnswersCount = 0; 



// Functie om een vraag te stellen en de kleur van de knop te wijzigen op basis van het antwoord
function askQuestionAndChangeColor(buttonElement, question, correctAnswer) {
    buttonElement.addEventListener('click', function () {
        let answer = prompt(question);
        if (answer === correctAnswer) {
            buttonElement.style.backgroundColor = 'green';
            correctAnswersCount++; // verhoog de teller
            checkIfFourCorrectAnswers(); // check na elke correcte beantwoording
        } else {
            buttonElement.style.backgroundColor = 'red';
        }
    });
}

// Voeg een klikgebeurtenis toe aan de 'Klaar'-knop om door te gaan naar kamer3.html
function checkIfFourCorrectAnswers() {
    if (correctAnswersCount >= 3) {
        klaarKnop.disabled = false;
        // activeer de 'klaar'-knop
    }
}

// Stel vragen en wijzig de kleur van de knoppen op basis van de antwoorden
klaarKnop.addEventListener('click', function () {
    window.location.href = "kamer3.html"; // ga naar kamer3.html
});

askQuestionAndChangeColor(buttons[0], "Waar is halloween onstaan?", "Ierland");
askQuestionAndChangeColor(buttons[1], "hoeveel kalorieen eet een amerikaan tijdens halloween gemiddeld", "7000");
askQuestionAndChangeColor(buttons[2], "wat betekend halloween", "heilgenavond");
askQuestionAndChangeColor(buttons[3], "welke groep heeft halloween bedacht?", "kelten");
askQuestionAndChangeColor(buttons[4], "welke kleur staat centraal voor halloween", "oranje");
askQuestionAndChangeColor(buttons[5], "in welk land word halloween het grootst gevierd?", "Amerika");
// askQuestionAndChangeColor(buttons[6], "4+4", "8");


