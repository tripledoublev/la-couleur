<script>
    import { onMount, onDestroy } from 'svelte';

    export let char;

    let displayChar = '.';
    const chars = " AÀBCDEÉÈÊFGHIJKLMNOPQRSTUVWXYZaàbcdeéèêfghijklmnopqrstuvwxyz.,/;[]\\!@#$%^&*()-_+={}|\:\"'<>,?`~0123456789".split('');
    let currentIndex = 0;
    let targetIndex;
    let interval;

    onMount(() => {
        targetIndex = chars.indexOf(char); // Find the index of the target character

        interval = setInterval(() => {
            displayChar = chars[currentIndex];
            currentIndex++;

            if (currentIndex > targetIndex) {
                clearInterval(interval);
                if (char === ' ') {
                    displayChar = '\u00A0'; // Use non-breaking space for visibility
                }
            }
        }, 25);
    });

    onDestroy(() => {
        clearInterval(interval);
    });
</script>

{displayChar}
