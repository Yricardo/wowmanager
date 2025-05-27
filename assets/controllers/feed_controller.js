import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    connect() {
        this.hideFeed();
    }

    showFeed() {
        document.querySelector('.profile-feed').style.display = 'block';
        document.querySelector('#show-feed').style.display = 'none';
        document.querySelector('#hide-feed').style.display = 'block';
        //dispatch event to update the grid layout
        this.dispatch("showFeed", { details: { feed: true } })
        console.log('dispatching event');
    }

    hideFeed() {
        document.querySelector('.profile-feed').style.display = 'none';
        document.querySelector('#show-feed').style.display = 'block';
        document.querySelector('#hide-feed').style.display = 'none';
        //dispatch event to update the grid layout
        this.dispatch("hideFeed", { details: { feed: false } })
        console.log('dispatching event');
    }
}