<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('ເຂົ້າສູ່ລະບົບ / Login')]
class LoginPage extends Component
{
    public string $email    = '';
    public string $password = '';
    public bool   $remember = false;

    public function login(): void
    {
        $this->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'ກະລຸນາໃສ່ອີເມວ',
            'email.email'       => 'ຮູບແບບອີເມວບໍ່ຖືກຕ້ອງ',
            'password.required' => 'ກະລຸນາໃສ່ລະຫັດຜ່ານ',
            'password.min'      => 'ລະຫັດຜ່ານຕ້ອງຢ່າງໜ້ອຍ 6 ຕົວ',
        ]);

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password, 'is_active' => true], $this->remember)) {
            $this->addError('email', 'ອີເມວ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ ຫຼື ບັນຊີຖືກລ້ອກ');
            return;
        }

        session()->regenerate();
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
