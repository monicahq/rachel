import "./bootstrap";

import "instant.page";

// --- Turbo Drive ---
import * as Turbo from "@hotwired/turbo";
window.Turbo = Turbo;
Turbo.session.drive = true; // explicit (enabled by default)

// --- Alpine ---
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import ajax from "@imacrayon/alpine-ajax";

Alpine.plugin(ajax);

// Start Alpine on the initial load (once)
document.addEventListener("DOMContentLoaded", () => {
  if (!document.documentElement.__alpined) {
    Livewire.start();
    document.documentElement.__alpined = true;
  }
});

// Re-initialize Alpine after every Turbo-driven navigation
addEventListener("turbo:load", () => {
  if (window.Alpine?.initTree) Alpine.initTree(document.body);
});
