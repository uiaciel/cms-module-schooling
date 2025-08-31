<?php

namespace Modules\Schooling\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Schooling\Models\PpdbPeriod;
use Modules\Schooling\Models\Applicant;
use Modules\Schooling\Models\PpdbRegistration;

use Modules\Schooling\Models\ParentData;
use Modules\Schooling\Models\PpdbDocument;

class Ppdb extends Component
{
    use WithFileUploads;

    public $step = 1;
    public $ppdb;
    public $ppdbs;
    public $slug;

    // Step 1: Applicant
    public $applicant = [
        'full_name' => '',
        'nickname' => '',
        'gender' => '',
        'place_of_birth' => '',
        'date_of_birth' => '',
        'religion' => '',
        'address' => '',
        'phone' => '',
        'previous_school' => '',
        'notes' => '',
    ];

    // Step 2: Parent
    public $parent = [
        'father_name' => '',
        'mother_name' => '',
        'father_job' => '',
        'mother_job' => '',
        'parent_address' => '',
        'parent_phone' => ''
    ];

    // Step 3: Document
    public $birth_certificate, $family_card, $photo, $certificate_pa;

    public $applicant_id;
    public $registration_code;
    public $pdf_link;

    public function mount($slug)
    {
        $this->slug = $slug;
        $this->ppdb = PpdbPeriod::with('applicants', 'registrations')->where('year', $slug)->firstOrFail();
        $this->ppdbs = PpdbRegistration::with('applicant')->where('ppdb_period_id', $this->ppdb->id)->get();
    }

    public function nextStep()
    {
        if ($this->step == 1) {
            $this->validate([
                'applicant.full_name' => 'required',
                'applicant.gender' => 'required',
                // ...validasi lain...
            ]);
            $applicant = $this->ppdb->applicants()->create($this->applicant);
            $this->applicant_id = $applicant->id;
            $this->step++;
            return;
        }
        if ($this->step == 2) {
            $this->validate([
                'parent.father_name' => 'required',
                // ...validasi lain...
            ]);
            ParentData::create(array_merge($this->parent, ['applicant_id' => $this->applicant_id]));
            $this->step++;
            return;
        }

        if ($this->step == 3) {

            $this->validate([
                'birth_certificate' => 'required|file|mimes:pdf,jpg,png',
                // ...validasi lain...
            ]);
            $year = $this->ppdb->year;
            $folder = "public/ppdb/{$year}";
            \Modules\Schooling\Models\PpdbDocument::create([
                'applicant_id' => $this->applicant_id,
                'birth_certificate' => $this->birth_certificate ? $this->birth_certificate->store($folder) : null,
                'family_card' => $this->family_card ? $this->family_card->store($folder) : null,
                'photo' => $this->photo ? $this->photo->store($folder) : null,
                'certificate_pa' => $this->certificate_pa ? $this->certificate_pa->store($folder) : null,
            ]);

            $today = now()->format('Y-m-d');
            $countToday = \Modules\Schooling\Models\PpdbRegistration::whereDate('created_at', $today)->count() + 1;
            $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2));
            $slug = $this->ppdb->year;
            $code = 'PPDB' . $slug . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT) . '-' . $random;
            // Simpan registration
            $registration = \Modules\Schooling\Models\PpdbRegistration::create([
                'applicant_id' => $this->applicant_id,
                'registration_code' => $code,
                'status' => 'registered',
                'notes' => 'Menunggu Verifikasi',
                'registered_at' => now(),
                'ppdb_period_id' => $this->ppdb->id,
            ]);
            $this->registration_code = $code;
            $this->pdf_link = route('ppdb.registration.pdf', $registration->id);

            $this->step++;
            $this->dispatch('downloadPdf', route('ppdb.registration.pdf', $registration->id));

            $this->dispatch('registrationFinished');
            return;
            return;
        }
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function submit()
    {
        $this->validate([
            'birth_certificate' => 'required|file|mimes:pdf,jpg,png',
            // ...validasi lain...
        ]);
        $year = $this->ppdb->year;
        $folder = "public/ppdb/{$year}";

        PpdbDocument::create([
            'applicant_id' => $this->applicant_id,
            'birth_certificate' => $this->birth_certificate ? $this->birth_certificate->store($folder) : null,
            'family_card' => $this->family_card ? $this->family_card->store($folder) : null,
            'photo' => $this->photo ? $this->photo->store($folder) : null,
            'certificate_pa' => $this->certificate_pa ? $this->certificate_pa->store($folder) : null,
        ]);
        session()->flash('success', 'Pendaftaran berhasil!');
        return redirect()->route('ppdb.info', $this->slug);
    }

    public function render()
    {
        $theme = \App\Models\Setting::first()->active_theme ?? config('frontend.active', 'default');
        return view('schooling::livewire.ppdb')
            ->layout("frontend.{$theme}.app");
    }
}
