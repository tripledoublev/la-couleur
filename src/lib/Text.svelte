<script>
  import { onMount, onDestroy } from 'svelte';
  import { fade } from 'svelte/transition';
  import { writable } from 'svelte/store';

  export let texts = [];
  export let intervalTime = 3000; // Interval time for each text and empty display

  let currentIndex = writable(0); // This will now increase twice per text cycle
  let displayedTexts = [];

  function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [array[i], array[j]] = [array[j], array[i]];
    }
  }

  $: if (texts.length > 0) {
    displayedTexts = [...texts];
    shuffleArray(displayedTexts);
    currentIndex.set(0); // Reset the index
  }

  let interval;

  onMount(() => {
    interval = setInterval(() => {
      currentIndex.update(n => {
        // Check if we've shown all texts and an empty state for each
        if (n >= (displayedTexts.length * 2) - 1) {
          shuffleArray(displayedTexts);
          return 0;
        } else {
          return n + 1;
        }
      });
    }, intervalTime);
  });
  
  onDestroy(() => {
      clearInterval(interval);
    });
</script>

<div class="text-container">
  {#if displayedTexts.length > 0 && $currentIndex % 2 === 0}
    <div in:fade={{duration: 150}}
          out:fade={{duration: 50}}
    >
      {displayedTexts[Math.floor($currentIndex / 2)]}
    </div>
  {/if}
</div>

<style>
  .text-container {
    color: white;
  }
</style>
