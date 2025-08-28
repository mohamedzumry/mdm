<div class="p-4">

    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Master Items</h2>
        <x-mary-button primary wire:click="openItemModal">+ Add Item</x-mary-button>
    </div>

    <div class="flex flex-col md:flex-row gap-2 justify-between mb-4">
        <div class="flex gap-2">
            <input type="text" wire:model.live="search" placeholder="Search code or name..."
                class="input input-bordered w-64" />

            <select class="select select-bordered" wire:model="statusFilter" placeholder="Filter by status">
                <option value="">All Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>

            <select class="select select-bordered" wire:model="brandFilter" placeholder="Filter by brand">
                <option value="">All Brands</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>

            <select class="select select-bordered" wire:model="categoryFilter" placeholder="Filter by category">
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <button wire:click="export('csv')" class="btn btn-sm btn-outline">Export CSV</button>
            <button wire:click="export('xlsx')" class="btn btn-sm btn-outline">Export Excel</button>
            <button wire:click="export('pdf')" class="btn btn-sm btn-outline">Export PDF</button>
        </div>
    </div>


    @php
        $headers = [
            ['key' => 'code', 'label' => 'Code'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'brand.name', 'label' => 'Brand'],
            ['key' => 'category.name', 'label' => 'Category'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'attachment', 'label' => 'Attachment'],
        ];

    @endphp

    <x-mary-table :headers="$headers" :rows="$items" with-pagination>
        {{-- Image preview --}}
        @scope('attachment', $item)
            @if ($item->attachment)
                <img src="{{ asset('storage/' . $item->attachment) }}" alt="Preview"
                    class="h-12 w-12 object-cover rounded" />
            @else
                <span class="text-gray-400 italic">No Image</span>
            @endif
        @endscope
        @scope('actions', $item)
            <div class="flex gap-2">
                <x-mary-button icon="o-pencil-square" wire:click="editItem({{ $item->id }})" spinner class="btn-sm" />
                <x-mary-button icon="o-trash"
                    wire:click="openDeleteConfirmationModal({{ $item->id }}, '{{ $item->code }}')" spinner
                    class="btn-sm" />
            </div>
        @endscope
    </x-mary-table>

    @php
        $statusOptions = [['value' => 'Active', 'label' => 'Active'], ['value' => 'Inactive', 'label' => 'Inactive']];
    @endphp

    <!-- Modal -->
    @if ($itemModal)
        <x-mary-modal wire:model="itemModal" title="{{ $itemId ? 'Edit Item' : 'Add Item' }}">
            <x-mary-form>
                <x-mary-input label="Code" wire:model.defer="code" required />
                <x-mary-input label="Name" wire:model.defer="name" required />
                <x-mary-choices-offline label="Brand" wire:model.defer="brand_id" :options="$brands" option-label="name"
                    placeholder="Search ..." single clearable searchable required />
                {{-- <x-mary-select label="Brand" :options="$brands" option-value="id" option-label="name"
                    wire:model.defer="brand_id" required /> --}}
                <x-mary-choices-offline label="Category" wire:model.defer="category_id" :options="$categories"
                    option-label="name" placeholder="Search ..." single clearable searchable required />
                {{-- <x-mary-select label="Category" :options="$categories" option-value="id" option-label="name"
                    wire:model.defer="category_id" required /> --}}
                <x-mary-select label="Status" :options="$statusOptions" option-value="value" option-label="label"
                    wire:model.defer="status" required />
                <x-mary-file wire:model.defer="attachment" label="Image" hint="Only PNG, JPG, JPEG"
                    accept="image/png, image/jpeg, image/jpg" required />
                {{-- <div>
                    <label class="mary-label">Attachment</label>
                    <input type="file" wire:model="attachment" class="mary-input w-full">
                    @error('attachment')
                        <span class="mary-error">{{ $message }}</span>
                    @enderror

                    @if ($attachment && is_object($attachment))
                        <div class="mt-2 text-sm">Selected file: {{ $attachment->getClientOriginalName() }}</div>
                    @endif
                </div> --}}
            </x-mary-form>

            <x-slot:actions name="footer">
                <x-mary-button label="Cancel" outline @click="$wire.itemModal = false" />
                <x-mary-button label="{{ $itemId ? 'Update' : 'Save' }}" wire:click="saveItem" primary />
            </x-slot:actions>
        </x-mary-modal>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($confirmDeleteModal)
        <x-mary-modal wire:model="confirmDeleteModal" title="Confirm Deletion">
            <p>Are you sure you want to delete item <strong>{{ $deleteItemCode }}</strong>?</p>
            <x-slot:actions>
                <x-mary-button label="Cancel" @click="$wire.confirmDeleteModal = false" />
                <x-mary-button label="Delete" wire:click="deleteItem({{ $deleteItemId }})" danger />
            </x-slot:actions>
        </x-mary-modal>
    @endif
</div>
