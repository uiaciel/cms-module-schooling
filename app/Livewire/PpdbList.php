<?php

namespace Modules\Schooling\Livewire;

use Livewire\WithFileUploads;
use Livewire\Component;
use Modules\Schooling\Models\PpdbPeriod;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Schooling\Exports\PpdbExport;

class PpdbList extends Component
{
    use WithFileUploads;

    public $ppdbs;
    public $showEditModal = false;
    public $editingPpdbId = null;
    public $year;
    public $start_date;
    public $end_date;
    public $description;
    public $status;
    public $brochure_pdf;
    public $brochure_img;

    protected $rules = [
        'year' => 'required|integer|unique:ppdb_periods,year',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'description' => 'nullable|string',
        'status' => 'required|string',
    ];

    public function mount()
    {
        $this->loadPpdbs();
    }

    public function loadPpdbs()
    {
        $this->ppdbs = PpdbPeriod::orderBy('year', 'desc')->get();
    }

    public function createPpdb()
    {
        $this->resetValidation();
        $this->reset(['editingPpdbId', 'year', 'start_date', 'end_date', 'description', 'status', 'brochure_pdf', 'brochure_img']);
        $this->showEditModal = true;
    }

    public function editPpdb($id)
    {
        $this->resetValidation();
        $ppdb = PpdbPeriod::findOrFail($id);
        $this->editingPpdbId = $ppdb->id;
        $this->year = $ppdb->year;
        $this->start_date = $ppdb->start_date;
        $this->end_date = $ppdb->end_date;
        $this->description = $ppdb->description;
        $this->status = $ppdb->status;
        $this->reset(['brochure_pdf', 'brochure_img']);
        $this->showEditModal = true;
    }

    public function savePpdb()
    {
        $rules = $this->rules;
        if ($this->editingPpdbId) {
            $rules['year'] = 'required|integer|unique:ppdb_periods,year,' . $this->editingPpdbId;
        }
        $rules['brochure_pdf'] = 'nullable|file|mimes:pdf|max:2048';
        $rules['brochure_img'] = 'nullable|image|max:2048';

        $this->validate($rules);

        $data = [
            'year' => $this->year,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'description' => $this->description,
            'status' => $this->status,
        ];

        if ($this->brochure_pdf) {
            $data['brochure_pdf'] = $this->brochure_pdf->store('ppdb', 'public');
        }

        if ($this->brochure_img) {
            $data['brochure_img'] = $this->brochure_img->store('ppdb', 'public');
        }

        if ($this->editingPpdbId) {
            $ppdb = PpdbPeriod::findOrFail($this->editingPpdbId);
            $ppdb->update($data);
            session()->flash('success', 'Data PPDB berhasil diperbarui.');
        } else {
            PpdbPeriod::create($data);
            session()->flash('success', 'Data PPDB berhasil ditambahkan.');
        }

        $this->showEditModal = false;
        $this->loadPpdbs(); // Refresh the list
    }

    public function deletePpdb($id)
    {
        $ppdb = PpdbPeriod::with(['applicants.parent', 'applicants.document', 'registrations'])->find($id);

        if ($ppdb) {
            // Delete related registrations
            foreach ($ppdb->registrations as $registration) {
                $registration->delete();
            }

            // Delete related applicants, parents, and documents
            foreach ($ppdb->applicants as $applicant) {
                if ($applicant->parent) {
                    $applicant->parent->delete();
                }
                if ($applicant->document) {
                    $applicant->document->delete();
                }
                $applicant->delete();
            }

            $ppdb->delete();
            session()->flash('success', 'Data PPDB dan semua data terkait berhasil dihapus.');
        } else {
            session()->flash('error', 'Data PPDB tidak ditemukan.');
        }
        $this->loadPpdbs();
    }

    public function exportExcel($id)
    {
        $tahun = PpdbPeriod::where('id', $id)->first();
        $date = now()->format('Y-m-d');

        return Excel::download(new PpdbExport($id), 'data-pendaftar-' . $tahun->year . '-download_tgl-' . $date . '.xlsx');
    }

    public function render()
    {
        return view('schooling::livewire.ppdb-list');
    }
}
