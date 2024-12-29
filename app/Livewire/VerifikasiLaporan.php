<?php

namespace App\Livewire;

use App\Models\DaftarLaporan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class VerifikasiLaporan extends Component
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
    public $status = '';
    public $alasan = '';

    protected $rules = [
        'status' => 'required',
    ];

    protected $messages = [
        'status.required' => 'Status Verifikasi wajib diisi.',
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

        $this->recordId = $recordId;
        $this->loadRecordData();

        $this->isModalOpen = true;
    }
    public function resetModal()
    {
        $this->resetValidation();
        $this->reset(['isModalOpen', 'modalTitle', 'modalAction', 'recordId', 'nama_program', 'jumlah_penerima', 'selectedProvince', 'selectedRegency', 'selectedDistrict', 'bukti_penyaluran', 'catatan', 'old_bukti_penyaluran', 'status', 'alasan']);
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
        $laporan = DaftarLaporan::findOrFail($this->recordId);
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
        $laporan = DaftarLaporan::findOrFail($this->recordId);
        if ($this->status == '2') {
            $this->validate();
            $laporan->update([
                'status' => $this->status,
                'alasan' => $this->alasan,
            ]);
        } else {
            $this->rules['alasan'] = 'required';
            $this->messages['alasan.required'] = 'Alasan Penolakan wajib diisi.';
            $this->validate();

            $laporan->update([
                'status' => $this->status,
                'alasan' => $this->alasan,
            ]);
        }

        $this->resetModal();
        $this->resetSearch();

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Data Laporan Bantuan berhasil diverifikasi."})');
    }

    public function mount()
    {
        $this->fetchProvinces();
    }
    public function render()
    {
        $laporan = DaftarLaporan::where(function ($query) {
            $query->where('provinsi', 'ilike', '%' . $this->search . '%')
                ->orWhere('kabupaten', 'ilike', '%' . $this->search . '%')
                ->orWhere('kecamatan', 'ilike', '%' . $this->search . '%')
                ->orWhere('nama_program', 'ilike', '%' . $this->search . '%');
        })
            ->orderBy('status', 'asc') // Urutkan berdasarkan status terkecil
            ->orderBy('id', 'desc')   // Urutkan berdasarkan ID menurun setelah status
            ->paginate(10);

        return view('livewire.verifikasi-laporan', ['laporan' => $laporan]);
    }
}
