<div class="p-6 space-y-6">

    <!-- Welcome -->
    <h2 class="text-2xl font-bold">
        Welcome, {{ $user->name }}
    </h2>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @if ($user->isAdmin())
            <!-- Admin level stats -->
            <x-mary-card>
                <div class="font-bold">Total Brands (All)</div>
                <div>{{ $totalBrandsAdmin }}</div>
            </x-mary-card>

            <x-mary-card>
                <div class="font-bold">Total Categories (All)</div>
                <div>{{ $totalCategoriesAdmin }}</div>
            </x-mary-card>

            <x-mary-card>
                <div class="font-bold">Total Items (All)</div>
                <div>{{ $totalItemsAdmin }}</div>
            </x-mary-card>
        @endif

        <!-- User restricted stats -->
        <x-mary-card>
            <div class="font-bold">Your Brands</div>
            <div>{{ $totalBrandsUser }}</div>
        </x-mary-card>

        <x-mary-card>
            <div class="font-bold">Your Categories</div>
            <div>{{ $totalCategoriesUser }}</div>
        </x-mary-card>

        <x-mary-card>
            <div class="font-bold">Your Items</div>
            <div>{{ $totalItemsUser }}</div>
        </x-mary-card>
    </div>

    <!-- Recent Items Table -->
    <x-mary-card class="mt-6">
        <div class="font-bold">Recent Items</div>
        <div>
            @php
                $headers = [
                    ['key' => 'code', 'label' => 'Code'],
                    ['key' => 'name', 'label' => 'Name'],
                    ['key' => 'brand', 'label' => 'Brand'],
                    ['key' => 'category', 'label' => 'Category'],
                    ['key' => 'status', 'label' => 'Status'],
                ];
                $rows = collect($recentItems)->map(function ($item) {
                    return [
                        'code' => $item->code,
                        'name' => $item->name,
                        'brand' => $item->brand?->name,
                        'category' => $item->category?->name,
                        'status' => $item->status,
                    ];
                });
            @endphp
            <x-mary-table :headers="$headers" :rows="$rows" empty-message="No items found." />
        </div>
    </x-mary-card>
</div>