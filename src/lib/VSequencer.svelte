<script>
    import { onMount, tick } from "svelte";
    import Video from "./Video.svelte";
    import videoCameras from "../assets/cameras";
  
    const intervalTime = 2345; 
    const preloadTime = intervalTime * 0.5;
    const fadeTime = intervalTime * 0.99;
  
    let videoUrls = videoCameras.map((video) => video.url);
    let currentVideoIndex = Math.floor(Math.random() * videoUrls.length);
    let nextVideoIndex = 1;
  
    let currentVideoVisible = true;
    let nextVideoVisible = false;
  
    // Function to preload and transition to the next video
    async function transitionVideos() {
      // Preload next video at 80% of the interval
      setTimeout(() => {
        nextVideoIndex = (currentVideoIndex + 1) % videoUrls.length;
      }, preloadTime);
  
      // Fade current video out and next video in at 98% of the interval
      setTimeout(() => {
        currentVideoVisible = false;
        nextVideoVisible = true;
      }, fadeTime);
  
      // Switch videos at 100% of the interval
      setTimeout(() => {
        currentVideoIndex = nextVideoIndex;
      }, intervalTime);
    }
  
    onMount(() => {
      const interval = setInterval(transitionVideos, intervalTime);
  
      return () => {
        clearInterval(interval); // clear interval on component destruction
      };
    });
  
    // Make sure the videos are loaded in the DOM before playing
    $: loaded = false;
    onMount(() => {
        console.log('onMount');
      tick().then(() => {
        loaded = true;
      });
    });
  </script>
  
  
  <Video url={videoUrls[currentVideoIndex]} description={`Video ${currentVideoIndex + 1}`} visible={currentVideoVisible} />
  <Video url={videoUrls[nextVideoIndex]} description={`Video ${nextVideoIndex + 1}`} visible={nextVideoVisible} />
