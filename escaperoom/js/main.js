




const startButton = document.getElementById('start-button');

startButton.addEventListener('click', () => {
    window.location.href = 'kamer1.html';
});

let container = document.querySelector(".text");

let speeds = {
   pause: 30, //Higher number = longer dela
   slow: 35 ,
   normal: 30,
   fast: 30,
   superFast: 10
};

let textLines = [
   { speed: speeds.slow, string: "Welkom in mijn huis," },
   { speed: speeds.fast, string: " ik ben Daimon, de eigenaar van dit huis. Er zijn al tijden geruchten dat er in dit huis een kwade geest rondvliegt die niet uit is op goede dingen, elk persoon die dit huis verder heeft betreden is niet meer teruggezien, maar u bent dapper genoeg om dit avontuur alleen aan te gaan, er liggen 3 kamers voor u", pause: true },
   { speed: speeds.fast, string: "met in elke kamer een bepaalde uitdaging, dit kan een puzzel zijn maar ook vragen over het thema van deze escape room, halloween. Kunt u deze escape room voltooien voor de kwade geest u te grazen neemt?" },
   { speed: speeds.fast, string: "", classes: ["green"] },
   { speed: speeds.normal, string: "durft u verder te betreden?" }
];


let characters = [];
textLines.forEach((line, index) => {
   if (index < textLines.length - 1) {
      line.string += " "; //Add a space between lines
   }

   line.string.split("").forEach((character) => {
      let span = document.createElement("span");
      span.textContent = character;
      container.appendChild(span);
      characters.push({
         span: span,
         isSpace: character === " " && !line.pause,
         delayAfter: line.speed,
         classes: line.classes || []
      });
   });
});

function revealOneCharacter(list) {
   let next = list.splice(0, 1)[0];
   next.span.classList.add("revealed");
   next.classes.forEach((c) => {
      next.span.classList.add(c);
   });
   let delay = next.isSpace && !next.pause ? 0 : next.delayAfter;

   if (list.length > 0) {
      setTimeout(function () {
         revealOneCharacter(list);
      }, delay);
   }
}

//Kick it off
setTimeout(() => {
   revealOneCharacter(characters);   
}, 600)



// kamer1



