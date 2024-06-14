<script>
  import { onMount } from 'svelte';
  import { fade } from 'svelte/transition';
  import axios from 'axios';
  import { homeCount } from './stores.js';

  import Nav from './lib/Nav.svelte';
  import Time from './lib/Time.svelte';
  import Text from './lib/Text.svelte';
  import VSequencer from './lib/VSequencer.svelte';
  import LiveCameras from './lib/LiveCameras.svelte';
  import FetchArchive from './lib/Couleur/FetchArchive.svelte';
  import FetchData from './lib/Climat/FetchData.svelte';
  import FetchMaison from './lib/Maison/FetchMaison.svelte';
  import Images from './lib/Maison/Images.svelte';

  const openingTime = 1710630000; 
  const closingTime = 1712507262; 

  let showComponent = true;

  let intervalTime = 6000; 
  let newIndex = 7;
  let whichInterval;
  let texts = [];
  let homeImages = [];
  let componentIndexes = Array.from({ length: 8 }, (_, i) => i);
  let currentIndex = 0; // Current index in the componentIndexes array
  let isAutoChange = true; // interval or manual change

// Function to shuffle the indexes array
function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]]; // Swap elements
  }
}


  // Function to generate a new interval within a specified range
  function getNewInterval() {
    const minMultiplier = 2; // Defines minimum multiplier (e.g., 2 for double the base interval)
    const maxMultiplier = 5; // Defines maximum multiplier (e.g., 5 for five times the base interval)
    const multiplier = Math.floor(minMultiplier + Math.random() * (maxMultiplier - minMultiplier));
    console.log('new interval', intervalTime * multiplier);
    return intervalTime * multiplier;
  }

  
  async function fetchHome() {
      try {
        const response = await axios.get("at-home/home-metadata.json");
        homeImages = response.data;
        console.log(homeImages);
        homeCount.set(homeImages.images.length - 1);
      } catch (error) {
        console.error(error);
      }
    }
  onMount(async () => {
    const response = await fetch('texts.json');
    texts = await response.json();
    fetchHome();

    shuffleArray(componentIndexes);

    const updateComponent = () => {
      if (isAutoChange) {
        if (showComponent) {
          if (currentIndex >= componentIndexes.length) {
            shuffleArray(componentIndexes); // Re-shuffle after going through all components
            currentIndex = 0; // Reset index
          }
          
          newIndex = componentIndexes[currentIndex++];
          
          if (newIndex >= 0 && newIndex <= 3) {
            whichInterval = 6000;
          } else if (newIndex === 4) {
            whichInterval = 12000;
          } else if (newIndex === 5) {
            whichInterval = getNewInterval() * 2;
          } else {
            whichInterval = getNewInterval();
          }
            showComponent = false;
          } else {
            whichInterval = 3000;
            newIndex = 100;
            showComponent = true;
          }
        setTimeout(updateComponent, whichInterval);
      }
    };




    updateComponent();
  }); 
  function setIndex(index) {
    newIndex = index;
  }
  function toggleAutoChange() {
    isAutoChange = !isAutoChange;
    if (isAutoChange) {
      currentIndex = 0;
      shuffleArray(componentIndexes);
    }
  }

  $: console.log('INDEX!!!!!!!!!!!!!!', newIndex);
</script>

<main>
  <Nav {setIndex} {toggleAutoChange} {isAutoChange} />
  {#if newIndex === 0}
  <div in:fade={{duration: 500}}
  >
    <Time {openingTime} {closingTime} />
  </div>
{:else if newIndex === 1}
  <div in:fade={{duration: 500}}
  >
  <VSequencer />
  </div>
{:else if newIndex === 2}
  <div in:fade={{duration: 500}}
  >
  <Text {texts} {intervalTime} />

  </div>
{:else if newIndex === 3}
  <div in:fade={{duration: 500}}
  >
    <LiveCameras />
  </div>
  {:else if newIndex === 4}
  <div in:fade={{duration: 500}}

  >
    <FetchData />
  </div>
  {:else if newIndex === 5}
    <div in:fade={{duration: 500}}>
      <FetchMaison />
  </div>
  {:else if newIndex === 6}
    <div in:fade={{duration: 500}}>
      <FetchArchive />
  </div>
  {:else if newIndex === 7}
    <div in:fade={{duration: 500}}>
      <Images />
  </div>
  {:else if newIndex === 100}
  <div in:fade={{duration: 500}}

  >
  </div>
  {/if}
</main>
