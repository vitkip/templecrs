<?php

namespace App\Livewire\Personnel;

use App\Models\Personnel;
use Livewire\Component;

class PersonnelShow extends Component
{
    public Personnel $personnel;

    public function mount(int $id): void
    {
        $this->personnel = Personnel::with('department')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.personnel.show');
    }
}
