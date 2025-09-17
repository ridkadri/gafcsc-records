<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryAssignment;
use App\Models\InventoryTransaction;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryAssignmentController extends Controller
{
    /**
     * Display all inventory assignments.
     */
    public function index(Request $request)
    {
        $query = InventoryAssignment::with(['item.category', 'staff', 'assignedBy']);
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('staff', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('service_number', 'like', "%{$search}%");
            })->orWhereHas('item', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('item_code', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->has('status') && $request->status != '') {
            if ($request->status === 'overdue') {
                $query->where('status', 'active')
                      ->where('expected_return_date', '<', now());
            } else {
                $query->where('status', $request->status);
            }
        }
        
        // Staff filter
        if ($request->has('staff_id') && $request->staff_id != '') {
            $query->where('staff_id', $request->staff_id);
        }
        
        $assignments = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get filter options
        $staff = Staff::orderBy('name')->get();
        
        // Stats
        $stats = [
            'total_active' => InventoryAssignment::active()->count(),
            'total_overdue' => InventoryAssignment::overdue()->count(),
            'total_returned' => InventoryAssignment::where('status', 'returned')->count(),
        ];
        
        return view('inventory.assignments.index', compact('assignments', 'staff', 'stats'));
    }

    /**
     * Show the form for creating a new assignment (checkout).
     */
    public function create(Request $request)
    {
        $item = null;
        if ($request->has('item_id')) {
            $item = InventoryItem::findOrFail($request->item_id);
        }
        
        $items = InventoryItem::active()
                             ->where('available_quantity', '>', 0)
                             ->with('category')
                             ->orderBy('name')
                             ->get();
                             
        $staff = Staff::orderBy('name')->get();
        
        return view('inventory.assignments.create', compact('items', 'staff', 'item'));
    }

    /**
     * Store a new assignment (checkout item).
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'staff_id' => 'required|exists:staff,id',
            'quantity' => 'required|integer|min:1',
            'expected_return_date' => 'nullable|date|after:today',
            'assignment_notes' => 'nullable|string',
        ]);

        $item = InventoryItem::findOrFail($request->item_id);
        
        // Check if enough quantity is available
        if ($request->quantity > $item->available_quantity) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', "Only {$item->available_quantity} units available for assignment.");
        }

        DB::transaction(function() use ($request, $item) {
            // Create assignment
            $assignment = InventoryAssignment::create([
                'item_id' => $request->item_id,
                'staff_id' => $request->staff_id,
                'quantity' => $request->quantity,
                'assigned_date' => now(),
                'expected_return_date' => $request->expected_return_date,
                'assignment_notes' => $request->assignment_notes,
                'assigned_by' => auth()->id(),
            ]);

            // Update item quantities
            $item->available_quantity -= $request->quantity;
            $item->assigned_quantity += $request->quantity;
            $item->save();

            // Create transaction record
            InventoryTransaction::create([
                'item_id' => $item->id,
                'staff_id' => $request->staff_id,
                'transaction_type' => 'check_out',
                'quantity' => $request->quantity,
                'quantity_before' => $item->available_quantity + $request->quantity,
                'quantity_after' => $item->available_quantity,
                'notes' => $request->assignment_notes ?? 'Item checked out',
                'processed_by' => auth()->id(),
                'metadata' => [
                    'assignment_id' => $assignment->id,
                    'expected_return_date' => $request->expected_return_date,
                ]
            ]);
        });

        return redirect()->route('inventory.assignments.index')
                        ->with('success', 'Item checked out successfully!');
    }

    /**
     * Display the specified assignment.
     */
    public function show(InventoryAssignment $assignment)
    {
        $assignment->load(['item.category', 'staff', 'assignedBy', 'returnedTo']);
        
        return view('inventory.assignments.show', compact('assignment'));
    }

    /**
     * Show the form for returning an item (check-in).
     */
    public function returnForm(InventoryAssignment $assignment)
    {
        if ($assignment->status !== 'active') {
            return redirect()->route('inventory.assignments.show', $assignment)
                           ->with('error', 'This assignment is not active.');
        }
        
        return view('inventory.assignments.return', compact('assignment'));
    }

    /**
     * Process item return (check-in).
     */
    public function processReturn(Request $request, InventoryAssignment $assignment)
    {
        $request->validate([
            'quantity_returned' => 'required|integer|min:1|max:' . $assignment->quantity,
            'condition_on_return' => 'required|in:good,fair,poor,damaged',
            'return_notes' => 'nullable|string',
        ]);

        if ($assignment->status !== 'active') {
            return redirect()->route('inventory.assignments.show', $assignment)
                           ->with('error', 'This assignment is not active.');
        }

        $item = $assignment->item;
        
        DB::transaction(function() use ($request, $assignment, $item) {
            // Update assignment
            $assignment->update([
                'actual_return_date' => now(),
                'condition_on_return' => $request->condition_on_return,
                'return_notes' => $request->return_notes,
                'returned_to' => auth()->id(),
                'status' => $request->quantity_returned == $assignment->quantity ? 'returned' : 'active',
            ]);

            // If partial return, create new assignment for remaining quantity
            if ($request->quantity_returned < $assignment->quantity) {
                $remainingQuantity = $assignment->quantity - $request->quantity_returned;
                
                // Update original assignment to reflect returned quantity
                $assignment->update(['quantity' => $request->quantity_returned]);
                
                // Create new assignment for remaining quantity
                InventoryAssignment::create([
                    'item_id' => $assignment->item_id,
                    'staff_id' => $assignment->staff_id,
                    'quantity' => $remainingQuantity,
                    'assigned_date' => $assignment->assigned_date,
                    'expected_return_date' => $assignment->expected_return_date,
                    'assignment_notes' => $assignment->assignment_notes . ' (Partial return - remaining items)',
                    'assigned_by' => $assignment->assigned_by,
                    'status' => 'active',
                ]);
            }

            // Update item quantities
            $item->available_quantity += $request->quantity_returned;
            $item->assigned_quantity -= $request->quantity_returned;
            
            // If condition is poor or damaged, move to maintenance
            if (in_array($request->condition_on_return, ['poor', 'damaged'])) {
                $item->maintenance_quantity += $request->quantity_returned;
                $item->available_quantity -= $request->quantity_returned;
            }
            
            $item->save();

            // Create transaction record
            InventoryTransaction::create([
                'item_id' => $item->id,
                'staff_id' => $assignment->staff_id,
                'transaction_type' => 'check_in',
                'quantity' => $request->quantity_returned,
                'quantity_before' => $item->available_quantity - $request->quantity_returned,
                'quantity_after' => $item->available_quantity,
                'notes' => $request->return_notes ?? 'Item returned',
                'processed_by' => auth()->id(),
                'metadata' => [
                    'assignment_id' => $assignment->id,
                    'condition_on_return' => $request->condition_on_return,
                ]
            ]);
        });

        return redirect()->route('inventory.assignments.show', $assignment)
                        ->with('success', 'Item returned successfully!');
    }

    /**
     * Mark assignment as lost.
     */
    public function markAsLost(Request $request, InventoryAssignment $assignment)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        if ($assignment->status !== 'active') {
            return redirect()->route('inventory.assignments.show', $assignment)
                           ->with('error', 'This assignment is not active.');
        }

        DB::transaction(function() use ($request, $assignment) {
            $item = $assignment->item;

            // Update assignment
            $assignment->update([
                'status' => 'lost',
                'return_notes' => $request->notes,
                'returned_to' => auth()->id(),
                'actual_return_date' => now(),
            ]);

            // Update item quantities (reduce total quantity as item is lost)
            $item->total_quantity -= $assignment->quantity;
            $item->assigned_quantity -= $assignment->quantity;
            $item->save();

            // Create transaction record
            InventoryTransaction::create([
                'item_id' => $item->id,
                'staff_id' => $assignment->staff_id,
                'transaction_type' => 'disposal',
                'quantity' => $assignment->quantity,
                'quantity_before' => $item->total_quantity + $assignment->quantity,
                'quantity_after' => $item->total_quantity,
                'notes' => 'Item reported as lost: ' . $request->notes,
                'processed_by' => auth()->id(),
                'metadata' => [
                    'assignment_id' => $assignment->id,
                    'reason' => 'lost',
                ]
            ]);
        });

        return redirect()->route('inventory.assignments.show', $assignment)
                        ->with('success', 'Assignment marked as lost.');
    }

    /**
     * Get overdue assignments.
     */
    public function overdue()
    {
        $assignments = InventoryAssignment::with(['item.category', 'staff', 'assignedBy'])
                                         ->overdue()
                                         ->orderBy('expected_return_date', 'asc')
                                         ->get();

        return view('inventory.assignments.overdue', compact('assignments'));
    }

    /**
     * Send reminder for overdue assignments.
     */
    public function sendReminder(InventoryAssignment $assignment)
    {
        // This would integrate with your notification system
        // For now, just return success message
        
        return redirect()->back()
                        ->with('success', 'Reminder sent successfully!');
    }

    /**
     * Bulk return items.
     */
    public function bulkReturn(Request $request)
    {
        $request->validate([
            'assignment_ids' => 'required|array',
            'assignment_ids.*' => 'exists:inventory_assignments,id',
            'condition_on_return' => 'required|in:good,fair,poor,damaged',
            'return_notes' => 'nullable|string',
        ]);

        $processed = 0;
        
        foreach ($request->assignment_ids as $assignmentId) {
            $assignment = InventoryAssignment::find($assignmentId);
            
            if ($assignment && $assignment->status === 'active') {
                // Process return with full quantity
                $this->processReturn(new Request([
                    'quantity_returned' => $assignment->quantity,
                    'condition_on_return' => $request->condition_on_return,
                    'return_notes' => $request->return_notes,
                ]), $assignment);
                
                $processed++;
            }
        }

        return redirect()->route('inventory.assignments.index')
                        ->with('success', "Successfully processed {$processed} returns.");
    }
}