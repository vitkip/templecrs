<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('ຈັດການຜູ້ໃຊ້ / Users')]
class UserTable extends Component
{
    use WithPagination;

    #[Url] public string $search      = '';
    #[Url] public string $roleFilter  = '';
    #[Url] public string $statusFilter = '';

    public int    $perPage = 15;
    public string $sortBy  = 'created_at';
    public string $sortDir = 'desc';

    public function updatedSearch(): void    { $this->resetPage(); }
    public function updatedRoleFilter(): void  { $this->resetPage(); }
    public function updatedStatusFilter(): void { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->search = $this->roleFilter = $this->statusFilter = '';
        $this->resetPage();
    }

    public function sortBy(string $col): void
    {
        $this->sortDir = ($this->sortBy === $col && $this->sortDir === 'asc') ? 'desc' : 'asc';
        $this->sortBy  = $col;
    }

    public function toggleActive(int $id): void
    {
        $user = User::findOrFail($id);

        // Prevent locking self out
        if ($user->id === auth()->id()) {
            session()->flash('error', 'ບໍ່ສາມາດປິດໃຊ້ງານບັນຊີຕົນເອງ');
            return;
        }

        $user->update(['is_active' => !$user->is_active]);
    }

    public function deleteUser(int $id): void
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'ບໍ່ສາມາດລຶບບັນຊີຕົນເອງ');
            return;
        }

        // Keep at least one super_admin
        if ($user->isSuperAdmin() && User::where('role', 'super_admin')->where('is_active', true)->count() <= 1) {
            session()->flash('error', 'ຕ້ອງມີ Super Admin ຢ່າງໜ້ອຍ 1 ຄົນ');
            return;
        }

        $user->delete();
        session()->flash('message', 'ລຶບຜູ້ໃຊ້ສຳເລັດ / User deleted.');
    }

    public function render()
    {
        $users = User::query()
            ->search($this->search ?: null)
            ->when($this->roleFilter, fn ($q) => $q->where('role', $this->roleFilter))
            ->when($this->statusFilter === 'active',   fn ($q) => $q->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        $stats = [
            'total'       => User::count(),
            'active'      => User::where('is_active', true)->count(),
            'super_admin' => User::where('role', 'super_admin')->count(),
            'admin'       => User::where('role', 'admin')->count(),
            'manager'     => User::where('role', 'manager')->count(),
            'staff'       => User::where('role', 'staff')->count(),
        ];

        return view('livewire.users.table', compact('users', 'stats'));
    }
}
