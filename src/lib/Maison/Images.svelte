<script>
    import axios from 'axios';
    import { onMount } from 'svelte';
    let dataImg;
    let totalLength;
    let timer = 0; 
    let showingContent = true;
    let index;

  
    async function fetchWeatherData() {
      try {
        const response = await axios.get("at-home/home-metadata.json");
        console.log(response.data.images.length);
        dataImg = response.data.images;
        totalLength = dataImg.length - 1;
        index = totalLength;
        console.log(dataImg);
      } catch (error) {
        console.error(error);
      }
    }
  
    
  onMount(() => {
    fetchWeatherData();
    const interval = setInterval(() => {
      timer++; // Increment the timer every second

/*       if (timer < 5) {
        // For the first 5 seconds, show content
        showingContent = true;
      } else if (timer === 5) {
        // On the 6th second, hide content
        showingContent = false;
      }
 */
      if (timer === 1) {
        // Reset the timer and contentIndex for the next cycle
        timer = 0;
        index = index > 1 ? index - 1 : totalLength;
      
      }
    }, 1000); // Check every second

    return () => {
      clearInterval(interval); // Cleanup on component destruction
    };
  });

  $: console.log('SHOWING CONTENT', showingContent);
    $: console.log('CURRENT INDEX', index);
    $: console.log('timer', timer);

  </script>
  
{#if dataImg && dataImg.length > 0}
  {#if showingContent}
    {#if dataImg[index].name && dataImg[index].fileName}
  <img src={'at-home/' + dataImg[index].fileName} alt={dataImg[index].name}>
    {/if}
  {/if}
{/if}