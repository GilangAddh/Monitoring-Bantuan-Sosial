<?php

namespace App\Livewire;

use App\Models\DaftarLaporan as ModelsDaftarLaporan;
use Illuminate\Support\Facades\Http;
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
    public $jumlah_penerima = 0;
    public $provinsi = '';
    public $kabupaten = '';
    public $kecamatan = '';
    public $bukti_penyaluran = null;
    public $catatan = '';
    public $isModalOpen = false;
    public $modalAction = '';
    public $recordId = null;
    public $modalTitle = '';
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
        $this->reset(['isModalOpen', 'modalTitle', 'modalAction', 'recordId', 'nama_program', 'jumlah_penerima', 'selectedProvince', 'selectedRegency', 'selectedDistrict', 'bukti_penyaluran', 'catatan']);
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
    public function saveData()
    {
        // $this->validate();

        if ($this->modalAction === 'edit') {
            $user = ModelsDaftarLaporan::findOrFail($this->recordId);
            $user->update($this->only(['nama_standar', 'nomer_dokumen', 'nomer_revisi', 'tanggal_terbit', 'is_active']));
        } else {
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

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Data standar berhasil disimpan."})');
    }
    public function mount()
    {
        $this->fetchProvinces();
    }
    public function render()
    {
        $laporan = ModelsDaftarLaporan::orderBy("id", "desc")->paginate(10);
        return view('livewire.daftar-laporan', ['laporan' => $laporan]);
    }
}
