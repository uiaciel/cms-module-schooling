<?php

namespace Modules\Schooling\Livewire;

use Livewire\Component;
use Modules\Schooling\Models\PpdbPeriod;

class PpdbCreate extends Component
{
    public $year;
    public $start_date;
    public $end_date;
    public $description;

    public function createPpdb()
    {
        $ppdb = new PpdbPeriod;
        $ppdb->year = $this->year;
        $ppdb->start_date = $this->start_date;
        $ppdb->end_date = $this->end_date;
        $ppdb->description = $this->description;
        $ppdb->save();

        session()->flash('message', 'PPDB created successfully.');

        $this->redirect(route('admin.ppdb.index'));
    }

    public function render()
    {
        return view('schooling::livewire.ppdb-create');
    }
}
