import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static values = { url: String, refreshInterval: Number }

    connect() {
        this.load();

        if (this.hasRefreshIntervalValue) {
            this.startRefreshing();
        }
    }

    load() {
        fetch(this.urlValue)
            .then(response => response.text())
            .then(html => {
                this.element.innerHTML = html;
            })
    }

    startRefreshing() {
        this.refreshTimer = setInterval(() => {
            this.load()
        }, this.refreshIntervalValue)
    }

    stopRefreshing() {
        if (this.refreshTimer) {
            clearInterval(this.refreshTimer);
            this.refreshTimer = null;
        }
    }

    disconnect() {
        this.stopRefreshing();
    }
}