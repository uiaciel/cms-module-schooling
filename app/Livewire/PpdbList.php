<?php

namespace Modules\Schooling\Livewire;

use Livewire\Component;
use Modules\Schooling\Models\PpdbPeriod;

class PpdbList extends Component
{

    public $ppdbs;

    public function mount()
    {
        $this->ppdbs = PpdbPeriod::All();
    }

    public function render()
    {
        return view('schooling::livewire.ppdb-list');
    }
}
