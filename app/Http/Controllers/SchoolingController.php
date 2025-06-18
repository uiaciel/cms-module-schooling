<?php

namespace Modules\Schooling\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Schooling\Models\GraduationStudent;
use Illuminate\Http\Request;
use Modules\Schooling\Models\GraduationYear;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Schooling\Models\Applicant;
use Modules\Schooling\Models\PpdbPeriod;

class SchoolingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function registrasi()
    {
        // $activePeriod = PpdbPeriod::whereDate('start_date', '<=', now())
        //     ->whereDate('end_date', '>=', now())
        //     ->firstOrFail();

        // return view('ppdb.form', compact('activePeriod'));

        $ppdbs = PpdbPeriod::All();

        return view('frontend::ppdb.registrasi', compact('ppdbs'));
    }

    public function saveregister(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'nickname' => 'nullable|string',
            'gender' => 'required|in:L,P',
            'place_of_birth' => 'required|string',
            'date_of_birth' => 'required|date',
            'religion' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'nullable|string',
            'previous_school' => 'nullable|string',
            'ppd_period_id' => 'required|exists:ppd_periods,id',
        ]);

        $applicant = new Applicant;
        $applicant->full_name = $request->full_name;
        $applicant->nickname = $request->nickname;
        $applicant->gender = $request->gender;
        $applicant->place_of_birth = $request->place_of_birth;
        $applicant->date_of_birth = $request->date_of_birth;
        $applicant->religion = $request->religion;
        $applicant->address = $request->address;
        $applicant->phone = $request->phone;
        $applicant->previous_school = $request->previous_school;
        $applicant->ppd_period_id = $request->ppd_period_id;
        $applicant->save();

        return redirect()->route('ppdb.success', ['id' => $applicant->id]);
    }

    public function success($id)
    {
        $applicant = Applicant::findOrFail($id);

        return view('frontend::ppdb.sukses', compact('applicant'));
    }

    protected static function booted()
    {
        static::creating(function ($applicant) {
            // Pastikan PPDB Period sudah ada
            $year = $applicant->ppdbPeriod->year ?? now()->year;

            $last = Applicant::whereHas('ppdbPeriod', fn($q) => $q->where('year', $year))
                ->whereNotNull('register_id')
                ->orderBy('register_id', 'desc')
                ->first();

            $lastNumber = 0;
            if ($last && preg_match('/\d{4}$/', $last->register_id, $matches)) {
                $lastNumber = (int) $matches[0];
            }

            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            $applicant->register_id = "PPDB-$year-$newNumber";
        });
    }

    public function graduationstatus()
    {

        return view('frontend::ppdb.graduation');
    }

    public function graduationForm($year)
    {
        $guardiation = GraduationYear::where('year', $year)->first();
        return view(
            'frontend::ppdb.graduation-check',
            compact('guardiation')
        );
    }

    public function graduationCheck(Request $request)
    {

        $request->validate([
            'nisn' => 'required',
            'password' => 'required',
        ]);

        $student = GraduationStudent::where('nisn', $request->nisn)->first();

        if (!$student) {
            return back()->withErrors(['nisn' => 'NISN tidak ditemukan.'])->withInput();
        }

        // Password = birth_date (format: Y-m-d)
        $birthDate = $student->birth_date ? date('d-m-Y', strtotime($student->birth_date)) : null;

        if ($birthDate !== $request->password) {
            return back()->withErrors(['password' => 'Password salah (format: dd-mm-yyyy).'])->withInput();
        }

        // Simpan tanggal pengecekan ke kolom accessed_at
        $student->accessed_at = now();
        $student->save();

        // Jika lolos, tampilkan halaman hasil kelulusan
        return view('frontend::ppdb.graduation-result', compact('student'));
    }

    public function graduationPdf(Request $request)
    {
        $request->validate([
            'nisn' => 'required',
            'password' => 'required',
        ]);

        $student = GraduationStudent::where('nisn', $request->nisn)->first();

        if (!$student) {
            return back()->withErrors(['nisn' => 'NISN tidak ditemukan.'])->withInput();
        }

        $birthDate = $student->birth_date ? date('d-m-Y', strtotime($student->birth_date)) : null;

        if ($birthDate !== $request->password) {
            return back()->withErrors(['password' => 'Password salah (format: dd-mm-yyyy).'])->withInput();
        }

        // Simpan tanggal pengecekan ke kolom accessed_at (opsional, atau hanya di check awal saja)
        $student->accessed_at = now();
        $student->save();

        // Buat PDF dari view yang sama atau view khusus untuk PDF
        $pdf = Pdf::loadView('frontend::ppdb.graduation-result-pdf', compact('student'));

        return $pdf->download('hasil-kelulusan-' . $student->nisn . '.pdf');
    }
}
