<?php

namespace App\Livewire;

use App\Models\DaftarLaporan;
use Livewire\Component;

class Dashboard extends Component
{
    public $total_laporan = 0;
    public $total_pkh = 0;
    public $total_blt = 0;
    public $total_bansos = 0;
    public $total_rtlh = 0;

    public $total_penerima = 0;
    public $penerima_pkh = 0;
    public $penerima_blt = 0;
    public $penerima_bansos = 0;
    public $penerima_rtlh = 0;
    public $chartData = [];

    public function mount()
    {
        $this->total_laporan = DaftarLaporan::count();
        $this->total_pkh = DaftarLaporan::where('nama_program', 'PKH')->count();
        $this->total_blt = DaftarLaporan::where('nama_program', 'BLT')->count();
        $this->total_bansos = DaftarLaporan::where('nama_program', 'Bansos')->count();
        $this->total_rtlh = DaftarLaporan::where('nama_program', 'RTLH')->count();

        $this->total_penerima = DaftarLaporan::sum('jumlah_penerima');
        $this->penerima_pkh = DaftarLaporan::where('nama_program', 'PKH')->sum('jumlah_penerima');
        $this->penerima_blt = DaftarLaporan::where('nama_program', 'BLT')->sum('jumlah_penerima');
        $this->penerima_bansos = DaftarLaporan::where('nama_program', 'Bansos')->sum('jumlah_penerima');
        $this->penerima_rtlh = DaftarLaporan::where('nama_program', 'RTLH')->sum('jumlah_penerima');
    }
    public function render()
    {
        return view('livewire.dashboard');
    }
}
