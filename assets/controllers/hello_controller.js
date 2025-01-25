import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [ "name" ]

    connect() {
        // this.element.textContent = 'Hello Stimulus! Edit me in assets/controllers/hello_controller.js';
    }

    greet() {
        const element = this.nameTarget
        const name = element.value
        console.log(`Hello, ${name}!`)
    }
}