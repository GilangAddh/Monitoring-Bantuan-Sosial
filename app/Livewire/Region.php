<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Region extends Component
{
    public $provinces = [];
    public $regencies = [];
    public $districts = [];

    public $selectedProvince = null;
    public $selectedRegency = null;

    public function mount()
    {
        $this->fetchProvinces();
    }

    public function fetchProvinces()
    {
        $response = Http::get('https://wilayah.id/api/provinces.json');

        if ($response->successful()) {
            $this->provinces = $response->json();
        }
    }

    public function updatedSelectedProvince($value)
    {
        $this->fetchRegencies($value);
        $this->selectedRegency = null;
        $this->districts = [];
    }

    public function fetchRegencies($provinceCode)
    {
        $response = Http::get("https://wilayah.id/api/regencies/{$provinceCode}.json");

        if ($response->successful()) {
            $this->regencies = $response->json();
        }
    }

    public function updatedSelectedRegency($value)
    {
        $this->fetchDistricts($value);
    }

    public function fetchDistricts($regencyCode)
    {
        $response = Http::get("https://wilayah.id/api/districts/{$regencyCode}.json");

        if ($response->successful()) {
            $this->districts = $response->json();
        }
    }

    public function render()
    {
        return view('livewire.region');
    }
}
