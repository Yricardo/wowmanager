import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        //todo implement toggle setttings stuff
        this.initGrid(false);
    }

    showFeed() {
        this.initGrid(true);
    }
    
    initGrid(withFeed) {
        const profileContainer = document.querySelector('.profile-container');
        if(withFeed)
        {
            profileContainer.style.gridTemplateAreas = '"feed menu" "feed user"';
            profileContainer.style.gridTemplateColumns = '1fr 3fr';
            profileContainer.style.gridTemplateRows = 'auto';
            return;
        }
        console.log('hiding feed');
        // Adjust the grid layout when the feed container is hidden
        profileContainer.style.gridTemplateAreas = '"menu menu" "user user"';
        profileContainer.style.gridTemplateColumns = '4fr 4fr';
        profileContainer.style.gridTemplateRows = 'auto'  
    }
    
    hideFeed() {
        this.initGrid(false);
    }
}