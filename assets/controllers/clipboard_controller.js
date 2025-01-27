// src/controllers/clipboard_controller.js
import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["source"]
    static classes = [ "supported" ]

    copy() {
        navigator.clipboard.writeText(this.sourceTarget.value);
    }

    connect() {
        if ("clipboard" in navigator) {
          this.element.classList.add(this.supportedClass);
        }
      }
}