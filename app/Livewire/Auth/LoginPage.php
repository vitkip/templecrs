<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
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

        $key = 'login:' . Str::lower($this->email) . ':' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('email', "ພະຍາຍາມຫຼາຍເກີນໄປ — ລອງໃໝ່ໃນອີກ {$seconds} ວິນາທີ");
            return;
        }

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password, 'is_active' => true], $this->remember)) {
            RateLimiter::hit($key, 300);
            $remaining = 5 - RateLimiter::attempts($key);
            $this->addError('email', "ອີເມວ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ" . ($remaining > 0 ? " (ເຫຼືອ {$remaining} ຄັ້ງ)" : ''));
            return;
        }

        RateLimiter::clear($key);
        session()->regenerate();
        $this->redirect(route('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
