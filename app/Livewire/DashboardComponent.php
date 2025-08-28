<?php

namespace App\Livewire;

use Livewire\Component;

class DashboardComponent extends Component
{
    public function render()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $totalBrandsAdmin = \App\Models\MasterBrand::count();
            $totalCategoriesAdmin = \App\Models\MasterCategory::count();
            $totalItemsAdmin = \App\Models\MasterItem::count();

            $totalBrandsUser = \App\Models\MasterBrand::where('user_id', $user->id)->count();
            $totalCategoriesUser = \App\Models\MasterCategory::where('user_id', $user->id)->count();
            $totalItemsUser = \App\Models\MasterItem::where('user_id', $user->id)->count();

            $recentItems = \App\Models\MasterItem::latest()->take(5)->get();
        } else {
            $totalBrandsAdmin = null;
            $totalCategoriesAdmin = null;
            $totalItemsAdmin = null;

            $totalBrandsUser = \App\Models\MasterBrand::where('user_id', $user->id)->count();
            $totalCategoriesUser = \App\Models\MasterCategory::where('user_id', $user->id)->count();
            $totalItemsUser = \App\Models\MasterItem::where('user_id', $user->id)->count();

            $recentItems = \App\Models\MasterItem::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('livewire.dashboard-component', compact(
            'user',
            'totalBrandsAdmin',
            'totalCategoriesAdmin',
            'totalItemsAdmin',
            'totalBrandsUser',
            'totalCategoriesUser',
            'totalItemsUser',
            'recentItems'
        ));
    }
}
