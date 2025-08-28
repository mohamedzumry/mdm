<?php

namespace App\Livewire;

use App\Models\MasterCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class MasterCategoryComponent extends Component
{
    use WithPagination, Toast;

    public $categoryId, $deleteCategoryId, $deleteCategoryCode, $code, $name, $status = 'Active';
    public $modalCategory = false, $confirmDeleteModal = false;

    protected function rules()
    {
        return [
            'code'   => 'required|string|max:50|unique:master_categories,code,' . $this->categoryId,
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ];
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
         if (!auth()->user()->isAdmin()) {
            $categories = MasterCategory::where('user_id', auth()->id())->paginate(5);
        } else {
            $categories = MasterCategory::paginate(5);
        }
        return view('livewire.master-category-component', compact('categories'));
    }

    public function openCategoryModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['code', 'name', 'status', 'categoryId']);

        if ($id) {
            $category = MasterCategory::findOrFail($id);
            $this->categoryId = $category->id;
            $this->code = $category->code;
            $this->name = $category->name;
            $this->status = $category->status;
        }
        $this->modalCategory = true;
    }

    public function closeCategoryModal()
    {
        $this->modalCategory = false;
    }

    public function saveCategory()
    {
        $this->validate();

        MasterCategory::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'user_id' => auth()->id(),
                'code' => $this->code,
                'name' => $this->name,
                'status' => $this->status
            ]
        );

        // Toasts Message
        $this->categoryId ? $this->success('Category updated successfully.') : $this->success('Category created successfully.');

        $this->closeCategoryModal();
    }

    public function editCategory($id)
    {
        $this->openCategoryModal($id);
    }

    public function openDeleteConfirmationModal($id, $code)
    {
        $this->resetValidation();
        $this->reset(['deleteCategoryId', 'deleteCategoryCode']);
        $this->deleteCategoryCode = $code;
        $this->deleteCategoryId = $id;

        $this->confirmDeleteModal = true;
    }

    public function closeDeleteConfirmationModal()
    {
        $this->confirmDeleteModal = false;
    }

    public function deleteCategory($id)
    {
        MasterCategory::findOrFail($id)->delete();
        $this->error(
            title: 'Category deleted successfully.',
            icon: 'o-trash',
        );
        $this->confirmDeleteModal = false;
    }
}
