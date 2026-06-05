<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('ຈັດການຜູ້ໃຊ້ / User')]
class UserForm extends Component
{
    use WithFileUploads;

    public bool $editMode  = false;
    public ?int $userId    = null;

    public string  $name     = '';
    public string  $email    = '';
    public string  $password = '';
    public string  $password_confirmation = '';
    public string  $role     = 'staff';
    public string  $phone    = '';
    public bool    $is_active = true;
    public         $avatar   = null;
    public ?string $existing_avatar_url = null;

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->editMode = true;
            $this->userId   = $id;
            $this->load($id);
        }
    }

    private function load(int $id): void
    {
        $u = User::findOrFail($id);
        $this->name                = $u->name;
        $this->email               = $u->email;
        $this->role                = $u->role;
        $this->phone               = $u->phone ?? '';
        $this->is_active           = $u->is_active;
        $this->existing_avatar_url = $u->avatar_url;
    }

    protected function rules(): array
    {
        return [
            'name'      => 'required|string|max:100',
            'email'     => ['required', 'email', 'max:120', Rule::unique('users', 'email')->ignore($this->userId)],
            'password'  => $this->editMode ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',
            'role'      => 'required|in:super_admin,admin,manager,staff',
            'phone'     => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'avatar'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required'      => 'ກະລຸນາໃສ່ຊື່',
            'email.required'     => 'ກະລຸນາໃສ່ອີເມວ',
            'email.unique'       => 'ອີເມວນີ້ຖືກໃຊ້ແລ້ວ',
            'password.required'  => 'ກະລຸນາໃສ່ລະຫັດຜ່ານ',
            'password.min'       => 'ລະຫັດຜ່ານຕ້ອງຢ່າງໜ້ອຍ 8 ຕົວ',
            'password.confirmed' => 'ລະຫັດຜ່ານທັງສອງບໍ່ຕົງກັນ',
        ];
    }

    public function save(): void
    {
        $this->validate();

        // Guard: keep at least one super_admin active
        if ($this->editMode && $this->userId) {
            $old = User::findOrFail($this->userId);
            if ($old->isSuperAdmin() && $this->role !== 'super_admin' && !$this->is_active) {
                $remaining = User::where('role', 'super_admin')->where('is_active', true)
                                 ->where('id', '!=', $this->userId)->count();
                if ($remaining === 0) {
                    $this->addError('role', 'ຕ້ອງມີ Super Admin ໃຊ້ງານຢ່າງໜ້ອຍ 1 ຄົນ');
                    return;
                }
            }
        }

        // Avatar upload
        $avatarPath = $this->existing_avatar_url;
        if ($this->avatar) {
            if ($avatarPath) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = $this->avatar->store('users/avatars', 'public');
        }

        $data = [
            'name'       => $this->name,
            'email'      => $this->email,
            'role'       => $this->role,
            'phone'      => $this->phone ?: null,
            'is_active'  => $this->is_active,
            'avatar_url' => $avatarPath,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editMode) {
            User::findOrFail($this->userId)->update($data);
            session()->flash('message', 'ແກ້ໄຂຜູ້ໃຊ້ສຳເລັດ / User updated.');
        } else {
            User::create($data);
            session()->flash('message', 'ເພີ່ມຜູ້ໃຊ້ສຳເລັດ / User created.');
        }

        $this->redirect(route('users.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.users.form');
    }
}
