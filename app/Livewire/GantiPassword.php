<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Ganti Password - SI PUSKES')]
class GantiPassword extends Component
{
    public $password_lama = '';

    public $password_baru = '';

    public $password_konfirmasi = '';

    /**
     * Validation messages
     */
    protected $messages = [
        'password_lama.required' => 'Password lama wajib diisi.',
        'password_baru.required' => 'Password baru wajib diisi.',
        'password_baru.min' => 'Password baru minimal 6 karakter.',
        'password_konfirmasi.required' => 'Konfirmasi password wajib diisi.',
        'password_konfirmasi.same' => 'Konfirmasi password tidak sama dengan password baru.',
    ];

    /**
     * Simpan password baru
     */
    public function simpanPassword()
    {
        // Validasi input
        $this->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6',
            'password_konfirmasi' => 'required|same:password_baru',
        ], $this->messages);

        // Verifikasi password lama
        if (! Hash::check($this->password_lama, Auth::user()->password)) {
            $this->addError('password_lama', 'Password lama tidak sesuai.');

            return;
        }

        try {
            // Update password
            Auth::user()->update([
                'password' => Hash::make($this->password_baru),
            ]);

            // Reset form
            $this->reset(['password_lama', 'password_baru', 'password_konfirmasi']);

            // Flash success message
            session()->flash('success', 'Password berhasil diubah!');

            // Redirect ke dashboard
            return $this->redirect(route('dashboard'), navigate: true);

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.ganti-password');
    }
}
