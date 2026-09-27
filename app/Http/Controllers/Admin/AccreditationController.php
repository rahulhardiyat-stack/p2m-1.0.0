<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ResearchServicesExport;
use App\Exports\ResearchServiceTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\ResearchServicesImport;
use App\Models\Accreditation;
use App\Models\Faculty;
use App\Models\ResearchService;
use App\Traits\HasMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class AccreditationController extends Controller
{
    use HasMedia;

    public function index(Request $request)
    {

        $query = Accreditation::with('faculty');

        if($request->filled('search')) {
            $search = $request->search;
            $query->when($search, fn($q) => $q->where('title', 'like', "%{$search}%")
                ->orWhere('accreditations.sk_number', 'like', "%{$search}%"))
                ->when($request->type, fn($q) => $q->where('type', $request->type));
        }

        if($request->filled('type')){
            $query->where('type', $request->type);
        }

        if ($request->filled('validity')) {
            match ($request->validity) {
                'active' => $query->active(),
                'expired' => $query->expired(),
                'expiring_soon' => $query->expiringSoon(180),
                default => null,
            };
        }





        $data = $query->orderBy('expired_date', 'desc')->latest()->paginate(15)->withQueryString();

        return view('admin.accreditations.index', compact('data'));
    }

    public function create()
    {
        $data = Accreditation::with('faculty')
            ->orderBy('expired_date', 'desc')->latest()->paginate(15)->withQueryString();
        $faculties = Faculty::all();


        return view('admin.accreditations.create', compact('data', 'faculties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'degree' => 'required',
            'status' => 'required',
            'type' => 'required',
            'issued_date' => 'required',
            'expired_date' => 'required',
            'sk_number' => 'required|nullable|string',
            'faculty_id'   => 'required|exists:faculties,id',
            'document_path' => 'nullable|file|mimes:pdf|max:30720',
        ], [
            'title.required' => 'Title is required.',
            'degree.required' => 'degree is required.',
            'status.required' => 'status is required.',
            'type.required' => 'type is required.',
            'sk_number.required' => 'sk_number is required.',
            'issued_date.required' => 'issued_date is required.',
            'expired_date.required' => 'expired_date is required.',
            'faculty_id.required' => 'Fakultas wajib dipilih.',
            'faculty_id.exists'   => 'Fakultas yang dipilih tidak valid/tidak ditemukan.'
        ]);

        if ($request->hasFile('document_path')) {
            $validated['document_path'] = $request->file('document_path')->store('accreditations_files', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        Accreditation::create($validated);

        return redirect()->route('admin.accreditations.index')->with('success', 'Data Akreditasi berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        $accreditation = Accreditation::findOrFail($id);
        return view('admin.accreditations.edit', compact('accreditation'));
    }

    public function update(Request $request, string $id)
    {

        $accreditation = Accreditation::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'degree' => 'required',
            'status' => 'required',
            'type' => 'required',
            'issued_date' => 'required',
            'expired_date' => 'required',
            'sk_number' => 'nullable|string',
            'document_path' => 'nullable|file|mimes:pdf|max:30720',
        ], [
                'title.required' => 'Title is required.',
                'degree.required' => 'degree is required.',
                'status.required' => 'status is required.',
                'type.required' => 'type is required.',
                'sk_number.required' => 'sk_number is required.',
                'issued_date.required' => 'issued_date is required.',
                'expired_date.required' => 'expired_date is required.',
        ]);

        if ($request->hasFile('document_path')) {
            if ($accreditation->document_path) {
                Storage::disk('public')->delete($accreditation->document_path);
            }
            $validated['document_path'] = $request->file('document_path')
                ->store('accreditations_files', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $accreditation->update($validated);


        return redirect()->route('admin.accreditations.index')->with('success', 'Data akreditasi berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $acreditation = Accreditation::findOrFail($id);
        if ($acreditation->document_path) {
            Storage::disk('public')->delete($acreditation->document_path);
        }
        $acreditation->delete();

        return back()->with('success', 'Data berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            Excel::import(new ResearchServicesImport, $request->file('file'));
            return back()->with('success', 'Data penelitian & pengabdian berhasil diimpor!');
        }
        catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = 'Gagal mengimpor data. Cek baris berikut: ';
            foreach ($failures as $failure) {
                $errorMsg .= "Baris " . $failure->row() . ": " . implode(', ', $failure->errors()) . ". ";
            }
            return back()->with('error', $errorMsg);
        }
        catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new ResearchServiceTemplateExport, 'template_import_penelitian_pengabdian.xlsx');
    }

    public function export()
    {
        return Excel::download(new ResearchServicesExport, 'data_penelitian_pengabdian.xlsx');
    }
}
