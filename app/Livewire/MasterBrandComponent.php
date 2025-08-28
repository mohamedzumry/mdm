<?php

namespace App\Livewire;

use App\Models\MasterBrand;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class MasterBrandComponent extends Component
{

    use WithPagination, Toast;

    public $brandId, $deleteBrandId, $deleteBrandCode, $code, $name, $status = 'Active';
    public $modalBrand = false, $confirmDeleteModal = false;

    protected function rules()
    {
        return [
            'code'   => 'required|string|max:50|unique:master_brands,code,' . $this->brandId,
            'name'   => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ];
    }


    #[Layout('components.layouts.app')]
    public function render()
    {
        if (!auth()->user()->isAdmin()) {
            $brands = MasterBrand::where('user_id', auth()->id())->paginate(5);
        } else {
            $brands = MasterBrand::paginate(5);
        }

        return view('livewire.master-brand-component', compact('brands'));
    }

    public function openBrandModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['code', 'name', 'status', 'brandId']);

        if ($id) {
            $brand = MasterBrand::findOrFail($id);
            $this->brandId = $brand->id;
            $this->code = $brand->code;
            $this->name = $brand->name;
            $this->status = $brand->status;
        }
        $this->modalBrand = true;
    }

    public function closeBrandModal()
    {
        $this->modalBrand = false;
    }

    public function saveBrand()
    {
        $this->validate();

        MasterBrand::updateOrCreate(
            ['id' => $this->brandId],
            [
                'user_id' => auth()->id(),
                'code' => $this->code,
                'name' => $this->name,
                'status' => $this->status
            ]
        );

        // Toasts Message
        $this->brandId ? $this->success('Brand updated successfully.') : $this->success('Brand created successfully.');

        $this->closeBrandModal();
    }

    public function editBrand($id)
    {
        $this->openBrandModal($id);
    }

    public function openDeleteConfirmationModal($id, $code)
    {
        $this->resetValidation();
        $this->reset(['deleteBrandId', 'deleteBrandCode']);
        $this->deleteBrandCode = $code;
        $this->deleteBrandId = $id;

        $this->confirmDeleteModal = true;
    }

    public function closeDeleteConfirmationModal()
    {
        $this->confirmDeleteModal = false;
    }

    public function deleteBrand($id)
    {
        MasterBrand::findOrFail($id)->delete();
        $this->error(
            title: 'Brand deleted successfully.',
            icon: 'o-trash',
        );
        $this->confirmDeleteModal = false;
    }
}
