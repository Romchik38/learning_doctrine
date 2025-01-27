import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["slide"]
    static values = { index: Number }

    initialize() {
        this.len = this.slideTargets.length;
    }

    next() {
        if(this.indexValue === this.len - 1) return;
        this.indexValue++;      
    }

    previous() {
        if(this.indexValue === 0) return;
        this.indexValue--;
    }

    indexValueChanged() {
        this.showCurrentSlide()
      }    

    showCurrentSlide() {
        this.slideTargets.forEach((element, index) => {
            element.hidden = index !== this.indexValue;
        })
    }
}