console.log('Main Loaded');

//array containing the names of all the first 151 pokemon
const pokemonNameArray = ['bulbasaur', 'ivysaur', 'venusaur', 'charmander', 'charmeleon', 'charizard', 'squirtle', 'wartortle', 'blastoise', 'caterpie', 'metapod', 'butterfree', 'weedle', 'kakuna', 'beedrill', 'pidgey', 'pidgeotto', 'pidgeot', 'rattata', 'raticate', 'spearow', 'fearow', 'ekans', 'arbok', 'pikachu', 'raichu', 'sandshrew', 'sandslash', 'nidoran-f', 'nidorina', 'nidoqueen', 'nidoran-m', 'nidorino', 'nidoking', 'clefairy', 'clefable', 'vulpix', 'ninetales', 'jigglypuff', 'wigglytuff', 'zubat', 'golbat', 'oddish', 'gloom', 'vileplume', 'paras', 'parasect', 'venonat', 'venomoth', 'diglett', 'dugtrio', 'meowth', 'persian', 'psyduck', 'golduck', 'mankey', 'primeape', 'growlithe', 'arcanine', 'poliwag', 'poliwhirl', 'poliwrath', 'abra', 'kadabra', 'alakazam', 'machop', 'machoke', 'machamp', 'bellsprout', 'weepinbell', 'victreebel', 'tentacool', 'tentacruel', 'geodude', 'graveler', 'golem', 'ponyta', 'rapidash', 'slowpoke', 'slowbro', 'magnemite', 'magneton', 'farfetchd', 'doduo', 'dodrio', 'seel', 'dewgong', 'grimer', 'muk', 'shellder', 'cloyster', 'gastly', 'haunter', 'gengar', 'onix', 'drowzee', 'hypno', 'krabby', 'kingler', 'voltorb', 'electrode', 'exeggcute', 'exeggutor', 'cubone', 'marowak', 'hitmonlee', 'hitmonchan', 'lickitung', 'koffing', 'weezing', 'rhyhorn', 'rhydon', 'chansey', 'tangela', 'kangaskhan', 'horsea', 'seadra', 'goldeen', 'seaking', 'staryu', 'starmie', 'mr-mime', 'scyther', 'jynx', 'electabuzz', 'magmar', 'pinsir', 'tauros', 'magikarp', 'gyarados', 'lapras', 'ditto', 'eevee', 'vaporeon', 'jolteon', 'flareon', 'porygon', 'omanyte', 'omastar', 'kabuto', 'kabutops', 'aerodactyl', 'snorlax', 'articuno', 'zapdos', 'moltres', 'dratini', 'dragonair', 'dragonite', 'mewtwo', 'mew'];

//array containing the image links of all the first 151 pokemon
const pokemonImageArray = ['https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/1.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/2.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/3.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/4.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/5.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/6.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/7.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/8.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/9.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/10.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/11.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/12.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/13.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/14.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/15.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/16.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/17.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/18.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/19.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/20.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/21.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/22.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/23.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/24.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/25.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/26.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/27.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/28.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/29.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/30.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/31.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/32.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/33.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/34.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/35.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/36.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/37.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/38.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/39.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/40.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/41.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/42.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/43.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/44.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/45.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/46.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/47.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/48.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/49.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/50.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/51.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/52.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/53.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/54.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/55.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/56.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/57.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/58.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/59.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/60.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/61.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/62.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/63.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/64.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/65.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/66.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/67.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/68.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/69.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/70.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/71.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/72.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/73.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/74.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/75.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/76.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/77.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/78.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/79.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/80.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/81.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/82.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/83.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/84.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/85.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/86.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/87.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/88.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/89.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/90.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/91.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/92.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/93.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/94.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/95.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/96.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/97.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/98.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/99.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/100.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/101.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/102.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/103.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/104.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/105.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/106.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/107.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/108.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/109.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/110.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/111.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/112.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/113.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/114.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/115.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/116.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/117.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/118.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/119.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/120.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/121.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/122.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/123.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/124.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/125.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/126.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/127.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/128.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/129.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/130.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/131.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/132.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/133.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/134.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/135.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/136.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/137.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/138.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/139.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/140.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/141.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/142.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/143.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/144.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/145.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/146.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/147.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/148.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/149.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/150.png', 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/151.png'];

//array containing the types of all the first 151 pokemon
const pokemonTypeArray = ['grass/poison', 'grass/poison', 'grass/poison', 'fire', 'fire', 'fire/flying', 'water', 'water', 'water', 'bug', 'bug', 'bug/flying', 'bug/poison', 'bug/poison', 'bug/poison', 'normal/flying', 'normal/flying', 'normal/flying', 'normal', 'normal', 'normal/flying', 'normal/flying', 'poison', 'poison', 'electric', 'electric', 'ground', 'ground', 'poison', 'poison', 'poison/ground', 'poison', 'poison', 'poison/ground', 'fairy', 'fairy', 'fire', 'fire', 'normal/fairy', 'normal/fairy', 'poison/flying', 'poison/flying', 'grass/poison', 'grass/poison', 'grass/poison', 'bug/grass', 'bug/grass', 'bug/poison', 'bug/poison', 'ground', 'ground', 'normal', 'normal', 'water', 'water', 'fighting', 'fighting', 'fire', 'fire', 'water', 'water', 'water/fighting', 'psychic', 'psychic', 'psychic', 'fighting', 'fighting', 'fighting', 'grass/poison', 'grass/poison', 'grass/poison', 'water/poison', 'water/poison', 'rock/ground', 'rock/ground', 'rock/ground', 'fire', 'fire', 'water/psychic', 'water/psychic', 'electric/steel', 'electric/steel', 'normal/flying', 'normal/flying', 'normal/flying', 'water', 'water/ice', 'poison', 'poison', 'water', 'water/ice', 'ghost/poison', 'ghost/poison', 'ghost/poison', 'rock/ground', 'psychic', 'psychic', 'water', 'water', 'electric', 'electric', 'grass/psychic', 'grass/psychic', 'ground', 'ground', 'fighting', 'fighting', 'normal', 'poison', 'poison', 'ground/rock', 'ground/rock', 'normal', 'grass', 'normal', 'water', 'water', 'water', 'water', 'water', 'water/psychic', 'psychic/fairy', 'bug/flying', 'ice/psychic', 'electric', 'fire', 'bug', 'normal', 'water', 'water/flying', 'water/ice', 'normal', 'normal', 'water', 'electric', 'fire', 'normal', 'rock/water', 'rock/water', 'rock/water', 'rock/water', 'rock/flying', 'normal', 'ice/flying', 'electric/flying', 'fire/flying', 'dragon', 'dragon', 'dragon/flying', 'psychic', 'psychic'];





let searchInput = document.getElementById('search-input');
let typeSelect = document.getElementById('type-select');
let pokemonContainer = document.getElementById('pokemon-container');



// Voeg event listeners toe
searchInput.addEventListener('input', filterPokemon);
typeSelect.addEventListener('change', filterPokemon);


// Haal Pokémon-gegevens op
async function getPokemon() {
    const response = await fetch('https://pokeapi.co/api/v2/pokemon?limit=151');
 const data = await response.json();
    for (const pokemon of data.results) {
        const detailsResponse = await fetch(pokemon.url);
        const detailsData = await detailsResponse.json();
        createPokemonCard(detailsData);
    }
}



// Maak een kaart voor elke Pokémon en voeg deze toe aan de container
function createPokemonCard(pokemon) {
    let div = document.createElement('div');
    div.classList.add('pokemon');

    let h2 = document.createElement('h2');
    h2.textContent = pokemon.name;
    div.appendChild(h2);

    let p = document.createElement('p');
    // Geef de types van de Pokémon weer als een slash-gescheiden string
    p.textContent = pokemon.types.map(type => type.type.name).join('/');
    div.appendChild(p);

    let img = document.createElement('img');
    img.src = pokemon.sprites.front_default;
    img.width = '150';
    div.appendChild(img);
 // Voeg de Pokémon-kaart toe aan de container
    pokemonContainer.appendChild(div);
}





// Filter Pokémon op basis van zoekopdracht en type
function filterPokemon() {
    let filter = searchInput.value.toLowerCase();
    let selectedType = typeSelect.value;

    // Verberg alle Pokémon-kaarten
    for (let i = 0; i < pokemonContainer.children.length; i++) {
        pokemonContainer.children[i].style.display = 'none';
    }

    // Toon de gefilterde Pokémon-kaarten
    let filteredPokemon = [];
    for (let i = 0; i < pokemonContainer.children.length; i++) {
        let pokemon = pokemonContainer.children[i];
        let name = pokemon.querySelector('h2').textContent.toLowerCase();
        let types = pokemon.querySelector('p').textContent.toLowerCase();

        if (selectedType) {
            if (types.includes(selectedType) && name.includes(filter)) {
                filteredPokemon.push(pokemon);
            }
        } else {
            if (name.includes(filter)) {
                filteredPokemon.push(pokemon);
            }
        }
    }

    for (let i = 0; i < filteredPokemon.length; i++) {
        filteredPokemon[i].style.display = 'block';
    }
}







// Haal Pokémon-types op en vul de dropdown
async function getPokemonTypes() {
    const response = await fetch('https://pokeapi.co/api/v2/type');
    const data = await response.json();
    // Loop door de lijst met Pokémon-types en voeg ze toe aan het dropdown-menu
    for (const type of data.results) {
        let option = document.createElement('option');
        option.value = type.name;
        option.textContent = type.name;
        typeSelect.appendChild(option);
    }
}
// Roep de functies aan om de pagina te vullen met Pokémon-gegevens en -types
getPokemon();
getPokemonTypes();
