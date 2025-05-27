import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.initGrid(false); 
        //catch the event to update the grid layout
        this.hideFeed();
    }

    initGrid(withFeed) {
        if(withFeed)
        {
            document.querySelector('.profile-container').style.gridTemplateAreas = '"feed menu menu" "feed user character"';
            document.querySelector('.profile-container').style.gridTemplateColumns = '1fr 2fr 1fr';
            document.querySelector('.profile-container').style.gridTemplateRows = 'auto 1fr';
            document.querySelector('.profile-container').style.gap = '10px'; // Optional: add gap between grid items        
            return;
        }
        // Adjust the grid layout when the feed container is hidden
        document.querySelector('.profile-container').style.gridTemplateAreas = '"menu menu menu" "user user character"';
        document.querySelector('.profile-container').style.gridTemplateColumns = '2fr 1fr 1fr';
        document.querySelector('.profile-container').style.gridTemplateRows = 'auto'  
        document.querySelector('.profile-container').style.gap = '10px';        
    }

    showFeed() {
        this.initGrid(true);
    }
        
    hideFeed() {
        this.initGrid(false);
    }

}