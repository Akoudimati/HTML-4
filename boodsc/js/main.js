  // Eerst moet ik  de referenties naar de knoppen en andere elementen verkrijgen
  const cheeseButton = document.querySelector(".cheese-button");
  const sausageButton = document.querySelector(".sausage-button");
  const colaButton = document.querySelector(".cola-button");
  const candyButton = document.querySelector(".candy-button");
  const chipsButton = document.querySelector(".chips-button");
  const cocktailNutsButton = document.querySelector(".cocktail-nuts-button");
  const depositButton = document.querySelector(".deposit-button");
  const orderedProducts = document.querySelector(".ordered-products");
  const totalPrice = document.querySelector(".total-price");

  let cart = []; // Een array om de items in de winkelwagen bij te houden
  let total = 0; // Variabele om het totaalbedrag bij te houden

  // Voeg een klikgebeurtenis toe aan elk productknop
  cheeseButton.addEventListener("click", () => addToCart("Kaas", 1.0));
  sausageButton.addEventListener("click", () => addToCart("Worst", 1.5));
  colaButton.addEventListener("click", () => addToCart("Cola", 1.3));
  candyButton.addEventListener("click", () => addToCart("Haribo Snoepjes", 2.0));
  chipsButton.addEventListener("click", () => addToCart("Chips", 1.4));
  cocktailNutsButton.addEventListener("click", () => addToCart("Borrelnootjes", 1.0));
  depositButton.addEventListener("click", () => addToCart("Statiegeld", -0.3));

  // Voeg een klikgebeurtenis toe aan de "Winkelwagen leegmaken" knop
  const clearCartButton = document.querySelector(".clear-cart-button");
  clearCartButton.addEventListener("click", clearCart);

  // Functie om een product aan de winkelwagen toe te voegen
  function addToCart(productName, price) {
      cart.push({ name: productName, price: price });
      updateCartUI();
  }
  
// Voeg een klikgebeurtenis toe aan de "Betalen" knop
const payButton = document.querySelector(".pay-button");
payButton.addEventListener("click", () => {
  alert("Nice!");
});

  // Functie om de winkelwagen bij te werken en de totaalprijs te berekenen
  function updateCartUI() {
      orderedProducts.innerHTML = ""; // Leeg de inhoud van de winkelwagen

      cart.forEach((item) => {
          const productElement = document.createElement("div");
          productElement.textContent = `${item.name} - €${item.price.toFixed(2)}`;
          orderedProducts.appendChild(productElement);
      });

      total = cart.reduce((acc, item) => acc + item.price, 0);

      // Update de totaalprijs
      totalPrice.textContent = `€${total.toFixed(2)}`;

      // Controleer op waarschuwingen
      if (total < 0) {
          alert("Waarschuwing: Het totaalbedrag is lager dan €0,00!");
      } else if (total > 50) {
          alert("Waarschuwing: Het totaalbedrag is hoger dan €50,00!");
      }
  }

  // Functie om de winkelwagen leeg te maken
  function clearCart() {
      cart = [];
      updateCartUI();
      
  }