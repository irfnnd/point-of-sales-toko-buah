<?php

namespace Database\Seeders;

use App\Models\Fruit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default User
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Dummy Data Buah
        $fruits = [
            ['code' => 'BH-001', 'name' => 'Apel Fuji Super', 'unit' => 'kg', 'purchase_price' => 28000, 'selling_price' => 38000],
            ['code' => 'BH-002', 'name' => 'Pisang Cavendish Premium', 'unit' => 'sisir', 'purchase_price' => 18000, 'selling_price' => 25000],
            ['code' => 'BH-003', 'name' => 'Mangga Harum Manis', 'unit' => 'kg', 'purchase_price' => 22000, 'selling_price' => 32000],
            ['code' => 'BH-004', 'name' => 'Jeruk Sunkist Australi', 'unit' => 'kg', 'purchase_price' => 35000, 'selling_price' => 48000],
            ['code' => 'BH-005', 'name' => 'Anggur Merah Import', 'unit' => 'kg', 'purchase_price' => 55000, 'selling_price' => 75000],
            ['code' => 'BH-006', 'name' => 'Alpukat Mentega Super', 'unit' => 'kg', 'purchase_price' => 30000, 'selling_price' => 42000],
            ['code' => 'BH-007', 'name' => 'Semangka Tanpa Biji', 'unit' => 'kg', 'purchase_price' => 8000, 'selling_price' => 14000],
            ['code' => 'BH-008', 'name' => 'Melon Sky Rocket', 'unit' => 'kg', 'purchase_price' => 12000, 'selling_price' => 19000],
            ['code' => 'BH-009', 'name' => 'Durian Montong Super', 'unit' => 'kg', 'purchase_price' => 85000, 'selling_price' => 120000],
            ['code' => 'BH-010', 'name' => 'Buah Naga Merah Fresh', 'unit' => 'kg', 'purchase_price' => 16000, 'selling_price' => 24000],
            ['code' => 'BH-011', 'name' => 'Stroberi Fresh Bandung', 'unit' => 'pack', 'purchase_price' => 15000, 'selling_price' => 22500],
            ['code' => 'BH-012', 'name' => 'Kiwi Hijau Zespri', 'unit' => 'pcs', 'purchase_price' => 8000, 'selling_price' => 12000],
            ['code' => 'BH-013', 'name' => 'Nanas Honi Subang', 'unit' => 'pcs', 'purchase_price' => 14000, 'selling_price' => 20000],
            ['code' => 'BH-014', 'name' => 'Pepaya California Super', 'unit' => 'kg', 'purchase_price' => 7000, 'selling_price' => 11000],
            ['code' => 'BH-015', 'name' => 'Kelengkeng Bangkok', 'unit' => 'kg', 'purchase_price' => 32000, 'selling_price' => 45000],
            ['code' => 'BH-016', 'name' => 'Pir Singo Korea', 'unit' => 'kg', 'purchase_price' => 30000, 'selling_price' => 42000],
        ];

        foreach ($fruits as $fruitData) {
            Fruit::updateOrCreate(
                ['code' => $fruitData['code']],
                $fruitData
            );
        }
    }
}
