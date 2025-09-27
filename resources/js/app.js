import "./bootstrap";

import "instant.page";

// --- Turbo Drive ---
import * as Turbo from "@hotwired/turbo";
window.Turbo = Turbo;
Turbo.session.drive = true; // explicit (enabled by default)

// --- Alpine ---
import Alpine from "alpinejs";
import ajax from "@imacrayon/alpine-ajax";

window.Alpine = Alpine;
Alpine.plugin(ajax);

// Start Alpine on the initial load (once)
document.addEventListener("DOMContentLoaded", () => {
  if (!document.documentElement.__alpined) {
    Alpine.start();
    document.documentElement.__alpined = true;
  }
});

// Re-initialize Alpine after every Turbo-driven navigation
addEventListener("turbo:load", () => {
  if (window.Alpine?.initTree) Alpine.initTree(document.body);
});
