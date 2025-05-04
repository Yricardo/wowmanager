function initGrid(withFeed = true)
{
    const profileContainer = document.querySelector('.profile-container');
    if(withFeed)
    {
        profileContainer.style.gridTemplateAreas = '"feed menu menu" "feed user character"';
        profileContainer.style.gridTemplateColumns = '1fr 2fr 1fr';
        profileContainer.style.gridTemplateRows = 'auto 1fr';
        profileContainer.style.gap = '10px'; // Optional: add gap between grid items        
        return;
    }
    // Adjust the grid layout when the feed container is hidden
    profileContainer.style.gridTemplateAreas = '"menu menu" "user character"';
    profileContainer.style.gridTemplateColumns = '2fr 1fr';
    profileContainer.style.gridTemplateRows = 'auto'  
    profileContainer.style.gap = '10px'; // Optional: add gap between grid items        
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        initGrid(true); // Initialize the grid with the feed visible
    });
} else {
    initGrid(true); // Initialize the grid with the feed visible
}

// on document loaded 
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('hide-feed').addEventListener('click', function() { hideFeed(); });
    document.getElementById('show-feed-btn').addEventListener('click', function() { showFeed(); });
});