<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Login - SI PUSKES')]
class Login extends Component
{
    // Properties untuk binding form
    public string $username = '';

    public string $password = '';

    public bool $remember = false;

    /**
     * Rules validasi untuk input login
     */
    protected function rules(): array
    {
        return [
            'username' => 'required|string|min:3',
            'password' => 'required|string|min:6',
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 3 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ];
    }

    /**
     * Method untuk handle proses login
     */
    public function login(): void
    {
        // Validasi input
        $this->validate();

        // Attempt login menggunakan username (bukan email)
        $credentials = [
            'username' => $this->username,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            // Regenerate session untuk keamanan (mencegah session fixation)
            session()->regenerate();

            // Optional: Flash message sukses
            session()->flash('success', 'Login berhasil! Selamat datang, '.Auth::user()->name);

            // Redirect ke dashboard
            $this->redirect('/dashboard', navigate: true);
        } else {
            // Jika login gagal, lempar error validation message
            $this->addError('username', 'Username atau password salah.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
