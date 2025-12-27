<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';

    public $name, $email, $password, $newQuotaGb = 1; 
    public $editingUserId = null;
    public $showModal = false;
    public $is_admin = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'newQuotaGb' => 'required|numeric|min:1',
        'is_admin' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editQuota($userId)
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $userId;
        $this->newQuotaGb = $user->total_capacity / (1024 * 1024 * 1024);
    }

    public function updateQuota()
    {
        $user = User::findOrFail($this->editingUserId);
        
        $user->total_capacity = $this->newQuotaGb * 1024 * 1024 * 1024;
        $user->save();

        $this->editingUserId = null;
        session()->flash('message', 'Kuota berhasil diperbarui.');
    }

    public function openCreateModal()
    {
        $this->reset(['name', 'email', 'password', 'editingUserId', 'is_admin']);
        $this->newQuotaGb = 1;
        $this->showModal = true;
    }

    public function createUser()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'total_capacity' => $this->newQuotaGb * 1024 * 1024 * 1024,
            'used_capacity' => 0,
            'is_admin' => $this->is_admin,
        ]);

        $this->closeModal();
        session()->flash('message', 'User berhasil ditambahkan.');
    }

    public function editUser($userId)
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $userId;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_admin = $user->is_admin;
        $this->newQuotaGb = $user->total_capacity / (1024 * 1024 * 1024);
        $this->showModal = true;
    }

    public function updateUser()
    {
        $user = User::findOrFail($this->editingUserId);
        
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
            'total_capacity' => $this->newQuotaGb * 1024 * 1024 * 1024,
        ]);

        if ($this->password) {
            $user->update(['password' => Hash::make($this->password)]);
        }

        $this->closeModal();
        session()->flash('message', 'Data user berhasil diperbarui.');
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
            return;
        }

        
        $userDirectory = "uploads/user_{$user->id}";
        
        
        Storage::disk('private')->deleteDirectory($userDirectory);

       
        $user->delete();

        session()->flash('message', 'User dan seluruh filenya berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users
        ])->layout('layouts.app');
    }
}
