function triggerFeedUpdate()
{
    //todo  implement
}

//depend on  the import of layout .js file to render/actualise the template grid layout, the template extending member_base.html.twig must load the one that fit the embeded base layout.
function showFeed()
{
    const feedContainer = document.querySelector('.profile-feed');
    initGrid(true);
    if (feedContainer) {
        feedContainer.style.display = 'block';
        document.getElementById('show-feed').style.display = 'none';
    }
}

//depend on  the import of layout .js file to render/actualise the template grid layout, the template extending member_base.html.twig must load the one that fit the embeded base layout.
function hideFeed()
{
    const feedContainer = document.querySelector('.profile-feed');
    initGrid(false);
    if (feedContainer) {
        feedContainer.style.display = 'none';
        document.getElementById('show-feed').style.display = 'block';
    }
}