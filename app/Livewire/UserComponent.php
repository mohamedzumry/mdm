<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class UserComponent extends Component
{
    use WithPagination, Toast;

    public $userId, $email, $name, $password;
    public $userModal = false, $confirmDeleteModal = false, $is_admin = false;

    protected function rules()
    {
        return [
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'name' => 'required',
            'password' => $this->userId ? 'nullable|min:6' : 'required|min:6',
        ];
    }
    public function render()
    {
        $users = User::paginate(5);
        return view('livewire.user-component', compact('users'));
    }

    public function openUserModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['userId', 'email', 'name', 'password', 'is_admin']);

        if ($id) {
            $user = User::findOrFail($id);
            $this->userId = $user->id;
            $this->email = $user->email;
            $this->name = $user->name;
            $this->is_admin = $user->is_admin;
        }
        $this->userModal = true;
    }

    public function closeUserModal()
    {
        $this->userModal = false;
    }

    public function saveUser()
    {
        $this->validate();

        $data = [
            'email' => $this->email,
            'name' => $this->name,
            'is_admin' => $this->is_admin,
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        if ($this->userId) {
            User::where('id', $this->userId)->update($data);
            $this->success('User updated successfully!');
        } else {
            User::create($data);
            $this->success('User created successfully!');
        }

        $this->closeUserModal();
    }

    public function editUser($id)
    {
        $this->openUserModal($id);
    }

    public function openDeleteConfirmationModal($id, $name)
    {
        $this->userId = $id;
        $this->name = $name;
        $this->confirmDeleteModal = true;
    }

    public function closeDeleteConfirmationModal()
    {
        $this->confirmDeleteModal = false;
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        $this->error(
            title: 'User deleted successfully.',
            icon: 'o-trash',
        );

        $this->confirmDeleteModal = false;
    }
}
