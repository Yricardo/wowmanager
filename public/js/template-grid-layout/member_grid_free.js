function initGrid(withFeed = true)
{
    const profileContainer = document.querySelector('.profile-container');
    if(withFeed)
    {
        profileContainer.style.gridTemplateAreas = '"feed menu menu" "feed content content"';
        profileContainer.style.gridTemplateColumns = '1fr 3fr'
        profileContainer.style.gridTemplateRows = 'auto';
        profileContainer.style.gap = '10px'; // Optional: add gap between grid items
        return;
    }
    profileContainer.style.gridTemplateAreas = '"menu" "content"';
    profileContainer.style.gridTemplateColumns = '1fr'
}

function showFeed()
{
    const feedContainer = document.querySelector('.feed-container');
    initGrid(true);
    if (feedContainer) {
        feedContainer.style.display = 'block';
    }
}

function hideFeed()
{
    const feedContainer = document.querySelector('.feed-container');
    initGrid(false);
    if (feedContainer) {
        feedContainer.style.display = 'none';
    }
}