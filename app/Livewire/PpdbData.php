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
    public $statusOptions = ['registered', 'verified', 'accepted', 'rejected'];
    public $status;
    public $year;
    public $ppdb;
    public $selectedIds = [];
    public $filter_registration_code = '';
    public $filter_full_name = '';
    public $filter_status = '';
    public $selectAll = false;
    public $bulk_status = '';

    public $totalRegistrasi = 0;
    public $totalVerifikasi = 0;
    public $totalNonVerifikasi = 0;

    public function mount($year = null)
    {
        $this->ppdb = PpdbPeriod::where('year', $year)->first();
        $this->year = $year;
        $this->filter();

        $this->totalRegistrasi = PpdbRegistration::where('ppdb_period_id', $this->ppdb->id)->count();
        $this->totalVerifikasi = PpdbRegistration::where('ppdb_period_id', $this->ppdb->id)->where('status', 'registered')->count();
        $this->totalNonVerifikasi = PpdbRegistration::where('ppdb_period_id', $this->ppdb->id)->where('status', '!=', 'registered')->count();
    }

    public function filter()
    {
        $query = PpdbRegistration::query()->where('ppdb_period_id', $this->ppdb->id)
            ->with(['applicant.parent', 'applicant.document']);

        if ($this->filter_registration_code) {
            $query->where('registration_code', 'like', '%' . $this->filter_registration_code . '%');
        }
        if ($this->filter_full_name) {
            $query->whereHas('applicant', function ($q) {
                $q->where('full_name', 'like', '%' . $this->filter_full_name . '%');
            });
        }
        if ($this->filter_status) {
            $query->where('status', $this->filter_status);
        }

        $this->registrations = $query->get()->map(function ($item) {
            $arr = $item->toArray();
            $arr['applicant'] = $item->applicant ? (object) $item->applicant->toArray() : null;
            if ($arr['applicant']) {
                $arr['applicant']->parent = $item->applicant->parent ? (object) $item->applicant->parent->toArray() : null;
                $arr['applicant']->document = $item->applicant->document ? (object) $item->applicant->document->toArray() : null;
            }
            return (object) $arr;
        })->values()->toArray();

        $this->selectedIds = []; // Reset selected IDs setelah filter
        $this->selectAll = false; // Reset selectAll setelah filter
    }

    public function resetFilter()
    {
        $this->filter_registration_code = '';
        $this->filter_full_name = '';
        $this->filter_status = '';
        $this->filter();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedIds = collect($this->registrations)->pluck('id')->all();
        } else {
            $this->selectedIds = [];
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

            $this->closeModal();
            $this->filter(); // Memuat ulang data setelah pembaruan
        } else {
            session()->flash('error', 'Data tidak ditemukan.');
            $this->filter();
        }
    }

    public function bulkUpdateStatus()
    {
        if (empty($this->selectedIds) || empty($this->bulk_status)) {
            session()->flash('error', 'Pilih minimal satu pendaftar dan status yang valid.');
            return;
        }

        PpdbRegistration::whereIn('id', $this->selectedIds)->update([
            'status' => $this->bulk_status
        ]);

        session()->flash('success', count($this->selectedIds) . ' pendaftar berhasil diubah statusnya menjadi ' . $this->bulk_status . '.');
        $this->selectedIds = [];
        $this->selectAll = false;
        $this->bulk_status = '';
        $this->filter();
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
