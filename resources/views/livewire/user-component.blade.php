<div class="p-4">
    <!-- Header -->
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Users</h2>
        <x-mary-button primary wire:click="openUserModal">+ Add User</x-mary-button>
    </div>

    @php
        $headers = [
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email`'],
            ['key' => 'is_admin', 'label' => 'Role'],
        ];

    @endphp

    <x-mary-table :headers="$headers" :rows="$users" with-pagination>
        @scope('actions', $user)
            <div class="flex gap-2">
                <x-mary-button icon="o-pencil-square" wire:click="editUser({{ $user->id }})" spinner class="btn-sm" />
                <x-mary-button icon="o-trash"
                    wire:click="openDeleteConfirmationModal({{ $user->id }}, '{{ $user->name }}')" spinner
                    class="btn-sm" />
            </div>
        @endscope
    </x-mary-table>

    <!-- User Modal -->
    @if ($userModal)
        <x-mary-modal wire:model="userModal" title="{{ $userId ? 'Edit User' : 'Add User' }}">

            <!-- Form inside modal -->
            <x-mary-form>

                <!-- Name -->
                <x-mary-input label="Name" wire:model.defer="name" required />

                <!-- Email -->
                <x-mary-input type="email" label="Email" wire:model.defer="email" required />

                <!-- Password -->
                @if ($userId == null)
                    <x-mary-password label="Password" hint="min 6 characters" wire:model.defer="password" clearable
                        required />
                @endif

                <!-- Role -->
                @php
                    $roleOptions = [['value' => 1, 'label' => 'Yes'], ['value' => 0, 'label' => 'No']];
                @endphp
                <x-mary-select label="Admin" :options="$roleOptions" option-value="value" option-label="label"
                    wire:model.defer="is_admin" />

            </x-mary-form>

            <!-- Modal Footer Actions -->
            <x-slot:actions name="footer">
                <x-mary-button label="Cancel" outline @click="$wire.userModal = false" />
                <x-mary-button label="{{ $userId ? 'Update' : 'Save' }}" wire:click="saveUser" primary />
            </x-slot:actions>
        </x-mary-modal>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($confirmDeleteModal)
        <x-mary-modal wire:model="confirmDeleteModal" title="Delete User" class="backdrop-blur">
            <p><strong></strong>Are you sure you want to delete user {{ $name }}?</strong></p>

            <x-slot:actions>
                <x-mary-button label="Cancel" @click="$wire.confirmDeleteModal = false" />
                <x-mary-button label="Delete" wire:click="deleteUser({{ $userId }})" />
            </x-slot:actions>
        </x-mary-modal>
    @endif
</div>
