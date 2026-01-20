<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Kelola Pengguna - SI PUSKES')]
class Users extends Component
{
    use WithPagination;

    // Properties untuk Form Input
    public $nama_lengkap = '';

    public $username = '';

    public $password = '';

    public $role = '';

    // Properties untuk State Management
    public $userId = null;

    public $isEditMode = false;

    public $showModal = false;

    // Search & Filter
    public $search = '';

    public $perPage = 10;

    // Role Options (sesuai dengan class diagram)
    public $roleOptions = [
        'admin' => 'Admin',
        'pendaftaran' => 'Pendaftaran',
        'dokter' => 'Dokter',
        'apoteker' => 'Apoteker',
        'kepala Puskesmas' => 'Kepala Puskesmas',
    ];

    // Validation Messages
    protected $messages = [
        'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
        'nama_lengkap.min' => 'Nama lengkap minimal 3 karakter.',
        'username.required' => 'Username wajib diisi.',
        'username.min' => 'Username minimal 3 karakter.',
        'username.unique' => 'Username sudah digunakan.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password minimal 6 karakter.',
        'role.required' => 'Role wajib dipilih.',
        'role.in' => 'Role yang dipilih tidak valid.',
    ];

    /**
     * Reset pagination ketika search berubah
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Render Component dengan Data Users
     */
    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('nama_lengkap', 'like', '%'.$this->search.'%')
                    ->orWhere('username', 'like', '%'.$this->search.'%')
                    ->orWhere('role', 'like', '%'.$this->search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.users', [
            'users' => $users,
        ]);
    }

    /**
     * Open Modal untuk Create User Baru
     */
    public function create()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    /**
     * Open Modal untuk Edit User
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->nama_lengkap = $user->nama_lengkap;
        $this->username = $user->username;
        $this->role = $user->role;
        $this->password = ''; // Kosongkan password saat edit

        $this->isEditMode = true;
        $this->showModal = true;
    }

    /**
     * Store New User atau Update Existing User
     */
    public function store()
    {
        // Validation Rules
        $rules = [
            'nama_lengkap' => 'required|min:3',
            'username' => 'required|min:3|unique:users,username'.($this->isEditMode ? ','.$this->userId : ''),
            'role' => 'required|in:admin,pendaftaran,dokter,apoteker,kepala Puskesmas',
        ];

        // Password validation berbeda untuk Create vs Edit
        if ($this->isEditMode) {
            // Saat Edit: Password optional, tapi jika diisi minimal 6 karakter
            if (! empty($this->password)) {
                $rules['password'] = 'min:6';
            }
        } else {
            // Saat Create: Password wajib diisi dan minimal 6 karakter
            $rules['password'] = 'required|min:6';
        }

        // Validate Input
        $validated = $this->validate($rules, $this->messages);

        try {
            if ($this->isEditMode) {
                // Update Existing User
                $user = User::findOrFail($this->userId);

                $user->nama_lengkap = $this->nama_lengkap;
                $user->username = $this->username;
                $user->role = $this->role;

                // Update password hanya jika diisi
                if (! empty($this->password)) {
                    $user->password = Hash::make($this->password);
                }

                $user->save();

                session()->flash('success', 'User berhasil diupdate.');
            } else {
                // Create New User
                User::create([
                    'nama_lengkap' => $this->nama_lengkap,
                    'username' => $this->username,
                    'password' => Hash::make($this->password), // Hash password
                    'role' => $this->role,
                ]);

                session()->flash('success', 'User baru berhasil ditambahkan.');
            }

            // Reset Form & Close Modal
            $this->resetForm();
            $this->showModal = false;

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Delete User dengan ID tertentu
     */
    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);

            // Cek agar tidak menghapus diri sendiri
            if ($user->id === auth()->id()) {
                session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');

                return;
            }

            $user->delete();
            session()->flash('success', 'User berhasil dihapus.');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Close Modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Reset Form Properties
     */
    private function resetForm()
    {
        $this->userId = null;
        $this->nama_lengkap = '';
        $this->username = '';
        $this->password = '';
        $this->role = '';
        $this->isEditMode = false;
        $this->resetValidation();
    }
}
