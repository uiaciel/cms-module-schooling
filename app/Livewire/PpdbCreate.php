<?php

namespace Modules\Schooling\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Schooling\Models\PpdbPeriod;
use Illuminate\Support\Facades\Storage;

class PpdbCreate extends Component
{
    use WithFileUploads;

    public $year;
    public $start_date;
    public $end_date;
    public $description;
    public $brochure_pdf;
    public $brochure_img;

    public function createPpdb()
    {
        $ppdb = new PpdbPeriod;
        $ppdb->year = $this->year;
        $ppdb->start_date = $this->start_date;
        $ppdb->end_date = $this->end_date;
        $ppdb->description = $this->description;

        // Handle brochure PDF upload
        if ($this->brochure_pdf) {
            $pdfPath = $this->brochure_pdf->store('ppdb', 'public');
            $ppdb->brochure_pdf = $pdfPath;
        }

        // Handle brochure image upload
        if ($this->brochure_img) {
            $imgPath = $this->brochure_img->store('ppdb', 'public');
            $ppdb->brochure_img = $imgPath;
        }

        $ppdb->save();

        session()->flash('message', 'PPDB created successfully.');

        $this->redirect(route('admin.ppdb.index'));
    }

    public function render()
    {
        return view('schooling::livewire.ppdb-create');
    }
}
