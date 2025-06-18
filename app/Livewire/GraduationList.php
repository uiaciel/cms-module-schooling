<?php

namespace Modules\Schooling\Livewire;

use Livewire\Component;
use Modules\Schooling\Models\GraduationYear;

class GraduationList extends Component
{

    public $graduations;

    public function mount()
    {
        $this->graduations = GraduationYear::All();
    }

    public function render()
    {
        return view('schooling::livewire.graduation-list');
    }
}
