<?php

namespace Modules\Schooling\Livewire;

use Livewire\Component;
use Modules\Schooling\Models\PpdbPeriod;
use Modules\Schooling\Models\PpdbRegistration;

class PpdbData extends Component
{
    public $registrations = [];
    public $selectedRegistration;
    public $showModal = false;
    public $statusOptions = ['Teregister', 'Terverifikasi', 'Diterima', 'Ditolak'];
    public $status;
    public $year;
    public $ppdb;

    public function mount($year = null)
    {

        $this->ppdb = PpdbPeriod::where('year', $year)->first();
        $this->year = $year;
        $this->loadData();
    }

    public function loadData()
    {

        if ($this->ppdb) {
            $this->registrations = PpdbRegistration::where('ppdb_period_id', $this->ppdb->id)
                ->with(['applicant.parent', 'applicant.document'])
                ->latest()
                ->get();
        } else {
            $this->registrations = collect();
        }
    }

    public function showDetail($id)
    {
        $this->selectedRegistration = PpdbRegistration::with(['applicant.parent', 'applicant.document'])->find($id);
        $this->status = $this->selectedRegistration->status;
        $this->showModal = true;
    }

    public function updateStatus()
    {
        if ($this->selectedRegistration) {
            $this->selectedRegistration->status = $this->status;
            $this->selectedRegistration->save();
            session()->flash('success', 'Status berhasil diubah.');
            $this->showModal = false;
            $this->mount(); // refresh data
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('schooling::livewire.ppdb-data');
    }
}
