<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\StockMovement;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Users & Profiles
        $admin = User::create([
            'username' => 'admin',
            'password' => 'admin123',
            'role' => 'admin',
        ]);
        UserProfile::create([
            'user_id' => $admin->id,
            'full_name' => 'Administrator Sistem',
            'phone' => '081234567890',
            'address' => 'Gedung Pusat Operasional Lantai 3',
        ]);

        $staff = User::create([
            'username' => 'staff',
            'password' => 'staff123',
            'role' => 'staff',
        ]);
        UserProfile::create([
            'user_id' => $staff->id,
            'full_name' => 'Budi Santoso (Staff Gudang)',
            'phone' => '082198765432',
            'address' => 'Jl. Pergudangan Logistik Blok B No. 4',
        ]);

        $siti = User::create([
            'username' => 'siti',
            'password' => 'staff123',
            'role' => 'staff',
        ]);
        UserProfile::create([
            'user_id' => $siti->id,
            'full_name' => 'Siti Rahma (Staff Operasional)',
            'phone' => '085712348765',
            'address' => 'Jl. Melati Raya No. 12',
        ]);

        // 2. Seed Categories
        $cat1 = Category::create(['name' => 'Komponen Komputer', 'description' => 'Perangkat keras internal komputer dan laptop']);
        $cat2 = Category::create(['name' => 'Aksesoris & Periferal', 'description' => 'Perangkat input/output seperti keyboard, mouse, webcam']);
        $cat3 = Category::create(['name' => 'Gadget & Audio', 'description' => 'Perangkat audio, speaker, headphone nirkabel']);
        $cat4 = Category::create(['name' => 'Peralatan Ergonomis', 'description' => 'Kursi dan meja kerja pendukung produktivitas']);
        $cat5 = Category::create(['name' => 'Jaringan & Konektivitas', 'description' => 'Router, switch, adaptor dan kabel jaringan']);

        // 3. Seed Suppliers
        $sup1 = Supplier::create([
            'name' => 'PT Nusantara Tech Logistik',
            'contact_person' => 'Ir. Hendra Gunawan',
            'phone' => '021-5551234',
            'email' => 'sales@nusantaratech.id',
            'address' => 'Kawasan Industri Pulogadung Kav 18, Jakarta'
        ]);
        $sup2 = Supplier::create([
            'name' => 'CV Maju Jaya Elektronik',
            'contact_person' => 'Dewi Lestari',
            'phone' => '022-7778901',
            'email' => 'order@majujaya.co.id',
            'address' => 'Jl. Soekarno Hatta No. 102, Bandung'
        ]);
        $sup3 = Supplier::create([
            'name' => 'Global Peripherals Supply',
            'contact_person' => 'Michael Tan',
            'phone' => '031-8884321',
            'email' => 'support@globalperipherals.com',
            'address' => 'Rungkut Industri III No. 45, Surabaya'
        ]);
        $sup4 = Supplier::create([
            'name' => 'Mitra Ergonomis Indonesia',
            'contact_person' => 'Rian Hidayat',
            'phone' => '021-9993322',
            'email' => 'info@mitraergonomis.id',
            'address' => 'Sentra Niaga Puri Indah Blok C, Jakarta Barat'
        ]);

        // 4. Seed Products
        $p1 = Product::create(['category_id' => $cat2->id, 'sku' => 'SKU-KB-001', 'name' => 'Mechanical Keyboard RGB TKL Brown Switch', 'price' => 650000, 'stock' => 28, 'minimum_stock' => 10]);
        $p2 = Product::create(['category_id' => $cat2->id, 'sku' => 'SKU-MS-002', 'name' => 'Wireless Gaming Mouse 16000 DPI Ultra-Light', 'price' => 380000, 'stock' => 4, 'minimum_stock' => 10]);
        $p3 = Product::create(['category_id' => $cat1->id, 'sku' => 'SKU-MN-003', 'name' => 'Monitor Gaming 24 Inch IPS 144Hz 1ms', 'price' => 1950000, 'stock' => 15, 'minimum_stock' => 5]);
        $p4 = Product::create(['category_id' => $cat3->id, 'sku' => 'SKU-HS-004', 'name' => 'Noise-Cancelling Wireless Headset Bluetooth', 'price' => 750000, 'stock' => 3, 'minimum_stock' => 8]);
        $p5 = Product::create(['category_id' => $cat2->id, 'sku' => 'SKU-WC-005', 'name' => 'Webcam Full HD 1080p with Dual Microphone', 'price' => 290000, 'stock' => 35, 'minimum_stock' => 12]);
        $p6 = Product::create(['category_id' => $cat3->id, 'sku' => 'SKU-SP-006', 'name' => 'Portable Bluetooth Speaker Waterproof IPX7', 'price' => 420000, 'stock' => 0, 'minimum_stock' => 5]);
        $p7 = Product::create(['category_id' => $cat4->id, 'sku' => 'SKU-CH-007', 'name' => 'Kursi Kerja Ergonomis Mesh Backrest Lumbar Support', 'price' => 1450000, 'stock' => 12, 'minimum_stock' => 5]);
        $p8 = Product::create(['category_id' => $cat4->id, 'sku' => 'SKU-DK-008', 'name' => 'Meja Kerja Minimalis Adjustable 120x60cm', 'price' => 950000, 'stock' => 18, 'minimum_stock' => 5]);
        $p9 = Product::create(['category_id' => $cat5->id, 'sku' => 'SKU-RT-009', 'name' => 'Wi-Fi 6 Dual-Band Gigabit Router AX1800', 'price' => 580000, 'stock' => 22, 'minimum_stock' => 8]);
        $p10 = Product::create(['category_id' => $cat1->id, 'sku' => 'SKU-SSD-010', 'name' => 'NVMe M.2 SSD 1TB PCIe Gen4 x4 High Speed', 'price' => 1100000, 'stock' => 6, 'minimum_stock' => 10]);

        // 5. Seed Many-to-Many product_suppliers
        $p1->suppliers()->attach([$sup1->id => ['supplier_price' => 520000], $sup3->id => ['supplier_price' => 510000]]);
        $p2->suppliers()->attach([$sup3->id => ['supplier_price' => 295000]]);
        $p3->suppliers()->attach([$sup1->id => ['supplier_price' => 1600000], $sup2->id => ['supplier_price' => 1580000]]);
        $p4->suppliers()->attach([$sup2->id => ['supplier_price' => 600000], $sup3->id => ['supplier_price' => 590000]]);
        $p5->suppliers()->attach([$sup1->id => ['supplier_price' => 220000]]);
        $p6->suppliers()->attach([$sup2->id => ['supplier_price' => 330000]]);
        $p7->suppliers()->attach([$sup4->id => ['supplier_price' => 1150000]]);
        $p8->suppliers()->attach([$sup4->id => ['supplier_price' => 720000]]);
        $p9->suppliers()->attach([$sup1->id => ['supplier_price' => 460000]]);
        $p10->suppliers()->attach([$sup1->id => ['supplier_price' => 880000], $sup2->id => ['supplier_price' => 875000]]);

        // 6. Seed Stock Movements
        $movements = [
            [$p1->id, $admin->id, 'IN', 35, 'Penerimaan stok awal batch 1'],
            [$p1->id, $staff->id, 'OUT', 7, 'Pengiriman pesanan internal B2B'],
            [$p2->id, $admin->id, 'IN', 10, 'Stok awal mouse'],
            [$p2->id, $staff->id, 'OUT', 6, 'Penjualan offline store'],
            [$p3->id, $admin->id, 'IN', 20, 'Penerimaan monitor dari supplier'],
            [$p3->id, $siti->id, 'OUT', 5, 'Instalasi lab komputer kantor'],
            [$p4->id, $staff->id, 'IN', 10, 'Penerimaan headset'],
            [$p4->id, $staff->id, 'OUT', 7, 'Distribusi divisi call center'],
            [$p5->id, $admin->id, 'IN', 40, 'Penerimaan webcam Full HD'],
            [$p5->id, $siti->id, 'OUT', 5, 'Penjualan retail'],
            [$p6->id, $staff->id, 'IN', 15, 'Penerimaan batch speaker awal'],
            [$p6->id, $staff->id, 'OUT', 15, 'Penjualan habis promosi flash sale'],
            [$p7->id, $admin->id, 'IN', 15, 'Penerimaan kursi ergonomis'],
            [$p7->id, $siti->id, 'OUT', 3, 'Pengadaan ruang meeting'],
            [$p8->id, $admin->id, 'IN', 20, 'Penerimaan meja kerja'],
            [$p8->id, $staff->id, 'OUT', 2, 'Kebutuhan workstation baru'],
            [$p9->id, $siti->id, 'IN', 25, 'Penerimaan router AX1800'],
            [$p9->id, $siti->id, 'OUT', 3, 'Upgrade jaringan cabang'],
            [$p10->id, $admin->id, 'IN', 15, 'Penerimaan SSD M.2 NVMe'],
            [$p10->id, $staff->id, 'OUT', 9, 'Perakitan PC server internal'],
        ];

        foreach ($movements as $m) {
            StockMovement::create([
                'product_id' => $m[0],
                'user_id' => $m[1],
                'type' => $m[2],
                'quantity' => $m[3],
                'notes' => $m[4],
            ]);
        }
    }
}
