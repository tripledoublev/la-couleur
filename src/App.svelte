<script>
  import { onMount } from 'svelte';
  import { fade } from 'svelte/transition';

  import Time from './lib/Time.svelte';
  import Text from './lib/Text.svelte';
  import VSequencer from './lib/VSequencer.svelte';
  import LiveCameras from './lib/LiveCameras.svelte';
  import FetchArchive from './lib/Couleur/FetchArchive.svelte';
  import FetchData from './lib/Climat/FetchData.svelte';

  const openingTime = 1710606462; 
  const closingTime = 1712507262; 

  let isInterval = true;

  let intervalTime = 5000; 
  let lastComponentIndex = -1; 
  let newIndex;
  let whichInterval;
  let texts = [];

  // Function to generate a new, non-repeating componentIndex
  function getNewComponentIndex() {
    let newIndex;
    do {
      newIndex = Math.floor(Math.random() * 12); // Here is where you need to adjust for the number of components
    } while (newIndex === lastComponentIndex); 
    return newIndex;
  }

  // Function to generate a new interval within a specified range
  function getNewInterval() {
    const minMultiplier = 2; // Defines minimum multiplier (e.g., 2 for double the base interval)
    const maxMultiplier = 12; // Defines maximum multiplier (e.g., 5 for five times the base interval)
    const multiplier = minMultiplier + Math.random() * (maxMultiplier - minMultiplier);
    return intervalTime * multiplier;
  }

  onMount(async () => {
    const response = await fetch('texts.json');
    texts = await response.json();

    const updateComponent = () => {
      if (isInterval) {
        newIndex = getNewComponentIndex();
        if (newIndex === 0) {
          whichInterval = 5000;
        } else if (newIndex === 4) {
          whichInterval = 9000;
        } else if (newIndex === 5) {
          whichInterval = getNewInterval() * 3;
        } else {
          whichInterval = getNewInterval();
        }
      } else {
        whichInterval = 2345;
        newIndex = 100;
      }
      lastComponentIndex = newIndex; 
      isInterval = !isInterval;
      setTimeout(updateComponent, whichInterval);
    };

    updateComponent(); // Initialize the first update
  });
</script>

<main>
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
  {:else if newIndex >= 5 && newIndex <= 11}
    <div in:fade={{duration: 500}}>
    <FetchArchive />
  </div>
  {:else if newIndex === 100}
  <div in:fade={{duration: 500}}

  >
  </div>
  {/if}
</main>
