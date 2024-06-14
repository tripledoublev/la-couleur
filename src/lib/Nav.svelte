<script>
    export let setIndex;
    export let toggleAutoChange;
    export let isAutoChange;
  
    // Emojis for the buttons
    const buttons = [
      { emoji: '🌡️', index: 4 }, // Thermometer
      { emoji: '🏠', index: 5 }, // House
      { emoji: '📅', index: 6 }, // Calendar or archive
      { emoji: '🎥', index: 7 }  // Film, projection
    ];
  </script>
  
  <nav>
    <button on:click={toggleAutoChange}>
      {isAutoChange ? '⚒️' : '🎬'}
    </button>
    
    {#if !isAutoChange}
      {#each buttons as button}
        <button on:click={() => setIndex(button.index)}>{button.emoji}</button>
      {/each}
    {/if}
  </nav>
  

  <style>
    nav { 
        position:absolute;
        left:1rem;
        top:1rem;    
    }
    nav button:not(:first-child) {
        margin-left: 1rem;
    }
    button {
        background-color: black;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
    }
  </style>