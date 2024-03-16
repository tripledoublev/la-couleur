export function timeAgo(input) {
    let date;

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
    
    // Use the current time in UTC for comparison
    const now = new Date();
    const nowUtc = Date.UTC(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), 
                            now.getUTCHours(), now.getUTCMinutes(), now.getUTCSeconds());
    
    const difference = Math.abs(nowUtc - date.getTime()); // Get the difference in milliseconds
    const minutesAgo = Math.floor(difference / (1000 * 60)); // Convert to minutes
    return `il y a ${minutesAgo} minutes.`;
}