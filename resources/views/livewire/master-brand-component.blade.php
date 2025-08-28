<div class="p-4">
    <!-- Header -->
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Master Brands</h2>
        <x-mary-button primary wire:click="openBrandModal">+ Add Brand</x-mary-button>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <x-mary-alert type="success" class="mb-4">
            {{ session('message') }}
        </x-mary-alert>
    @endif

    @php
        $headers = [
            ['key' => 'code', 'label' => 'Code'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'status', 'label' => 'Status'],
        ];

    @endphp

    <x-mary-table :headers="$headers" :rows="$brands" with-pagination>
        @scope('actions', $brand)
            <div class="flex gap-2">
                <x-mary-button icon="o-pencil-square" wire:click="editBrand({{ $brand->id }})" spinner class="btn-sm" />
                <x-mary-button icon="o-trash"
                    wire:click="openDeleteConfirmationModal({{ $brand->id }}, '{{ $brand->code }}')" spinner
                    class="btn-sm" />
            </div>
        @endscope
    </x-mary-table>

    {{-- <!-- Pagination -->
    <div class="mt-4">
        {{ $brands->links() }}
    </div> --}}

    <!-- Brand Modal -->
    @if ($modalBrand)
        <x-mary-modal wire:model="modalBrand" title="{{ $brandId ? 'Edit Brand' : 'Add Brand' }}">

            <!-- Form inside modal -->
            <x-mary-form>

                <!-- Code -->
                <x-mary-input label="Code" wire:model.defer="code" required />

                <!-- Name -->
                <x-mary-input label="Name" wire:model.defer="name" required />

                <!-- Status -->
                @php
                    $statusOptions = [
                        ['value' => 'Active', 'label' => 'Active'],
                        ['value' => 'Inactive', 'label' => 'Inactive'],
                    ];
                @endphp
                <x-mary-select label="Status" :options="$statusOptions" option-value="value" option-label="label"
                    wire:model="status" />

            </x-mary-form>

            <!-- Modal Footer Actions -->
            <x-slot:actions name="footer">
                <x-mary-button label="Cancel" outline @click="$wire.modalBrand = false" />
                <x-mary-button label="{{ $brandId ? 'Update' : 'Save' }}" wire:click="saveBrand" primary />
            </x-slot:actions>
        </x-mary-modal>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($confirmDeleteModal)
        <x-mary-modal wire:model="confirmDeleteModal" title="Delete Brand" class="backdrop-blur">
            <p><strong></strong>Are you sure you want to delete brand {{ $deleteBrandCode }}?</strong></p>

            <x-slot:actions>
                <x-mary-button label="Cancel" @click="$wire.confirmDeleteModal = false" />
                <x-mary-button label="Delete" wire:click="deleteBrand({{ $deleteBrandId }})" />
            </x-slot:actions>
        </x-mary-modal>
    @endif
</div>
