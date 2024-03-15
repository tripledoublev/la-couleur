<script>
    import axios from 'axios';
    import DisplayData from './DisplayData.svelte';
    import { onMount } from 'svelte';
  
    let weatherData = null;
  
    async function fetchWeatherData() {
      try {
        const response = await axios.get("at-home/home-metadata.json");
        weatherData = response.data;
        console.log(weatherData);
      } catch (error) {
        console.error(error);
      }
    }
  
    onMount(() => {
        fetchWeatherData();
    });
  </script>
  
  {#if weatherData}
    <DisplayData data={weatherData} />
  {/if}
  