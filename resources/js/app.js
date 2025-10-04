import "./bootstrap";

import "instant.page";

// --- Turbo Drive ---
// import * as Turbo from "@hotwired/turbo";
// window.Turbo = Turbo;
// Turbo.session.drive = true; // explicit (enabled by default)

// --- Alpine ---
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import ajax from "@imacrayon/alpine-ajax";

Alpine.plugin(ajax);
Livewire.start();

// Re-initialize Alpine after every Turbo-driven navigation
// addEventListener("turbo:load", () => {
//   if (window.Alpine?.initTree) Alpine.initTree(document.body);
// });
