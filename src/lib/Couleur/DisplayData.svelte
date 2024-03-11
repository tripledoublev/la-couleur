<script>
    import { onMount } from 'svelte';
  
    export let data; // The array of objects is now passed as 'images'
    let images = data.images;
    let currentIndex = images.length - 1; // Start with the last item
    let currentVisible = 3; // 0 for the color description, 1 for color block, 2 for image
  
    onMount(() => {
      const interval = setInterval(() => {
        console.log('images', images);
        // Change the view (color description, color block, image) every 3 seconds
        if (currentVisible < 3) {
          currentVisible += 1;
        } else {
          // Once all views have been shown for the current item, move to the previous item
          currentVisible = 0;
          currentIndex = currentIndex > 0 ? currentIndex - 1 : images.length - 1;
        }
      }, 5000);
  
      return () => clearInterval(interval); // Cleanup on component destruction
    });
  </script>
  
  <div class="content">
    {#if images.length > 0} <!-- Ensure there are items to display -->
      {#if currentVisible === 0}
        <h2>{images[currentIndex].name}</h2>
        {:else if currentVisible === 1}
        <span class="color-block" style="background-color: {images[currentIndex].color};"></span>
        {:else if currentVisible === 2}
        <h2>{images[currentIndex].name} est la couleur du ciel au coin de {images[currentIndex].location}</h2>

      {:else if currentVisible === 3}
        <img src={'archive/' + images[currentIndex].fileName} alt={images[currentIndex].name}>
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
      width: 16vw;
      height: 9vw;
      margin: 20px;
      transition: background-color 0.5s ease-in-out;
    }
  </style>
  