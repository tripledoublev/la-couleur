<script>
    import { onMount } from 'svelte';
    import { homeCount } from '../../stores';
  
    export let data; 
  let images = data.images;
  let totalLength = images.length - 1;
  let contentIndex = 0;
  let showingContent = true;
  let timer = 0; 
  let currentIndex;

 $: if ($homeCount === 0) {
    currentIndex = totalLength;
  } else {
    currentIndex = $homeCount;
  };
  
  function timeAgo(input) {
  let date = input;

  // Check if input is a string and try converting it to a Date object
  if (typeof input === 'string') {
    date = new Date(input);
  }
    if (!(date instanceof Date)) {
    console.log("Not a Date object:", date);
    return "Date format error";
  }
  if (isNaN(date.getTime())) {
    console.log("Invalid Date:", date);
    return "Invalid date";
  }
    const now = new Date();
    const difference = Math.abs(now - date); // Get the difference in milliseconds
    const minutesAgo = Math.floor(difference / (1000 * 60)); // Convert to minutes
    return `il y a ${minutesAgo} minutes`;
  }

  $: if(images[currentIndex]) console.log(images[currentIndex].timestamp, timeAgo(images[currentIndex].timestamp));


  onMount(() => {
    currentIndex = $homeCount;
    const interval = setInterval(() => {
      timer++; // Increment the timer every second

      if (timer < 5) {
        // For the first 5 seconds, show content
        showingContent = true;
      } else if (timer === 5) {
        // On the 6th second, hide content
        showingContent = false;
      }

      if (timer === 6) {
        // Reset the timer and contentIndex for the next cycle
        timer = 0;
        contentIndex = contentIndex < 3 ? contentIndex + 1 : 0;
        // Move to the next image after each full cycle
        if (contentIndex === 0) { // This now checks if we've cycled through all content views
        currentIndex = currentIndex > 0 ? currentIndex - 1 : images.length - 1;
      }
      }
    }, 1000); // Check every second

    return () => {
      clearInterval(interval); // Cleanup on component destruction
    };
  });
  $: console.log('CONTENT INDEX', contentIndex);
    $: console.log('SHOWING CONTENT', showingContent);
    $: console.log('images length', images.length);
    $: console.log('CURRENT INDEX', currentIndex);
    $: console.log('timer', timer);

    $: console.log(currentIndex === images.length ? ' est ' : 'était')
  </script>
  
  <div class="content">
    {#if images.length > 0} <!-- Ensure there are items to display -->
        {#if showingContent}
     {#if contentIndex === 0}
     <h2>{images[currentIndex].name}</h2>
       {:else if contentIndex === 1}     
       
     <img src={'at-home/' + images[currentIndex].fileName} alt={images[currentIndex].name}>

       {:else if contentIndex === 2}

   
       <h2>{images[currentIndex].name} 
        {currentIndex === images.length - 1 ? ' est ' : 'était'} la couleur du ciel au coin de {images[currentIndex].location}
    {currentIndex !== images.length - 1 ? timeAgo(images[currentIndex].timestamp) : ''}</h2>

       {:else if contentIndex === 3}


       <span class="color-block" style="background-color: {images[currentIndex].color};"></span>

     
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
    h2 {
        max-width: 64rem;
    }
    img {
        max-width: 100%;
    }

    h2, img {
      color: white;
      text-align: center;
      transition: opacity 0.5s ease-in-out; /* Smooth transition */
    }
  
    .color-block {
      width: 100vw;
      height: 100vh;
      margin: 0;
      transition: background-color 0.5s ease-in-out;
    }
  </style>
  