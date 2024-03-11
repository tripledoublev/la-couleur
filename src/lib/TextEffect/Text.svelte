<script>
    import { writable } from 'svelte/store';
    import Word from './Word.svelte';

    export let text;
    let originalWords = text.split(' ');
    let displayedWords = writable([]);


    let delay = 500;


      originalWords.forEach((word, index) => {
        setTimeout(() => {
            displayedWords.update(currentWords => [...currentWords, word]);
        }, index * delay);
    });
</script>


<div>
    {#each $displayedWords as word, index}
            <Word {word} />
        {#if index < $displayedWords.length - 1}
                <Word word=" " />
        {/if}
    {/each}
</div>
