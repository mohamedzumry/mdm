<?php

namespace App\Exports;

use App\Models\MasterItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MasterItemsExport implements FromCollection, WithHeadings
{
    protected $search;
    protected $status;

    public function __construct($search = null, $status = null)
    {
        $this->search = $search;
        $this->status = $status;
    }

    public function collection()
    {
        return MasterItem::with(['brand', 'category'])
            ->when($this->search, function ($query) {
                $query->where('code', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->status !== '', function ($query) {
                $query->where('status', $this->status);
            })
            ->get()
            ->map(function ($item) {
                return [
                    'ID' => $item->id,
                    'Code' => $item->code,
                    'Name' => $item->name,
                    'Brand' => $item->brand?->name,
                    'Category' => $item->category?->name,
                    'Status' => $item->status,
                    'Created At' => $item->created_at->format('Y-m-d H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return ['ID', 'Code', 'Name', 'Brand', 'Category', 'Status', 'Created At'];
    }
}
