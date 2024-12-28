<?php

namespace App\Livewire;

use App\Models\Navigation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Navbar extends Component
{
    public $menus;

    public function mount() {}

    public function render()
    {
        return view('livewire.navbar')->with([
            'profileName' => Auth::user()->name
        ]);
    }
}
