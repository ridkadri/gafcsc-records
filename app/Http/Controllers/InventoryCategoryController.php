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
    // In your InventoryController.php create method:
    public function create(Request $request)
    {
        $categories = InventoryCategory::active()->orderBy('name')->get();
        $locations = InventoryItem::active()
                                ->whereNotNull('location')
                                ->distinct()
                                ->pluck('location')
                                ->sort();
        
        // Pre-select category if provided
        $selectedCategory = null;
        if ($request->has('category_id')) {
            $selectedCategory = InventoryCategory::find($request->category_id);
        }
        
        return view('inventory.create', compact('categories', 'locations', 'selectedCategory'));
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
    public function show(InventoryCategory $inventoryCategory)
    {
        // Load category with items and their relationships
        $inventoryCategory->loadCount('items');

        // Get paginated items for this category
        $items = $inventoryCategory->items()
            ->with(['activeAssignments.staff'])
            ->paginate(10);

        // Calculate statistics based on your existing InventoryItem structure
        $stats = [
            'total_items' => $inventoryCategory->items()->count(),
            'available_items' => $inventoryCategory->items()->where('status', 'active')->count(),
            'checked_out_items' => $inventoryCategory->items()->whereHas('activeAssignments')->count(),
            'maintenance_items' => $inventoryCategory->items()->where('status', 'maintenance')->count(),
            'low_stock_items' => $inventoryCategory->items()
                ->whereRaw('available_quantity <= minimum_stock_level')
                ->where('minimum_stock_level', '>', 0)
                ->count(),
        ];

        // Get related categories (same first letter of code or similar names)
        $relatedCategories = InventoryCategory::where('id', '!=', $inventoryCategory->id)
            ->where('is_active', true)
            ->where(function ($query) use ($inventoryCategory) {
                $query->where('code', 'like', substr($inventoryCategory->code, 0, 1) . '%')
                    ->orWhere('name', 'like', '%' . explode(' ', $inventoryCategory->name)[0] . '%');
            })
            ->withCount('items')
            ->limit(5)
            ->get();
        
        return view('inventory.categories.show', compact('inventoryCategory', 'items', 'stats', 'relatedCategories'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(InventoryCategory $inventoryCategory)
    {
        // Load items count for display
        $inventoryCategory->loadCount('items');

        return view('inventory.categories.edit', compact('inventoryCategory'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, InventoryCategory $inventoryCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:inventory_categories,name,' . $inventoryCategory->id,
            'code' => 'required|string|max:10|unique:inventory_categories,code,' . $inventoryCategory->id,
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

        $inventoryCategory->update($validated);

        return redirect()->route('inventory.categories.show', $inventoryCategory)
                        ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(InventoryCategory $inventoryCategory)
    {
        // Check if category has items
        if ($inventoryCategory->items()->exists()) {
            return redirect()->route('inventory.categories.show', $inventoryCategory)
                           ->with('error', 'Cannot delete category that has items assigned to it.');
        }

        $inventoryCategory->delete();

        return redirect()->route('inventory.categories.index')
                        ->with('success', 'Category deleted successfully!');
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus(InventoryCategory $inventoryCategory)
    {
        $inventoryCategory->update([
            'is_active' => !$inventoryCategory->is_active
        ]);

        $status = $inventoryCategory->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
                        ->with('success', "Category {$status} successfully!");
    }

    /**
     * Show items with low stock in this category.
     */
    public function lowStock(InventoryCategory $inventoryCategory)
    {
        $lowStockItems = $inventoryCategory->items()
            ->whereRaw('available_quantity <= minimum_stock_level')
            ->where('minimum_stock_level', '>', 0)
            ->with(['activeAssignments.staff'])
            ->paginate(15);

        return view('inventory.categories.low-stock', compact('inventoryCategory', 'lowStockItems'));
    }

    /**
     * Show all items in this category.
     */
    public function items(InventoryCategory $inventoryCategory)
    {
        $items = $inventoryCategory->items()
            ->with(['activeAssignments.staff'])
            ->paginate(20);

        return view('inventory.categories.items', compact('inventoryCategory', 'items'));
    }

    /**
     * Show items that need inspection in this category.
     */
    public function needsInspection(InventoryCategory $inventoryCategory)
    {
        $inspectionItems = $inventoryCategory->items()
            ->where(function ($query) {
                $query->where('status', 'needs_inspection')
                    ->orWhere('next_inspection', '<', now())
                    ->orWhereNull('next_inspection');
            })
            ->with(['activeAssignments.staff'])
            ->paginate(15);

        return view('inventory.categories.needs-inspection', compact('inventoryCategory', 'inspectionItems'));
    }

        /**
     * Schedule inspection for single item
     */
    public function scheduleInspection(Request $request, InventoryItem $item)
    {
        $request->validate([
            'next_inspection' => 'required|date|after:today',
            'inspection_notes' => 'nullable|string',
        ]);

        $item->update([
            'next_inspection' => $request->next_inspection,
            'inspection_notes' => $request->inspection_notes,
        ]);

        return redirect()->back()->with('success', 'Inspection scheduled successfully!');
    }

    /**
     * Mark single item as inspected
     */
    public function markInspected(InventoryItem $item)
    {
        $item->update([
            'last_inspection_date' => now(),
            'next_inspection' => now()->addMonths(6), // or your standard interval
            'status' => 'active',
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Bulk mark items as inspected
     */
    public function bulkMarkInspected(Request $request)
    {
        $itemIds = $request->input('items', []);
        
        InventoryItem::whereIn('id', $itemIds)->update([
            'last_inspection_date' => now(),
            'next_inspection' => now()->addMonths(6),
            'status' => 'active',
        ]);

        return response()->json(['success' => true]);
    }
}