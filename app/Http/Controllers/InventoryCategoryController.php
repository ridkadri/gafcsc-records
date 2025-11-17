<?php

namespace App\Http\Controllers;

use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryCategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = InventoryCategory::withCount('items')
                                     ->orderBy('name')
                                     ->get();
        
        return view('inventory.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('inventory.categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_categories',
            'code' => 'required|string|max:10|unique:inventory_categories',
            'description' => 'nullable|string',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'requires_approval' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Ensure code is uppercase
        $validated['code'] = strtoupper($validated['code']);
        
        // Set defaults for checkboxes
        $validated['requires_approval'] = $request->has('requires_approval');
        $validated['is_active'] = $request->has('is_active');

        $category = InventoryCategory::create($validated);

        return redirect()->route('inventory.categories.show', $category)
                        ->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified category.
     */
    public function show(InventoryCategory $category)
    {
        // Load category with items and their relationships
        $category->loadCount('items');

        // Get paginated items for this category
        $items = $category->items()
            ->with(['activeAssignments.staff'])
            ->paginate(10);

        // Calculate statistics based on your existing InventoryItem structure
        $stats = [
            'total_items' => $category->items()->count(),
            'available_items' => $category->items()->where('status', 'active')->count(),
            'checked_out_items' => $category->items()->whereHas('activeAssignments')->count(),
            'maintenance_items' => $category->items()->where('status', 'maintenance')->count(),
            'low_stock_items' => $category->items()
                ->whereRaw('available_quantity <= minimum_stock_level')
                ->where('minimum_stock_level', '>', 0)
                ->count(),
        ];

        // Get related categories (same first letter of code or similar names)
        $relatedCategories = InventoryCategory::where('id', '!=', $category->id)
            ->where('is_active', true)
            ->where(function ($query) use ($category) {
                $query->where('code', 'like', substr($category->code, 0, 1) . '%')
                    ->orWhere('name', 'like', '%' . explode(' ', $category->name)[0] . '%');
            })
            ->withCount('items')
            ->limit(5)
            ->get();
        
        return view('inventory.categories.show', compact('category', 'items', 'stats', 'relatedCategories'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(InventoryCategory $category)
    {
        // Load items count for display
        $category->loadCount('items');

        return view('inventory.categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, InventoryCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_categories,name,' . $category->id,
            'code' => 'required|string|max:10|unique:inventory_categories,code,' . $category->id,
            'description' => 'nullable|string',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'requires_approval' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Ensure code is uppercase
        $validated['code'] = strtoupper($validated['code']);
        
        // Set defaults for checkboxes
        $validated['requires_approval'] = $request->has('requires_approval');
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('inventory.categories.show', $category)
                        ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(InventoryCategory $category)
    {
        // Check if category has items
        if ($category->items()->exists()) {
            return redirect()->route('inventory.categories.show', $category)
                           ->with('error', 'Cannot delete category that has items assigned to it.');
        }

        $category->delete();

        return redirect()->route('inventory.categories.index')
                        ->with('success', 'Category deleted successfully!');
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus(InventoryCategory $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
                        ->with('success', "Category {$status} successfully!");
    }

    /**
     * Show items with low stock in this category.
     */
    public function lowStock(InventoryCategory $category)
    {
        $lowStockItems = $category->items()
            ->whereRaw('available_quantity <= minimum_stock_level')
            ->where('minimum_stock_level', '>', 0)
            ->with(['activeAssignments.staff'])
            ->paginate(15);

        return view('inventory.categories.low-stock', compact('category', 'lowStockItems'));
    }

    /**
     * Show all items in this category.
     */
    public function items(InventoryCategory $category)
    {
        $items = $category->items()
            ->with(['activeAssignments.staff'])
            ->paginate(20);

        return view('inventory.categories.items', compact('category', 'items'));
    }
}