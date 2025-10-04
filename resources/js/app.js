import "./bootstrap";

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
Livewire.start();

// Re-initialize Alpine after every Turbo-driven navigation
// addEventListener("turbo:load", () => {
//   if (window.Alpine?.initTree) Alpine.initTree(document.body);
// });
