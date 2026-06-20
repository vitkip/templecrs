<?php

namespace App\Livewire\Personnel;

use App\Models\Personnel;
use Livewire\Component;

class PersonnelShow extends Component
{
    public Personnel $personnel;

    public function mount(int $id): void
    {
        abort_unless(auth()->check() && auth()->user()->canManagePersonnel(), 403);

        $this->personnel = Personnel::with('department')->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.personnel.show');
    }
}
