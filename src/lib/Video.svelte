<script>
    import { fade } from 'svelte/transition';

  export let url;
  export let description;
  export let fadeTime = 100;
  export let visible = true;

  let videoElement;

  $: if (videoElement && url) {
    changeSource();
  }

  async function changeSource() {
    videoElement.src = url;
    videoElement.load();
    try {
      await videoElement.play();
    } catch (e) {
      console.error('Error playing video:', e);
    }
  }
</script>

<video
bind:this={videoElement}
width="100%"
muted
autoplay
preload="auto"
aria-label={description}
on:stalled={() => console.warn('Video stalled')}
in:fade={{ duration: fadeTime }}  
out:fade={{ duration: fadeTime }} 
class:visible={visible}
>
<source src={url} type="video/mp4" />
Your browser does not support the video tag.
</video>

<style>
  video {
    position: fixed;
    top: -5px;
    left: 0%;
    min-width: 100vw;
    min-height: 56.25vw;
    width: 100vw;
    height: auto;
    z-index: -1;
    transition: opacity 1s ease-in-out;
    z-index:999;
  }


</style>
