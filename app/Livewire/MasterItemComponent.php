<?php

namespace App\Livewire;

use App\Exports\MasterItemsExport;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\MasterItem;
use App\Models\MasterBrand;
use App\Models\MasterCategory;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Mary\Traits\Toast;

class MasterItemComponent extends Component
{
    use WithPagination, WithFileUploads, Toast;

    public $itemId, $deleteItemId, $deleteItemCode, $code, $name, $brand_id, $category_id, $status = 'Active', $attachment, $existingAttachment;
    public $itemModal = false;
    public $confirmDeleteModal = false;

    public $search = '';
    public $statusFilter = '';
    public $brandFilter = '';
    public $categoryFilter = '';


    protected function rules()
    {
        return [
            'code' => 'required|unique:master_items,code,' . $this->itemId,
            'name' => 'required',
            'brand_id' => 'required|exists:master_brands,id',
            'category_id' => 'required|exists:master_categories,id',
            'attachment' => $this->itemId ? 'nullable|file|mimes:jpg,jpeg,png|max:2048' : 'required|file|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:Active,Inactive',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function export($type = 'csv')
    {
        $fileName = 'master_items_' . now()->format('Y-m-d') . '.' . $type;

        switch ($type) {
            case 'csv':
                return Excel::download(new MasterItemsExport($this->search, $this->status), $fileName, \Maatwebsite\Excel\Excel::CSV);
            case 'xlsx':
                return Excel::download(new MasterItemsExport($this->search, $this->status), $fileName, \Maatwebsite\Excel\Excel::XLSX);
            case 'pdf':
                return Excel::download(new MasterItemsExport($this->search, $this->status), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        }
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        // Only show active brands/categories that belong to the user (or all if admin)
        if (!auth()->user()->isAdmin()) {
            $brands = MasterBrand::where('status', 'Active')
                ->where('user_id', auth()->id())
                ->get();

            $categories = MasterCategory::where('status', 'Active')
                ->where('user_id', auth()->id())
                ->get();

            $items = MasterItem::query()
                ->where('user_id', auth()->id());
        } else {
            $brands = MasterBrand::where('status', 'Active')->get();
            $categories = MasterCategory::where('status', 'Active')->get();
            $items = MasterItem::query();
        }

        if ($this->search) {
            $items->where(function ($query) {
                $query->where('code', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $items->where('status', $this->statusFilter);
        }

        if ($this->brandFilter) {
            $items->where('brand_id', $this->brandFilter);
        }

        if ($this->categoryFilter) {
            $items->where('category_id', $this->categoryFilter);
        }

        $items = $items->paginate(5);

        return view('livewire.master-item-component', compact('items', 'brands', 'categories'));
    }


    public function openItemModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['itemId', 'code', 'name', 'brand_id', 'category_id', 'status', 'attachment']);

        if ($id) {
            $item = MasterItem::findOrFail($id);
            $this->itemId = $item->id;
            $this->code = $item->code;
            $this->name = $item->name;
            $this->brand_id = $item->brand_id;
            $this->category_id = $item->category_id;
            $this->status = $item->status;
            $this->existingAttachment = $item->attachment;
        }

        $this->itemModal = true;
    }

    public function closeItemModal()
    {
        $this->itemModal = false;
    }

    public function saveItem()
    {
        $validatedData = $this->validate();

        if ($this->attachment) {
            $validatedData['attachment'] = $this->attachment->store(path: "images/items");
        }

        if ($this->attachment instanceof TemporaryUploadedFile) {
            $filename = $this->attachment->storePublicly(path: "images/items");
        } else {
            $filename = $this->existingAttachment;
        }


        MasterItem::updateOrCreate(
            ['id' => $this->itemId],
            [
                'user_id' => auth()->id(),
                'code' => $validatedData['code'],
                'name' => $validatedData['name'],
                'brand_id' => $validatedData['brand_id'],
                'category_id' => $validatedData['category_id'],
                'status' => $validatedData['status'],
                'attachment' => $filename,
            ]
        );

        $this->itemId ? $this->success('Item updated successfully.') : $this->success('Item created successfully.');
        $this->closeItemModal();
    }

    public function editItem($id)
    {
        $this->openItemModal($id);
    }

    public function openDeleteConfirmationModal($id, $code)
    {
        $this->resetValidation();
        $this->reset(['deleteItemId', 'deleteItemCode']);
        $this->deleteItemId = $id;
        $this->deleteItemCode = $code;
        $this->confirmDeleteModal = true;
    }

    public function closeDeleteConfirmationModal()
    {
        $this->confirmDeleteModal = false;
    }

    public function deleteItem($id)
    {
        $item = MasterItem::findOrFail($id);
        if ($item->attachment) {
            Storage::disk('public')->delete($item->attachment);
        }
        $item->delete();
        $this->error(
            title: 'Item deleted successfully.',
            icon: 'o-trash',
        );
        $this->confirmDeleteModal = false;
    }
}
