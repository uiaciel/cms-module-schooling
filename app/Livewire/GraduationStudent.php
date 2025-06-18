<?php

namespace Modules\Schooling\Livewire;

use Livewire\Component;
use \Carbon\Carbon;
use Modules\Schooling\Models\GraduationStudent as ModelsGraduationStudent;
use Modules\Schooling\Models\GraduationYear;
use Modules\Schooling\Imports\GraduationImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class GraduationStudent extends Component
{
    use WithFileUploads;

    public $file;
    public $graduation;
    public $year;
    public $students = [];
    public $editRow = null; // id baris yang sedang diedit
    public $editData = [];  // data sementara untuk edit

    public function mount($year)
    {
        $this->graduation = GraduationYear::where('year', $year)->first();
        $this->year = $year;
        $this->loadStudents();
    }

    public function import()
    {
        $this->validate([
            'file' => 'required|file|mimes:xls,xlsx,csv',
        ]);

        $uploadedFile = $this->file;
        if (!$uploadedFile) {
            $this->dispatch('notify', type: 'error', message: 'File tidak ditemukan.');
            return;
        }

        $originalName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $slugName = Str::slug($originalName);
        $extension = $uploadedFile->getClientOriginalExtension();
        $fileName = $slugName . '.' . $extension;

        $filePath = $uploadedFile->storeAs('xls', $fileName);

        Excel::import(new GraduationImport($this->graduation->id), storage_path('app/private/' . $filePath));

        $this->dispatch('notify', type: 'success', message: 'All good!');
        $this->file = null;
        $this->loadStudents();
    }

    public function loadStudents()
    {
        $this->students = ModelsGraduationStudent::where('graduation_year_id', $this->graduation->id ?? null)->get();
    }

    public function startEdit($id)
    {
        $student = ModelsGraduationStudent::findOrFail($id);
        $this->editRow = $id;
        $this->editData = [
            'sk' => $student->sk,
            'name' => $student->name,
            'nisn' => $student->nisn,
            'birth_date' => Carbon::parse($student->birth_date)->format('Y-m-d'),
            'graduation_status' => $student->graduation_status,
            'accessed_at' => $student->accessed_at ? date('Y-m-d', strtotime($student->accessed_at)) : null,
        ];
    }

    public function saveEdit($id)
    {
        $this->validate([
            'editData.sk' => 'required|string|max:255',
            'editData.name' => 'required|string|max:255',
            'editData.nisn' => 'required|numeric',
            'editData.birth_date' => 'required|date',
            'editData.graduation_status' => 'required|string',
            'editData.accessed_at' => 'nullable|date',
        ]);

        $student = ModelsGraduationStudent::findOrFail($id);
        $student->update($this->editData);

        $this->editRow = null;
        $this->editData = [];
        $this->loadStudents();
        session()->flash('success', 'Data siswa berhasil diupdate.');
    }

    public function cancelEdit()
    {
        $this->editRow = null;
        $this->editData = [];
    }

    public function render()
    {
        return view('schooling::livewire.graduation-student');
    }
}
