import { Controller } from '@hotwired/stimulus';

import OverviewGridController from "./overviewgrid_controller.js";
import FreeGridController from "./freegrid_controller.js";

export default class extends Controller {
    connect() {
        this.init();
    }

    init() {
        if (document.readyState === 'loading') { 
            document.addEventListener('DOMContentLoaded', () => this.init());
        } else {
            //todo refactor this when more grid will be supported
            const layout = document.querySelector("#page-state").dataset.layout;
            let controllerName = null;
            if (layout === 'overview') {
                this.application.register("overviewgrid", OverviewGridController);
                controllerName = 'overviewgrid';
            } else if (layout === 'free') {
                this.application.register("freegrid", FreeGridController);
                controllerName = 'freegrid';
            }
            if (!controllerName) {
                throw new Error('Invalid layout type. Expected "overview" or "free".');
            }

            document.querySelector('.profile-container').setAttribute('data-action', `feed:showFeed->${controllerName}#showFeed feed:hideFeed->${controllerName}#hideFeed`);

            // Switch controller on the profile-container
            this.element.setAttribute('data-controller', controllerName + ' feed');
        }
    }
}