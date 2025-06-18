<?php

namespace Modules\Schooling\Livewire;

use Livewire\Component;
use Modules\Schooling\Models\GraduationYear;

class GraduationCreate extends Component
{

    public $year;
    public $open_date;
    public $close_date;
    public $status;

    public function createGraduation()
    {
        $graduation = new GraduationYear;
        $graduation->year = $this->year;
        $graduation->open_date = $this->open_date;
        $graduation->close_date = $this->open_date;
        $graduation->status = $this->status;
        $graduation->save();

        session()->flash('message', 'Graduation created successfully.');

        $this->redirect(route('admin.graduation.index'));
    }

    public function render()
    {
        return view('schooling::livewire.graduation-create');
    }
}
