<?php

namespace App\Livewire;

use App\Models\DaftarLaporan as ModelsDaftarLaporan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class DaftarLaporan extends Component
{
    use WithFileUploads;

    public $provinces = [];
    public $regencies = [];
    public $districts = [];

    public $selectedProvince = null;
    public $selectedRegency = null;
    public $selectedDistrict = null;
    public $nama_program = '';
    public $jumlah_penerima = null;
    public $provinsi = '';
    public $kabupaten = '';
    public $kecamatan = '';
    public $bukti_penyaluran = null;
    public $catatan = '';
    public $isModalOpen = false;
    public $modalAction = '';
    public $recordId = null;
    public $modalTitle = '';
    public $old_bukti_penyaluran = '';
    public $search = '';

    protected $rules = [
        'nama_program' => 'required',
        'jumlah_penerima' => 'required|integer|min:1',
        'selectedProvince' => 'required',
        'selectedRegency' => 'required',
        'selectedDistrict' => 'required',
        'catatan' => 'nullable',
    ];

    protected $messages = [
        'nama_program.required' => 'Nama program wajib diisi.',
        'jumlah_penerima.required' => 'Jumlah penerima wajib diisi.',
        'jumlah_penerima.integer' => 'Jumlah penerima harus berupa angka.',
        'jumlah_penerima.min' => 'Jumlah penerima minimal 1.',
        'selectedProvince.required' => 'Provinsi harus dipilih.',
        'selectedRegency.required' => 'Kabupaten/Kota harus dipilih.',
        'selectedDistrict.required' => 'Kecamatan harus dipilih.',
        'catatan.nullable' => 'Catatan bersifat opsional.',
    ];

    public function resetSearch()
    {
        $this->reset(['search']);
    }

    public function openModal($action, $recordId = null)
    {
        $this->resetModal();
        $this->modalAction = $action;
        $this->modalTitle = ucfirst($action) . ' Laporan Penyaluran Bantuan ';

        if (in_array($action, ['edit', 'lihat', 'hapus']) && $recordId) {
            $this->recordId = $recordId;
            $this->loadRecordData();
        }

        $this->isModalOpen = true;
    }
    public function resetModal()
    {
        $this->resetValidation();
        $this->reset(['isModalOpen', 'modalTitle', 'modalAction', 'recordId', 'nama_program', 'jumlah_penerima', 'selectedProvince', 'selectedRegency', 'selectedDistrict', 'bukti_penyaluran', 'catatan', 'old_bukti_penyaluran']);
    }

    public function fetchProvinces()
    {
        $response = Http::get('https://wilayah.id/api/provinces.json');

        if ($response->successful()) {
            $this->provinces = $response->json()['data'];
        }
    }

    public function updatedSelectedProvince($value)
    {
        $this->fetchRegencies($value);
        $this->provinsi = collect($this->provinces)->firstWhere('code', $value);
        $this->selectedRegency = null;
        $this->districts = [];
    }

    public function fetchRegencies($provinceCode)
    {
        $response = Http::get("https://wilayah.id/api/regencies/{$provinceCode}.json");

        if ($response->successful()) {
            $this->regencies = $response->json()['data'];
        }
    }

    public function updatedSelectedRegency($value)
    {
        $this->fetchDistricts($value);
        $this->kabupaten = collect($this->regencies)->firstWhere('code', $value);
    }
    public function fetchDistricts($regencyCode)
    {
        $response = Http::get("https://wilayah.id/api/districts/{$regencyCode}.json");

        if ($response->successful()) {
            $this->districts = $response->json()['data'];
        }
    }
    public function updatedSelectedDistrict($value)
    {
        $this->kecamatan = collect($this->districts)->firstWhere('code', $value);
    }
    private function loadRecordData()
    {
        $laporan = ModelsDaftarLaporan::findOrFail($this->recordId);
        $this->nama_program = $laporan->nama_program;
        $this->jumlah_penerima = $laporan->jumlah_penerima;
        $this->provinsi = $laporan->provinsi;
        $this->kabupaten = $laporan->kabupaten;
        $this->kecamatan = $laporan->kecamatan;
        $this->selectedProvince = $laporan->kode_provinsi;
        $this->selectedRegency = $laporan->kode_kabupaten;
        $this->selectedDistrict = $laporan->kode_kecamatan;
        $this->old_bukti_penyaluran = $laporan->bukti_penyaluran;
        $this->catatan = $laporan->catatan;
        $this->fetchRegencies($this->selectedProvince);
        $this->fetchDistricts($this->selectedRegency);
    }
    public function saveData()
    {

        if ($this->modalAction === 'edit') {
            $laporan = ModelsDaftarLaporan::findOrFail($this->recordId);

            if ($this->bukti_penyaluran) {
                $originalFileName = $this->bukti_penyaluran->getClientOriginalName();
                $newFileName = time() . '_' . $originalFileName;
                $this->bukti_penyaluran->storeAs('public/bukti_penyaluran', $newFileName);
            } else {
                $newFileName = $this->old_bukti_penyaluran;
            }

            $laporan->update([
                'nama_program' => $this->nama_program,
                'jumlah_penerima' => $this->jumlah_penerima,
                'provinsi' => $this->provinsi['name'] ?? $laporan->provinsi,
                'kabupaten' => $this->kabupaten['name'] ?? $laporan->kabupaten,
                'kecamatan' => $this->kecamatan['name'] ?? $laporan->kecamatan,
                'kode_provinsi' => $this->selectedProvince,
                'kode_kabupaten' => $this->selectedRegency,
                'kode_kecamatan' => $this->selectedDistrict,
                'bukti_penyaluran' => $newFileName,
                'catatan' => $this->catatan,
            ]);
        } else {
            $this->rules['bukti_penyaluran'] = 'required|file|mimes:jpeg,png,pdf|max:2048';
            $this->messages['bukti_penyaluran.required'] = 'Bukti penyaluran wajib diunggah.';
            $this->messages['bukti_penyaluran.file'] = 'Bukti penyaluran harus berupa file.';
            $this->messages['bukti_penyaluran.mimes'] = 'Bukti penyaluran harus berupa file dengan format: jpeg, png, pdf.';
            $this->messages['bukti_penyaluran.max'] = 'Ukuran file bukti penyaluran tidak boleh lebih dari 2MB.';

            $this->validate($this->rules);

            $originalFileName = $this->bukti_penyaluran->getClientOriginalName();
            $newFileName = time() . '_' . $originalFileName;
            $this->bukti_penyaluran->storeAs('public/bukti_penyaluran', $newFileName);

            ModelsDaftarLaporan::create([
                'nama_program' => $this->nama_program,
                'jumlah_penerima' => $this->jumlah_penerima,
                'provinsi' => $this->provinsi['name'] ?? null,
                'kabupaten' => $this->kabupaten['name'] ?? null,
                'kecamatan' => $this->kecamatan['name'] ?? null,
                'kode_provinsi' => $this->selectedProvince,
                'kode_kabupaten' => $this->selectedRegency,
                'kode_kecamatan' => $this->selectedDistrict,
                'bukti_penyaluran' => $newFileName,
                'catatan' => $this->catatan,
            ]);
        }
        $this->resetModal();
        $this->resetSearch();

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Data Laporan Bantuan berhasil disimpan."})');
    }
    public function delete()
    {
        $standar = ModelsDaftarLaporan::findOrFail($this->recordId);
        if ($standar->bukti_penyaluran && Storage::exists('public/bukti_penyaluran/' . $standar->bukti_penyaluran)) {
            Storage::delete('public/bukti_penyaluran/' . $standar->bukti_penyaluran);
        }
        $standar->delete();
        $this->resetModal();
        $this->resetSearch();

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Data Laporan Bantuan berhasil dihapus."})');
    }
    public function mount()
    {
        $this->fetchProvinces();
    }
    public function render()
    {
        $laporan = ModelsDaftarLaporan::where('provinsi', 'ilike', '%' . $this->search . '%')
            ->orWhere('kabupaten', 'ilike', '%' . $this->search . '%')
            ->orWhere('kecamatan', 'ilike', '%' . $this->search . '%')
            ->orWhere('nama_program', 'ilike', '%' . $this->search . '%')
            ->orderBy("id", "desc")->paginate(10);
        return view('livewire.daftar-laporan', ['laporan' => $laporan]);
    }
}
