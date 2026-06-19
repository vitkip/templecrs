<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('ໂປຣໄຟລຂອງຂ້ອຍ / My Profile')]
class ProfilePage extends Component
{
    use WithFileUploads;

    public string  $name     = '';
    public string  $email    = '';
    public string  $phone    = '';
    public         $avatar   = null;
    public ?string $existing_avatar_url = null;

    public string $current_password       = '';
    public string $new_password           = '';
    public string $new_password_confirmation = '';

    public bool $passwordUpdated = false;
    public bool $profileUpdated  = false;

    public function mount(): void
    {
        $user = auth()->user();
        $this->name                = $user->name;
        $this->email               = $user->email;
        $this->phone               = $user->phone ?? '';
        $this->existing_avatar_url = $user->avatar_url;
    }

    protected function profileRules(): array
    {
        return [
            'name'   => 'required|string|max:100',
            'email'  => ['required', 'email', 'max:120', Rule::unique('users', 'email')->ignore(auth()->id())],
            'phone'  => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    protected function passwordRules(): array
    {
        return [
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ];
    }

    public function saveProfile(): void
    {
        $this->validate($this->profileRules(), [
            'name.required'  => 'ກະລຸນາໃສ່ຊື່',
            'email.required' => 'ກະລຸນາໃສ່ອີເມວ',
            'email.unique'   => 'ອີເມວນີ້ຖືກໃຊ້ແລ້ວ',
        ]);

        $user = auth()->user();

        $avatarPath = $this->existing_avatar_url;
        if ($this->avatar) {
            if ($avatarPath) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = $this->avatar->store('users/avatars', 'public');
        }

        $user->update([
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone ?: null,
            'avatar_url' => $avatarPath,
        ]);

        $this->existing_avatar_url = $user->fresh()->avatar_url;
        $this->avatar              = null;
        $this->profileUpdated      = true;
        $this->passwordUpdated     = false;
    }

    public function savePassword(): void
    {
        $this->validate($this->passwordRules(), [
            'current_password.required' => 'ກະລຸນາໃສ່ລະຫັດຜ່ານປັດຈຸບັນ',
            'new_password.required'     => 'ກະລຸນາໃສ່ລະຫັດຜ່ານໃໝ່',
            'new_password.min'          => 'ລະຫັດຜ່ານຕ້ອງຢ່າງໜ້ອຍ 8 ຕົວ',
            'new_password.confirmed'    => 'ລະຫັດຜ່ານທັງສອງບໍ່ຕົງກັນ',
        ]);

        $user = auth()->user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'ລະຫັດຜ່ານປັດຈຸບັນບໍ່ຖືກຕ້ອງ');
            return;
        }

        $user->update(['password' => Hash::make($this->new_password)]);

        $this->current_password          = '';
        $this->new_password              = '';
        $this->new_password_confirmation = '';
        $this->passwordUpdated           = true;
        $this->profileUpdated            = false;
    }

    public function render()
    {
        return view('livewire.profile.page');
    }
}
