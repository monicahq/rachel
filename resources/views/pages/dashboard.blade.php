<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component
{
    public function render()
    {
        return $this->view()
            ->title(__('Dashboard'));
    }
}; ?>

<div>

</div>
