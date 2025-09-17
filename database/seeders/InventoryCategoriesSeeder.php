<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryCategory;

class InventoryCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Weapons & Firearms',
                'code' => 'WPNS',
                'description' => 'Military weapons, firearms, and related equipment',
                'color' => '#DC2626', // Red
                'requires_approval' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Uniforms & Clothing',
                'code' => 'UNIF',
                'description' => 'Military uniforms, clothing, and personal wear items',
                'color' => '#059669', // Green
                'requires_approval' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Electronics & Communication',
                'code' => 'ELEC',
                'description' => 'Radios, computers, electronic equipment',
                'color' => '#2563EB', // Blue
                'requires_approval' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Protective Equipment',
                'code' => 'PROT',
                'description' => 'Body armor, helmets, safety equipment',
                'color' => '#7C3AED', // Purple
                'requires_approval' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Medical Supplies',
                'code' => 'MED',
                'description' => 'First aid kits, medical equipment, pharmaceuticals',
                'color' => '#DC2626', // Red
                'requires_approval' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Vehicles & Transport',
                'code' => 'VEH',
                'description' => 'Military vehicles, transport equipment',
                'color' => '#1F2937', // Gray
                'requires_approval' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Tools & Equipment',
                'code' => 'TOOL',
                'description' => 'General tools, maintenance equipment',
                'color' => '#F59E0B', // Yellow
                'requires_approval' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Books & Educational',
                'code' => 'EDU',
                'description' => 'Textbooks, training materials, educational resources',
                'color' => '#10B981', // Emerald
                'requires_approval' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Office Supplies',
                'code' => 'OFF',
                'description' => 'Stationery, office equipment, administrative supplies',
                'color' => '#6B7280', // Gray
                'requires_approval' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Sports & Recreation',
                'code' => 'SPORT',
                'description' => 'Sports equipment, recreational items',
                'color' => '#8B5CF6', // Violet
                'requires_approval' => false,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            InventoryCategory::create($category);
        }
    }
}