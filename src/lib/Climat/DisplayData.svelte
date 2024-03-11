<script>
  import { onMount } from 'svelte';
  import { fade } from 'svelte/transition';

    export let data;
    let temperatureData = data[0];
    let condition = temperatureData.WeatherText;
    let ressenti = temperatureData.RealFeelTemperature.Metric;
  
    let currentVisible = 0; // 0 for ressenti.Phrase, 1 for condition, 2 for ressenti.Value
  
  onMount(() => {
    const interval = setInterval(() => {
      currentVisible = (currentVisible + 1) % 3; // Cycle through 0, 1, 2
    }, 3000); // Change every 3 seconds

    return () => clearInterval(interval); // Cleanup on component destruction
  });
  
  </script>
  <div class="temperature">
    {#if currentVisible === 0}
      <h2>{ressenti.Phrase}</h2>
    {:else if currentVisible === 1}
      <h2>{condition}</h2>
    {:else if currentVisible === 2}
      <h2>{ressenti.Value} °{ressenti.Unit}</h2>
    {/if}
  </div>
  
  <style>
    .temperature {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh; /* Adjust based on your needs */
    }
  
    h2 {
      color: white;
      font-size: 6rem;
      font-weight: 400;
      margin: 0;
      text-align: center;
      transition: opacity 0.5s ease-in-out; /* Smooth transition for the text */
    }
  </style>