<?php

namespace Modules\Schooling\Livewire;

use Livewire\Component;
use Modules\Schooling\Models\PpdbRegistration;

class PpdbData extends Component
{
    public $registrations = [];
    public $selectedRegistration;
    public $showModal = false;
    public $statusOptions = ['Teregister', 'Terverifikasi', 'Diterima', 'Ditolak'];
    public $status;

    public function mount()
    {
        $this->registrations = PpdbRegistration::with(['applicant.parent', 'applicant.document'])->latest()->get();
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
