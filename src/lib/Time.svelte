<script>
    import { onMount, onDestroy } from 'svelte';
    import { fade } from 'svelte/transition';
    import { writable } from 'svelte/store';
    export let openingTime; 
    export let closingTime; 

    let now;
    let currentIndex = writable(0);
    let messages = writable([]);
    
    function updateMessages() {
    let newMessages = [];
    
    now = Math.floor(Date.now() / 1000);
    
    // Calculate times
    let timeUntilOpening = openingTime - now;
    let timeSinceOpening = now - openingTime;
    let timeUntilClosing = closingTime - now;
    let timeSinceClosing = now - closingTime;
  
    if (now < openingTime) {
      // Before the event starts
      newMessages.push(`Cette expérience débutera dans ${timeUntilOpening} secondes.`);
    } else {
      // After the event has started
      newMessages.push(`Cette expérience a débuté il y a ${timeSinceOpening} secondes.`);
    }
    if (now < closingTime) {
        // During the event
        newMessages.push(`Cette expérience se terminera dans ${timeUntilClosing} secondes.`);
      } else {
        // After the event has ended
        newMessages.push(`Cette expérience s'est terminée il y a ${timeSinceClosing} secondes.`);
      }
    messages.set(newMessages);
    $currentIndex = Math.floor(Math.random() * newMessages.length);
  }

  onMount(() => {
    const interval = setInterval(() => {
      now = Math.floor(Date.now() / 1000);
      updateMessages();
    }, 1000);

    // Initial update
    updateMessages();

    return () => {
      clearInterval(interval);
    };
  });

  </script>

  
<div class="text-container">
  {#if $messages.length > 0}
    <div in:fade={{duration: 150}}
          out:fade={{duration: 50}}
    >
      {$messages[$currentIndex]}
    </div>
  {/if}
</div>

<style>
  .text-container {
    color: white;
  }
</style>