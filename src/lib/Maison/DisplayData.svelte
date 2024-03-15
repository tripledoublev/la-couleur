<script>
    import { onMount } from 'svelte';
  
    export let data; 
    let images = data.images;
    let currentIndex = images.length - 1;
    let currentVisible = 0; 
    let pauseState = false;
  
    onMount(() => {
      const intervals = [];
      intervals.push(setInterval(() => {
        pauseState = false;
        // Change the view (color description, color block, image) every 3 seconds
        if (currentVisible < 3) {
          currentVisible += 1;
          console.log(currentVisible);
        } else {
          // Once all views have been shown for the current item, move to the previous item
          currentVisible = 0;
          currentIndex = currentIndex > 0 ? currentIndex - 1 : images.length - 1;
        }
      }, 6000));

      intervals.push(setInterval(() => { 
            pauseState = true;
        }, 5000));
  
      return () => intervals.map((a) => {
        clearInterval(a);
      }); // Cleanup on component destruction
    });
  </script>
  
  <div class="content">
    {#if images.length > 0} <!-- Ensure there are items to display -->
     {#if !pauseState}
     {#if currentVisible === 0}
     <img src={'at-home/' + images[currentIndex].fileName} alt={images[currentIndex].name}>

       {:else if currentVisible === 1}        
       <h2>{images[currentIndex].name} 
        {currentIndex === images.length ? ' est ' : 'était'} la couleur du ciel au coin de {images[currentIndex].location}</h2>

       {:else if currentVisible === 2}
       <span class="color-block" style="background-color: {images[currentIndex].color};"></span>
       {:else if currentVisible === 3}

       <h2>{images[currentIndex].name}</h2>
     
     {/if}
      {/if}
    {/if}
  </div>
  
  <style>
    .content {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      height: 100vh; /* Adjust based on your needs */
    }
  
    h2, img {
      color: white;
      text-align: center;
      transition: opacity 0.5s ease-in-out; /* Smooth transition */
      max-width: 64rem;
    }
  
    .color-block {
      width: 100vw;
      height: 100vh;
      margin: 0;
      transition: background-color 0.5s ease-in-out;
    }
  </style>
  