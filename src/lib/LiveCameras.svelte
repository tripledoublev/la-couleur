<script>
    import { onMount } from 'svelte';
    import { cameras } from '../assets/mtlcameras';
    import Text from './TextEffect/Text.svelte';
    let imageElement;
    let refetchTimer;
  
    let currentSrc = '';
    let filteredUrls = [];
    let currentIndex = 0;
    let shuffledCameras = [];
  
    let isLoading = true;

    let processIndex = 0; // Tracks the index of the last processed image

// Function to shuffle an array (Fisher-Yates shuffle algorithm)
function shuffleArray(array) {
    let shuffled = array.slice(); // Clone the array to avoid modifying the original
    for (let i = shuffled.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]]; // Swap elements
    }
    return shuffled;
}

// Function to get the next batch of images
function getNextBatch(imageUrls) {
    // Calculate the remaining items in the array
    const remainingItems = imageUrls.length - processIndex;
    // If there are less than 32 items left, reset to start from the beginning
    if (remainingItems < 32) {
        processIndex = 0; // Reset index if not enough images left for a full batch
    }
    const batch = imageUrls.slice(processIndex, processIndex + 32);
    processIndex += 32; // Update the process index for the next call
    return batch;
}
  
    // Function to start the refetch timer
  function startRefetchTimer() {
    clearTimeout(refetchTimer);
    refetchTimer = setTimeout(() => {
      isLoading = false;
    }, 6000); // 6 seconds
  }
  
  // Function to process a single image
  async function processImage(imageUrl) {
  
    return new Promise(async (resolve, reject) => {
      const corsProxy = 'proxy.php?url=';
      try {
        const response = await fetch(corsProxy + imageUrl);
        const blob = await response.blob();
        const objectUrl = URL.createObjectURL(blob);
        const img = new Image();
        img.src = objectUrl;
  
        img.onload = async () => {
          const canvas = document.createElement('canvas');
          canvas.width = img.width;
          canvas.height = img.height;
          const ctx = canvas.getContext('2d');
          ctx.drawImage(img, 0, 0);
          const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
  
          let blackPixelCount = 0;
          for (let i = 0; i < imageData.data.length; i += 4) {
            const red = imageData.data[i];
            const green = imageData.data[i + 1];
            const blue = imageData.data[i + 2];
            if (red < 50 && green < 50 && blue < 50) {
              blackPixelCount++;
            }
          }
  
          const blackPixelPercentage = (blackPixelCount / (imageData.width * imageData.height)) * 100;
          URL.revokeObjectURL(objectUrl);
  
          resolve(blackPixelPercentage < 80); // Keep the image if less than 80% black
        };
  
        img.onerror = () => {
          URL.revokeObjectURL(objectUrl);
          console.error('Error loading image: ' + imageUrl);
          resolve(false);
        };
      } catch (error) {
        reject(error);
      }
    });
  }
  
  // Function to process and filter all images
  async function processAndFilterImages() {
    // Only shuffle the cameras array if it's the first time or we've gone through all images
    if (shuffledCameras.length === 0 || processIndex >= shuffledCameras.length) {
        shuffledCameras = shuffleArray(cameras); // Shuffle cameras array
        processIndex = 0; // Reset the process index
    }

    const batchUrls = getNextBatch(shuffledCameras);
    const promises = batchUrls.map(url => processImage(url));
    const results = await Promise.all(promises);
    startRefetchTimer();
    
    filteredUrls = batchUrls.filter((_, index) => results[index]);
    if (filteredUrls.length > 0) {
        currentSrc = filteredUrls[0];
        setInterval(changeImage, 333);
    }
}
  
  // Function to change image
  function changeImage() {
    currentIndex = (currentIndex + 1) % filteredUrls.length;
    currentSrc = filteredUrls[currentIndex];
  }
  
  // Run on component mount
  onMount(async () => {
    isLoading = true;
    await processAndFilterImages();
    startRefetchTimer();
  });
  </script>
  {#if !isLoading}
  <img bind:this={imageElement} src={currentSrc} alt="Couleur Montréal">
  {:else}
  <h2>Le 12 mars 2024, la ville de Montréal a cessé de mettre à jour les images des caméras de circulations. Elles sont toujours accessibles en ligne, figées dans le temps.</h2>
  {/if}
  
  <style>
    h2 {
      max-width: 80vw;
    }
  </style>