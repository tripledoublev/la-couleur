<script>
    import { onMount } from 'svelte';
    import { writable } from 'svelte/store';
    import Character from './Character.svelte';

    export let word;
    let displayedChars = writable([]);

    onMount(() => {
        word.split('').forEach((char, index) => {
            setTimeout(() => {
                displayedChars.update(chars => [...chars, char]);
            }, index * 250); // 250ms delay per character
        });
    });
</script>

    {#each $displayedChars as char}
        <Character {char} />
    {/each}
