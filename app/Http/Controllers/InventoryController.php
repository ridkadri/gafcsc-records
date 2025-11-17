<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryCategory;
use App\Models\InventoryAssignment;
use App\Models\InventoryTransaction;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display inventory dashboard.
     */
    public function index(Request $request)
    {
        $query = InventoryItem::with(['category', 'activeAssignments.staff']);
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }
        
        // Category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        // Condition filter
        if ($request->has('condition') && $request->condition != '') {
            $query->where('condition', $request->condition);
        }
        
        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Location filter
        if ($request->has('location') && $request->location != '') {
            $query->where('location', $request->location);
        }
        
        $items = $query->active()->orderBy('name')->paginate(15);
        
        // Get filter options
        $categories = InventoryCategory::active()->orderBy('name')->get();
        $locations = InventoryItem::active()
                                 ->whereNotNull('location')
                                 ->distinct()
                                 ->pluck('location')
                                 ->sort();
        
        // Dashboard stats
        $stats = [
            'total_items' => InventoryItem::active()->count(),
            'total_value' => InventoryItem::active()->sum(DB::raw('unit_cost * total_quantity')),
            'low_stock_count' => InventoryItem::active()->lowStock()->count(),
            'active_assignments' => InventoryAssignment::active()->count(),
            'overdue_assignments' => InventoryAssignment::overdue()->count(),
        ];
        
        return view('inventory.index', compact('items', 'categories', 'locations', 'stats'));
    }

    /**
     * Show the form for creating a new inventory item.
     */
    public function create()
    {
        $categories = InventoryCategory::active()->orderBy('name')->get();
        $locations = InventoryItem::active()
                                 ->whereNotNull('location')
                                 ->distinct()
                                 ->pluck('location')
                                 ->sort();
        
        return view('inventory.create', compact('categories', 'locations'));
    }

    /**
     * Store a newly created inventory item.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'item_code' => 'required|string|max:255|unique:inventory_items',
            'barcode' => 'nullable|string|max:255|unique:inventory_items',
            'category_id' => 'required|exists:inventory_categories,id',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'unit_cost' => 'nullable|numeric|min:0',
            'total_quantity' => 'required|integer|min:1',
            'minimum_stock_level' => 'required|integer|min:0',
            'condition' => 'required|in:good,fair,poor,damaged',
            'location' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date|after:purchase_date',
            'specifications' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['available_quantity'] = $data['total_quantity'];
        
        // Parse specifications if provided
        if ($request->specifications) {
            $data['specifications'] = json_decode($request->specifications, true) ?? [];
        }

        $item = InventoryItem::create($data);

        // Create initial transaction record
        InventoryTransaction::create([
            'item_id' => $item->id,
            'transaction_type' => 'purchase',
            'quantity' => $item->total_quantity,
            'quantity_before' => 0,
            'quantity_after' => $item->total_quantity,
            'notes' => 'Initial inventory entry',
            'processed_by' => auth()->id(),
        ]);

        return redirect()->route('inventory.show', $item)
                        ->with('success', 'Inventory item added successfully!');
    }

    /**
     * Display the specified inventory item.
     */
    public function show(InventoryItem $item)
    {
        $item->load([
            'category',
            'activeAssignments.staff',
            'transactions.processedBy',
            'transactions.staff',
            'maintenanceRecords'
        ]);
        
        return view('inventory.show', compact('item'));
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(InventoryItem $item)
    {
        $categories = InventoryCategory::active()->orderBy('name')->get();
        $locations = InventoryItem::active()
                                 ->whereNotNull('location')
                                 ->distinct()
                                 ->pluck('location')
                                 ->sort();
        
        return view('inventory.edit', compact('item', 'categories', 'locations'));
    }

    /**
     * Update the specified inventory item.
     */
    public function update(Request $request, InventoryItem $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'item_code' => 'required|string|max:255|unique:inventory_items,item_code,' . $item->id,
            'barcode' => 'nullable|string|max:255|unique:inventory_items,barcode,' . $item->id,
            'category_id' => 'required|exists:inventory_categories,id',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'unit_cost' => 'nullable|numeric|min:0',
            'minimum_stock_level' => 'required|integer|min:0',
            'condition' => 'required|in:good,fair,poor,damaged',
            'location' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date|after:purchase_date',
            'specifications' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        
        // Parse specifications if provided
        if ($request->specifications) {
            $data['specifications'] = json_decode($request->specifications, true) ?? [];
        }

        $item->update($data);

        return redirect()->route('inventory.show', $item)
                        ->with('success', 'Inventory item updated successfully!');
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy(InventoryItem $item)
    {
        // Check if item has active assignments
        if ($item->activeAssignments()->exists()) {
            return redirect()->route('inventory.show', $item)
                           ->with('error', 'Cannot delete item with active assignments. Please return all assigned items first.');
        }

        // Update status instead of deleting
        $item->update(['status' => 'retired']);

        // Create transaction record
        InventoryTransaction::create([
            'item_id' => $item->id,
            'transaction_type' => 'disposal',
            'quantity' => $item->total_quantity,
            'quantity_before' => $item->available_quantity,
            'quantity_after' => 0,
            'notes' => 'Item retired from inventory',
            'processed_by' => auth()->id(),
        ]);

        return redirect()->route('inventory.index')
                        ->with('success', 'Inventory item retired successfully!');
    }

    /**
     * Show low stock items.
     */
    public function lowStock()
    {
        $items = InventoryItem::with(['category'])
                             ->active()
                             ->lowStock()
                             ->orderBy('available_quantity', 'asc')
                             ->get();

        return view('inventory.low-stock', compact('items'));
    }

    /**
     * Show items needing inspection.
     */
    public function needsInspection()
    {
        $items = InventoryItem::with(['category'])
                             ->active()
                             ->needsInspection()
                             ->orderBy('next_inspection', 'asc')
                             ->get();

        return view('inventory.needs-inspection', compact('items'));
    }

    /**
     * Adjust inventory quantity.
     */
    public function adjustQuantity(Request $request, InventoryItem $item)
    {
        $request->validate([
            'adjustment_type' => 'required|in:increase,decrease',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $quantityBefore = $item->total_quantity;
        $availableBefore = $item->available_quantity;

        if ($request->adjustment_type === 'increase') {
            $item->total_quantity += $request->quantity;
            $item->available_quantity += $request->quantity;
            $transactionType = 'purchase';
        } else {
            if ($request->quantity > $item->available_quantity) {
                return redirect()->back()
                               ->with('error', 'Cannot decrease quantity by more than available quantity.');
            }
            $item->total_quantity -= $request->quantity;
            $item->available_quantity -= $request->quantity;
            $transactionType = 'disposal';
        }

        $item->save();

        // Create transaction record
        InventoryTransaction::create([
            'item_id' => $item->id,
            'transaction_type' => $transactionType,
            'quantity' => $request->quantity,
            'quantity_before' => $quantityBefore,
            'quantity_after' => $item->total_quantity,
            'notes' => $request->notes ?? 'Manual quantity adjustment',
            'processed_by' => auth()->id(),
        ]);

        return redirect()->route('inventory.show', $item)
                        ->with('success', 'Inventory quantity adjusted successfully!');
    }

    /**
     * Generate barcode for item.
     */
    public function generateBarcode(InventoryItem $item)
    {
        if ($item->barcode) {
            return redirect()->back()
                           ->with('error', 'Item already has a barcode.');
        }

        // Generate a simple barcode based on item ID and code
        $barcode = 'INV-' . str_pad($item->id, 6, '0', STR_PAD_LEFT) . '-' . strtoupper(substr($item->item_code, 0, 3));
        
        $item->update(['barcode' => $barcode]);

        return redirect()->back()
                        ->with('success', 'Barcode generated successfully!');
    }

    /**
     * Get inventory statistics for reports.
     */
    public function getStats()
    {
        $stats = [
            'total_items' => InventoryItem::active()->count(),
            'total_value' => InventoryItem::active()->sum(DB::raw('unit_cost * total_quantity')),
            'categories_count' => InventoryCategory::active()->count(),
            'low_stock_items' => InventoryItem::active()->lowStock()->count(),
            'active_assignments' => InventoryAssignment::active()->count(),
            'overdue_assignments' => InventoryAssignment::overdue()->count(),
            'category_breakdown' => InventoryItem::active()
                                                  ->join('inventory_categories', 'inventory_items.category_id', '=', 'inventory_categories.id')
                                                  ->selectRaw('inventory_categories.name, COUNT(*) as count')
                                                  ->groupBy('inventory_categories.name')
                                                  ->get(),
            'condition_breakdown' => InventoryItem::active()
                                                  ->selectRaw('condition, COUNT(*) as count')
                                                  ->groupBy('condition')
                                                  ->get(),
        ];

        return response()->json($stats);
    }
}