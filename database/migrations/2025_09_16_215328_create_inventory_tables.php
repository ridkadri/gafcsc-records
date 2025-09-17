<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create inventory categories table
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique(); // e.g., 'WPNS', 'UNIF', 'ELEC'
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6B7280'); // Hex color for UI
            $table->boolean('requires_approval')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create inventory items table
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('item_code')->unique(); // e.g., 'M16A2-001'
            $table->string('barcode')->unique()->nullable();
            $table->foreignId('category_id')->constrained('inventory_categories');
            $table->text('description')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->integer('total_quantity');
            $table->integer('available_quantity');
            $table->integer('assigned_quantity')->default(0);
            $table->integer('maintenance_quantity')->default(0);
            $table->integer('minimum_stock_level')->default(0);
            $table->string('condition')->default('good'); // good, fair, poor, damaged
            $table->string('location')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->date('last_inspection')->nullable();
            $table->date('next_inspection')->nullable();
            $table->json('specifications')->nullable(); // Store additional specs as JSON
            $table->text('notes')->nullable();
            $table->string('status')->default('active'); // active, retired, disposed
            $table->timestamps();

            // Add indexes
            $table->index(['category_id', 'status']);
            $table->index(['condition', 'status']);
            $table->index(['location']);
        });

        // Create inventory assignments table (who has what)
        Schema::create('inventory_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items');
            $table->foreignId('staff_id')->constrained('staff');
            $table->integer('quantity');
            $table->date('assigned_date');
            $table->date('expected_return_date')->nullable();
            $table->date('actual_return_date')->nullable();
            $table->string('status')->default('active'); // active, returned, overdue, lost
            $table->text('assignment_notes')->nullable();
            $table->text('return_notes')->nullable();
            $table->string('condition_on_return')->nullable();
            $table->foreignId('assigned_by')->constrained('users');
            $table->foreignId('returned_to')->nullable()->constrained('users');
            $table->timestamps();

            // Add indexes
            $table->index(['staff_id', 'status']);
            $table->index(['item_id', 'status']);
            $table->index(['expected_return_date']);
            $table->index(['status']);
        });

        // Create inventory transactions table (audit trail)
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items');
            $table->foreignId('staff_id')->nullable()->constrained('staff');
            $table->string('transaction_type'); // check_out, check_in, purchase, disposal, maintenance, transfer
            $table->integer('quantity');
            $table->integer('quantity_before');
            $table->integer('quantity_after');
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->constrained('users');
            $table->json('metadata')->nullable(); // Store additional transaction data
            $table->timestamps();

            // Add indexes
            $table->index(['item_id', 'created_at']);
            $table->index(['staff_id', 'created_at']);
            $table->index(['transaction_type', 'created_at']);
            $table->index(['processed_by']);
        });

        // Create inventory maintenance records
        Schema::create('inventory_maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items');
            $table->string('maintenance_type'); // routine, repair, upgrade, inspection
            $table->date('scheduled_date');
            $table->date('completed_date')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, in_progress, completed, cancelled
            $table->text('description');
            $table->text('notes')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('performed_by')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            // Add indexes
            $table->index(['item_id', 'status']);
            $table->index(['scheduled_date']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_maintenance');
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('inventory_assignments');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('inventory_categories');
    }
};