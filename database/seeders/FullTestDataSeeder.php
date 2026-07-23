<?php

namespace Database\Seeders;

use App\Models\AccountTransaction;
use App\Models\ActivityLog;
use App\Models\CashAccount;
use App\Models\Category;
use App\Models\DamagedMedicine;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Generic;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\MedicineType;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Pharmacy;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use App\Models\SaleReturnItem;
use App\Models\Setting;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Models\StockTransferItem;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;

class FullTestDataSeeder extends Seeder
{
    private array $medicinesData = [
        // Analgesic & Antipyretic
        ['name' => 'Napa', 'generic' => 'Paracetamol', 'type' => 'Tablet', 'strength' => '500mg', 'category' => 'Analgesic & Antipyretic', 'manufacturer' => 'Beximco Pharmaceuticals Ltd.', 'unit' => 'Strip', 'purchase' => 18.00, 'sale' => 25.00, 'vat' => 0],
        ['name' => 'Napa Extra', 'generic' => 'Paracetamol', 'type' => 'Tablet', 'strength' => '500mg + 65mg Caffeine', 'category' => 'Analgesic & Antipyretic', 'manufacturer' => 'Beximco Pharmaceuticals Ltd.', 'unit' => 'Strip', 'purchase' => 25.00, 'sale' => 35.00, 'vat' => 0],
        ['name' => 'Maxmol', 'generic' => 'Paracetamol', 'type' => 'Tablet', 'strength' => '500mg', 'category' => 'Analgesic & Antipyretic', 'manufacturer' => 'Square Pharmaceuticals PLC', 'unit' => 'Strip', 'purchase' => 16.00, 'sale' => 22.00, 'vat' => 0],
        ['name' => 'Brufen', 'generic' => 'Ibuprofen', 'type' => 'Tablet', 'strength' => '400mg', 'category' => 'NSAID / Pain & Inflammation', 'manufacturer' => 'ACI Limited', 'unit' => 'Strip', 'purchase' => 20.00, 'sale' => 28.00, 'vat' => 0],
        ['name' => 'Naprox', 'generic' => 'Naproxen', 'type' => 'Tablet', 'strength' => '500mg', 'category' => 'NSAID / Pain & Inflammation', 'manufacturer' => 'Square Pharmaceuticals PLC', 'unit' => 'Strip', 'purchase' => 30.00, 'sale' => 42.00, 'vat' => 0],
        ['name' => 'Voltaren', 'generic' => 'Diclofenac Sodium', 'type' => 'Tablet', 'strength' => '50mg', 'category' => 'NSAID / Pain & Inflammation', 'manufacturer' => 'Novartis (Bangladesh) Ltd.', 'unit' => 'Strip', 'purchase' => 22.00, 'sale' => 32.00, 'vat' => 0],

        // Antibiotic
        ['name' => 'Amoxil', 'generic' => 'Amoxicillin', 'type' => 'Capsule', 'strength' => '500mg', 'category' => 'Antibiotic', 'manufacturer' => 'GlaxoSmithKline Bangladesh Ltd.', 'unit' => 'Strip', 'purchase' => 45.00, 'sale' => 65.00, 'vat' => 0],
        ['name' => 'Augmentin', 'generic' => 'Amoxicillin + Clavulanic Acid', 'type' => 'Tablet', 'strength' => '625mg', 'category' => 'Antibiotic', 'manufacturer' => 'GlaxoSmithKline Bangladesh Ltd.', 'unit' => 'Strip', 'purchase' => 120.00, 'sale' => 165.00, 'vat' => 0],
        ['name' => 'Azithral', 'generic' => 'Azithromycin', 'type' => 'Tablet', 'strength' => '500mg', 'category' => 'Antibiotic', 'manufacturer' => 'Alembic Pharmaceuticals Ltd.', 'unit' => 'Strip', 'purchase' => 55.00, 'sale' => 78.00, 'vat' => 0],
        ['name' => 'Ciprocin', 'generic' => 'Ciprofloxacin', 'type' => 'Tablet', 'strength' => '500mg', 'category' => 'Antibiotic', 'manufacturer' => 'Beximco Pharmaceuticals Ltd.', 'unit' => 'Strip', 'purchase' => 40.00, 'sale' => 58.00, 'vat' => 0],
        ['name' => 'Levoquin', 'generic' => 'Levofloxacin', 'type' => 'Tablet', 'strength' => '500mg', 'category' => 'Antibiotic', 'manufacturer' => 'Square Pharmaceuticals PLC', 'unit' => 'Strip', 'purchase' => 65.00, 'sale' => 90.00, 'vat' => 0],
        ['name' => 'Taxim O', 'generic' => 'Cefixime', 'type' => 'Capsule', 'strength' => '200mg', 'category' => 'Antibiotic', 'manufacturer' => 'ACME Laboratories Ltd.', 'unit' => 'Strip', 'purchase' => 80.00, 'sale' => 110.00, 'vat' => 0],
        ['name' => 'Cef-3', 'generic' => 'Cefuroxime Axetil', 'type' => 'Tablet', 'strength' => '500mg', 'category' => 'Antibiotic', 'manufacturer' => 'Square Pharmaceuticals PLC', 'unit' => 'Strip', 'purchase' => 95.00, 'sale' => 135.00, 'vat' => 0],
        ['name' => 'Metronidazole BV', 'generic' => 'Metronidazole', 'type' => 'Tablet', 'strength' => '400mg', 'category' => 'Antibiotic', 'manufacturer' => 'General Pharmaceuticals Ltd.', 'unit' => 'Strip', 'purchase' => 12.00, 'sale' => 18.00, 'vat' => 0],

        // Antacid & Anti-ulcerant
        ['name' => 'Seclo', 'generic' => 'Omeprazole', 'type' => 'Capsule', 'strength' => '20mg', 'category' => 'Antacid & Anti-ulcerant', 'manufacturer' => 'Square Pharmaceuticals PLC', 'unit' => 'Strip', 'purchase' => 35.00, 'sale' => 50.00, 'vat' => 0],
        ['name' => 'Nexium', 'generic' => 'Esomeprazole', 'type' => 'Capsule', 'strength' => '40mg', 'category' => 'Antacid & Anti-ulcerant', 'manufacturer' => 'AstraZeneca', 'unit' => 'Strip', 'purchase' => 85.00, 'sale' => 120.00, 'vat' => 0],
        ['name' => 'Pantodac', 'generic' => 'Pantoprazole', 'type' => 'Tablet', 'strength' => '40mg', 'category' => 'Antacid & Anti-ulcerant', 'manufacturer' => 'Incepta Pharmaceuticals Ltd.', 'unit' => 'Strip', 'purchase' => 40.00, 'sale' => 55.00, 'vat' => 0],
        ['name' => 'Rabecid', 'generic' => 'Rabeprazole', 'type' => 'Tablet', 'strength' => '20mg', 'category' => 'Antacid & Anti-ulcerant', 'manufacturer' => 'Incepta Pharmaceuticals Ltd.', 'unit' => 'Strip', 'purchase' => 45.00, 'sale' => 62.00, 'vat' => 0],
        ['name' => 'Rantac', 'generic' => 'Ranitidine', 'type' => 'Tablet', 'strength' => '150mg', 'category' => 'Antacid & Anti-ulcerant', 'manufacturer' => 'ACI Limited', 'unit' => 'Strip', 'purchase' => 15.00, 'sale' => 22.00, 'vat' => 0],

        // Antidiabetic
        ['name' => 'Glysup', 'generic' => 'Metformin Hydrochloride', 'type' => 'Tablet', 'strength' => '500mg', 'category' => 'Antidiabetic', 'manufacturer' => 'Sun Pharma (Bangladesh)', 'unit' => 'Strip', 'purchase' => 20.00, 'sale' => 30.00, 'vat' => 0],
        ['name' => 'Glimid', 'generic' => 'Glimepiride', 'type' => 'Tablet', 'strength' => '2mg', 'category' => 'Antidiabetic', 'manufacturer' => 'Eskayef Pharmaceuticals Ltd.', 'unit' => 'Strip', 'purchase' => 35.00, 'sale' => 50.00, 'vat' => 0],
        ['name' => 'Gliclazide MR', 'generic' => 'Gliclazide', 'type' => 'Tablet', 'strength' => '80mg', 'category' => 'Antidiabetic', 'manufacturer' => 'Beximco Pharmaceuticals Ltd.', 'unit' => 'Strip', 'purchase' => 25.00, 'sale' => 38.00, 'vat' => 0],
        ['name' => 'Januvia', 'generic' => 'Sitagliptin', 'type' => 'Tablet', 'strength' => '100mg', 'category' => 'Antidiabetic', 'manufacturer' => 'MSD (Merck)', 'unit' => 'Strip', 'purchase' => 180.00, 'sale' => 250.00, 'vat' => 0],

        // Antihypertensive & Cardiac
        ['name' => 'Amlodac', 'generic' => 'Amlodipine Besylate', 'type' => 'Tablet', 'strength' => '5mg', 'category' => 'Antihypertensive & Cardiac', 'manufacturer' => 'Cipla (Bangladesh)', 'unit' => 'Strip', 'purchase' => 22.00, 'sale' => 32.00, 'vat' => 0],
        ['name' => 'Atoris', 'generic' => 'Atorvastatin', 'type' => 'Tablet', 'strength' => '20mg', 'category' => 'Antihypertensive & Cardiac', 'manufacturer' => 'Ranbaxy (Bangladesh)', 'unit' => 'Strip', 'purchase' => 45.00, 'sale' => 65.00, 'vat' => 0],
        ['name' => 'Rosuvas', 'generic' => 'Rosuvastatin', 'type' => 'Tablet', 'strength' => '10mg', 'category' => 'Antihypertensive & Cardiac', 'manufacturer' => 'Sun Pharma (Bangladesh)', 'unit' => 'Strip', 'purchase' => 55.00, 'sale' => 78.00, 'vat' => 0],
        ['name' => 'Lozartan', 'generic' => 'Losartan Potassium', 'type' => 'Tablet', 'strength' => '50mg', 'category' => 'Antihypertensive & Cardiac', 'manufacturer' => 'Square Pharmaceuticals PLC', 'unit' => 'Strip', 'purchase' => 40.00, 'sale' => 58.00, 'vat' => 0],
        ['name' => 'Tebis', 'generic' => 'Telmisartan', 'type' => 'Tablet', 'strength' => '40mg', 'category' => 'Antihypertensive & Cardiac', 'manufacturer' => 'Incepta Pharmaceuticals Ltd.', 'unit' => 'Strip', 'purchase' => 50.00, 'sale' => 72.00, 'vat' => 0],
        ['name' => 'Bisocor', 'generic' => 'Bisoprolol Fumarate', 'type' => 'Tablet', 'strength' => '5mg', 'category' => 'Antihypertensive & Cardiac', 'manufacturer' => 'Eskayef Pharmaceuticals Ltd.', 'unit' => 'Strip', 'purchase' => 30.00, 'sale' => 42.00, 'vat' => 0],
        ['name' => 'Tenormin', 'generic' => 'Atenolol', 'type' => 'Tablet', 'strength' => '50mg', 'category' => 'Antihypertensive & Cardiac', 'manufacturer' => 'AstraZeneca', 'unit' => 'Strip', 'purchase' => 28.00, 'sale' => 40.00, 'vat' => 0],

        // Antihistamine & Anti-allergic
        ['name' => 'Cetirizine BV', 'generic' => 'Cetirizine Hydrochloride', 'type' => 'Tablet', 'strength' => '10mg', 'category' => 'Antihistamine & Anti-allergic', 'manufacturer' => 'Beximco Pharmaceuticals Ltd.', 'unit' => 'Strip', 'purchase' => 15.00, 'sale' => 22.00, 'vat' => 0],
        ['name' => 'Fexo', 'generic' => 'Fexofenadine', 'type' => 'Tablet', 'strength' => '120mg', 'category' => 'Antihistamine & Anti-allergic', 'manufacturer' => 'Sanofi Bangladesh Ltd.', 'unit' => 'Strip', 'purchase' => 40.00, 'sale' => 55.00, 'vat' => 0],
        ['name' => 'Loratan', 'generic' => 'Loratadine', 'type' => 'Tablet', 'strength' => '10mg', 'category' => 'Antihistamine & Anti-allergic', 'manufacturer' => 'Square Pharmaceuticals PLC', 'unit' => 'Strip', 'purchase' => 20.00, 'sale' => 30.00, 'vat' => 0],
        ['name' => 'Montair', 'generic' => 'Montelukast Sodium', 'type' => 'Tablet', 'strength' => '10mg', 'category' => 'Antihistamine & Anti-allergic', 'manufacturer' => 'Cipla (Bangladesh)', 'unit' => 'Strip', 'purchase' => 45.00, 'sale' => 62.00, 'vat' => 0],

        // Gastrointestinal
        ['name' => 'Motilium', 'generic' => 'Domperidone', 'type' => 'Tablet', 'strength' => '10mg', 'category' => 'Gastrointestinal', 'manufacturer' => 'Astellas Pharma', 'unit' => 'Strip', 'purchase' => 25.00, 'sale' => 35.00, 'vat' => 0],
        ['name' => 'Emeset', 'generic' => 'Ondansetron', 'type' => 'Tablet', 'strength' => '4mg', 'category' => 'Gastrointestinal', 'manufacturer' => 'Cipla (Bangladesh)', 'unit' => 'Strip', 'purchase' => 35.00, 'sale' => 50.00, 'vat' => 0],
        ['name' => 'Loperax', 'generic' => 'Loperamide', 'type' => 'Capsule', 'strength' => '2mg', 'category' => 'Gastrointestinal', 'manufacturer' => 'Square Pharmaceuticals PLC', 'unit' => 'Strip', 'purchase' => 22.00, 'sale' => 32.00, 'vat' => 0],

        // Vitamin & Mineral Supplement
        ['name' => 'Becosules', 'generic' => 'Vitamin B Complex', 'type' => 'Capsule', 'strength' => 'B-complex + Vitamin C', 'category' => 'Vitamin & Mineral Supplement', 'manufacturer' => 'Pfizer (Bangladesh)', 'unit' => 'Strip', 'purchase' => 25.00, 'sale' => 35.00, 'vat' => 0],
        ['name' => 'Supradyn', 'generic' => 'Multivitamins & Minerals', 'type' => 'Tablet', 'strength' => 'Multivitamin', 'category' => 'Vitamin & Mineral Supplement', 'manufacturer' => 'Bayer (Bangladesh)', 'unit' => 'Strip', 'purchase' => 40.00, 'sale' => 55.00, 'vat' => 0],
        ['name' => 'Calcirol', 'generic' => 'Calcium Carbonate + Vitamin D3', 'type' => 'Capsule', 'strength' => '500mg + 250IU', 'category' => 'Vitamin & Mineral Supplement', 'manufacturer' => 'Alembic Pharmaceuticals Ltd.', 'unit' => 'Strip', 'purchase' => 55.00, 'sale' => 75.00, 'vat' => 0],
        ['name' => 'Fefol', 'generic' => 'Ferrous Fumarate', 'type' => 'Capsule', 'strength' => '150mg + Folic Acid', 'category' => 'Vitamin & Mineral Supplement', 'manufacturer' => 'GlaxoSmithKline Bangladesh Ltd.', 'unit' => 'Strip', 'purchase' => 30.00, 'sale' => 42.00, 'vat' => 0],

        // Respiratory & Anti-asthmatic
        ['name' => 'Salbutamol Inhaler', 'generic' => 'Salbutamol', 'type' => 'Inhaler', 'strength' => '100mcg', 'category' => 'Respiratory & Anti-asthmatic', 'manufacturer' => 'GlaxoSmithKline Bangladesh Ltd.', 'unit' => 'Piece', 'purchase' => 120.00, 'sale' => 170.00, 'vat' => 0],
        ['name' => 'Theo-Asthalin', 'generic' => 'Theophylline', 'type' => 'Tablet', 'strength' => '400mg', 'category' => 'Respiratory & Anti-asthmatic', 'manufacturer' => 'Cipla (Bangladesh)', 'unit' => 'Strip', 'purchase' => 30.00, 'sale' => 42.00, 'vat' => 0],

        // Antifungal
        ['name' => 'Clotrimazole Cream', 'generic' => 'Clotrimazole', 'type' => 'Cream', 'strength' => '1%', 'category' => 'Antifungal', 'manufacturer' => 'Beximco Pharmaceuticals Ltd.', 'unit' => 'Tube', 'purchase' => 20.00, 'sale' => 30.00, 'vat' => 0],
        ['name' => 'Diflucan', 'generic' => 'Fluconazole', 'type' => 'Capsule', 'strength' => '150mg', 'category' => 'Antifungal', 'manufacturer' => 'Pfizer (Bangladesh)', 'unit' => 'Piece', 'purchase' => 35.00, 'sale' => 50.00, 'vat' => 0],
        ['name' => 'Nizoral', 'generic' => 'Ketoconazole', 'type' => 'Tablet', 'strength' => '200mg', 'category' => 'Antifungal', 'manufacturer' => 'Janssen (Bangladesh)', 'unit' => 'Strip', 'purchase' => 40.00, 'sale' => 55.00, 'vat' => 0],

        // Anti-parasitic & Anthelmintic
        ['name' => 'Albendazole BV', 'generic' => 'Albendazole', 'type' => 'Tablet', 'strength' => '400mg', 'category' => 'Anti-parasitic & Anthelmintic', 'manufacturer' => 'Beximco Pharmaceuticals Ltd.', 'unit' => 'Piece', 'purchase' => 8.00, 'sale' => 12.00, 'vat' => 0],
        ['name' => 'Vermox', 'generic' => 'Mebendazole', 'type' => 'Tablet', 'strength' => '100mg', 'category' => 'Anti-parasitic & Anthelmintic', 'manufacturer' => 'Johnson & Johnson', 'unit' => 'Strip', 'purchase' => 25.00, 'sale' => 35.00, 'vat' => 0],

        // Ophthalmic (Eye Care)
        ['name' => 'Tobradex Eye Drop', 'generic' => 'Dexamethasone', 'type' => 'Drops', 'strength' => '0.3mg/ml + 1mg/ml', 'category' => 'Ophthalmic (Eye Care)', 'manufacturer' => 'Alcon (Bangladesh)', 'unit' => 'Piece', 'purchase' => 65.00, 'sale' => 90.00, 'vat' => 0],

        // Oral Care
        ['name' => 'Hexidine', 'generic' => 'Chlorhexidine', 'type' => 'Solution', 'strength' => '0.2%', 'category' => 'Oral Care', 'manufacturer' => 'ACI Limited', 'unit' => 'Bottle', 'purchase' => 35.00, 'sale' => 50.00, 'vat' => 0],

        // Pediatric Care
        ['name' => 'Napa Susp', 'generic' => 'Paracetamol', 'type' => 'Suspension', 'strength' => '120mg/5ml', 'category' => 'Pediatric Care', 'manufacturer' => 'Beximco Pharmaceuticals Ltd.', 'unit' => 'Bottle', 'purchase' => 22.00, 'sale' => 32.00, 'vat' => 0],
        ['name' => 'Omez DSR', 'generic' => 'Esomeprazole Magnesium', 'type' => 'Capsule', 'strength' => '30mg', 'category' => 'Antacid & Anti-ulcerant', 'manufacturer' => 'Dr. Reddy\'s (Bangladesh)', 'unit' => 'Strip', 'purchase' => 60.00, 'sale' => 85.00, 'vat' => 0],
    ];

    public function run(): void
    {
        Pharmacy::all()->each(function (Pharmacy $pharmacy) {
            runForPharmacy($pharmacy, function () use ($pharmacy) {
                app(PermissionRegistrar::class)->setPermissionsTeamId($pharmacy->id);

                $location = Location::where('pharmacy_id', $pharmacy->id)->where('is_default', true)->first()
                    ?? Location::where('pharmacy_id', $pharmacy->id)->first();

                if (! $location) {
                    return;
                }

                $admin = User::where('pharmacy_id', $pharmacy->id)->first();
                $suppliers = Supplier::where('pharmacy_id', $pharmacy->id)->get();
                $customers = Customer::where('pharmacy_id', $pharmacy->id)->get();
                $expenseCategories = ExpenseCategory::where('pharmacy_id', $pharmacy->id)->get();
                $cashAccounts = CashAccount::where('pharmacy_id', $pharmacy->id)->get();

                if ($suppliers->isEmpty() || $customers->isEmpty()) {
                    return;
                }

                $medicines = $this->seedMedicines($pharmacy);
                $batches = $this->seedBatches($pharmacy, $location, $suppliers, $medicines);
                $purchases = $this->seedPurchases($pharmacy, $location, $suppliers, $admin, $medicines, $batches);
                $sales = $this->seedSales($pharmacy, $location, $customers, $admin, $medicines, $batches);
                $this->seedSaleReturns($pharmacy, $sales, $customers, $medicines, $batches);
                $this->seedPurchaseReturns($pharmacy, $purchases, $suppliers, $medicines, $batches);
                $this->seedPayments($pharmacy, $purchases, $sales, $suppliers, $customers);
                $this->seedExpenses($pharmacy, $expenseCategories, $admin);
                $this->seedStockTransfers($pharmacy, $location, $medicines, $admin);
                $this->seedDamagedMedicines($pharmacy, $location, $admin, $medicines, $batches);
                $this->seedNotifications($pharmacy, $admin);
                $this->seedSettings($pharmacy);
                $this->seedActivityLogs($pharmacy, $admin);
                $this->seedAccountTransactions($pharmacy, $cashAccounts);
            });
        });
    }

    private function seedMedicines(Pharmacy $pharmacy): \Illuminate\Support\Collection
    {
        $categoryCache = [];
        $manufacturerCache = [];
        $genericCache = [];
        $typeCache = [];
        $unitCache = [];

        $medicines = collect();

        foreach ($this->medicinesData as $i => $data) {
            $categoryCache[$data['category']] ??= Category::firstOrCreate(['name' => $data['category']], ['status' => true]);
            $manufacturerCache[$data['manufacturer']] ??= Manufacturer::firstOrCreate(['name' => $data['manufacturer']], ['status' => true]);
            $genericCache[$data['generic']] ??= Generic::firstOrCreate(['name' => $data['generic']]);
            $typeCache[$data['type']] ??= MedicineType::firstOrCreate(['name' => $data['type']]);
            $unitCache[$data['unit']] ??= Unit::firstOrCreate(['name' => $data['unit']], ['short_name' => strtoupper(substr($data['unit'], 0, 3))]);

            $barcode = 'BC-'.str_pad((string) ($pharmacy->id * 10000 + $i + 1), 8, '0', STR_PAD_LEFT);

            $medicine = Medicine::firstOrCreate(
                ['pharmacy_id' => $pharmacy->id, 'barcode' => $barcode],
                [
                    'category_id' => $categoryCache[$data['category']]->id,
                    'manufacturer_id' => $manufacturerCache[$data['manufacturer']]->id,
                    'generic_id' => $genericCache[$data['generic']]->id,
                    'medicine_type_id' => $typeCache[$data['type']]->id,
                    'unit_id' => $unitCache[$data['unit']]->id,
                    'medicine_name' => $data['name'],
                    'strength' => $data['strength'],
                    'purchase_price' => $data['purchase'],
                    'sale_price' => $data['sale'],
                    'minimum_stock' => rand(10, 50),
                    'vat' => $data['vat'],
                    'status' => true,
                    'description' => $data['generic'].' - '.$data['strength'],
                ]
            );

            $medicines->push($medicine);
        }

        return $medicines;
    }

    private function seedBatches(Pharmacy $pharmacy, Location $location, $suppliers, $medicines): \Illuminate\Support\Collection
    {
        $batches = collect();

        foreach ($medicines as $medicine) {
            $batchCount = rand(1, 2);

            for ($b = 0; $b < $batchCount; $b++) {
                $qty = rand(50, 300);
                $expiryMonths = rand(6, 24);

                $batch = MedicineBatch::create([
                    'pharmacy_id' => $pharmacy->id,
                    'location_id' => $location->id,
                    'medicine_id' => $medicine->id,
                    'batch_no' => 'B-'.Str::random(4).'-'.date('ym', strtotime("+$expiryMonths months")),
                    'purchase_price' => $medicine->purchase_price,
                    'sale_price' => $medicine->sale_price,
                    'quantity' => $qty,
                    'remaining_qty' => $qty,
                    'expiry_date' => now()->addMonths($expiryMonths),
                    'manufacture_date' => now()->subMonths(rand(1, 6)),
                    'supplier_id' => $suppliers->random()->id,
                ]);

                $batches->push($batch);
            }
        }

        return $batches;
    }

    private function seedPurchases(Pharmacy $pharmacy, Location $location, $suppliers, $admin, $medicines, $batches): \Illuminate\Support\Collection
    {
        $purchases = collect();

        for ($i = 0; $i < 15; $i++) {
            $purchase = Purchase::create([
                'pharmacy_id' => $pharmacy->id,
                'location_id' => $location->id,
                'supplier_id' => $suppliers->random()->id,
                'purchase_date' => now()->subDays(rand(1, 60)),
                'subtotal' => 0,
                'discount' => 0,
                'vat' => 0,
                'tax' => 0,
                'total' => 0,
                'paid' => 0,
                'due' => 0,
                'note' => 'Test purchase #'.($i + 1),
                'created_by' => $admin?->id,
            ]);

            $itemCount = rand(3, 6);
            $subtotal = 0;
            $selectedBatches = $batches->random(min($itemCount, $batches->count()));

            foreach ($selectedBatches as $batch) {
                $qty = rand(10, 50);
                $total = round($batch->purchase_price * $qty, 2);
                $subtotal += $total;

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'medicine_batch_id' => $batch->id,
                    'medicine_id' => $batch->medicine_id,
                    'qty' => $qty,
                    'purchase_price' => $batch->purchase_price,
                    'sale_price' => $batch->sale_price,
                    'total' => $total,
                ]);

                StockMovement::create([
                    'pharmacy_id' => $pharmacy->id,
                    'location_id' => $location->id,
                    'medicine_id' => $batch->medicine_id,
                    'batch_id' => $batch->id,
                    'type' => StockMovement::TYPE_PURCHASE,
                    'qty' => $qty,
                    'reference' => 'Purchase',
                    'reference_id' => $purchase->id,
                ]);
            }

            $discount = round($subtotal * (mt_rand(0, 50) / 1000), 2);
            $vat = round(($subtotal - $discount) * 0.075, 2);
            $total = round($subtotal - $discount + $vat, 2);
            $paid = round($total * (mt_rand(50, 100) / 100), 2);

            $purchase->update([
                'subtotal' => $subtotal,
                'discount' => $discount,
                'vat' => $vat,
                'total' => $total,
                'paid' => $paid,
                'due' => round($total - $paid, 2),
            ]);

            $purchases->push($purchase);
        }

        return $purchases;
    }

    private function seedSales(Pharmacy $pharmacy, Location $location, $customers, $admin, $medicines, $batches): \Illuminate\Support\Collection
    {
        $sales = collect();
        $paymentMethods = ['cash', 'bKash', 'Nagad', 'card'];

        for ($i = 0; $i < 25; $i++) {
            $sale = Sale::create([
                'pharmacy_id' => $pharmacy->id,
                'location_id' => $location->id,
                'customer_id' => rand(1, 3) > 1 ? $customers->random()->id : null,
                'sale_date' => now()->subDays(rand(0, 30)),
                'subtotal' => 0,
                'discount' => 0,
                'vat' => 0,
                'total' => 0,
                'paid' => 0,
                'due' => 0,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'created_by' => $admin?->id,
                'note' => null,
            ]);

            $itemCount = rand(1, 4);
            $subtotal = 0;
            $availableBatches = $batches->where('remaining_qty', '>', 0)->random(min($itemCount, $batches->where('remaining_qty', '>', 0)->count()));

            foreach ($availableBatches as $batch) {
                $maxQty = min($batch->remaining_qty, rand(1, 5));
                $qty = max(1, $maxQty);
                $total = round($batch->sale_price * $qty, 2);
                $subtotal += $total;

                $batch->decrement('remaining_qty', $qty);

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'medicine_batch_id' => $batch->id,
                    'medicine_id' => $batch->medicine_id,
                    'qty' => $qty,
                    'price' => $batch->sale_price,
                    'discount' => 0,
                    'total' => $total,
                ]);

                StockMovement::create([
                    'pharmacy_id' => $pharmacy->id,
                    'location_id' => $location->id,
                    'medicine_id' => $batch->medicine_id,
                    'batch_id' => $batch->id,
                    'type' => StockMovement::TYPE_SALE,
                    'qty' => $qty,
                    'reference' => 'Sale',
                    'reference_id' => $sale->id,
                ]);
            }

            $discount = round($subtotal * (mt_rand(0, 30) / 1000), 2);
            $vat = round(($subtotal - $discount) * 0.075, 2);
            $total = round($subtotal - $discount + $vat, 2);
            $paid = round($total * (mt_rand(60, 100) / 100), 2);

            $sale->update([
                'subtotal' => $subtotal,
                'discount' => $discount,
                'vat' => $vat,
                'total' => $total,
                'paid' => $paid,
                'due' => round($total - $paid, 2),
            ]);

            $sales->push($sale);
        }

        return $sales;
    }

    private function seedSaleReturns(Pharmacy $pharmacy, $sales, $customers, $medicines, $batches): void
    {
        $returnableSales = $sales->filter(fn ($s) => $s->items()->count() > 0)->take(3);

        foreach ($returnableSales as $sale) {
            $saleItems = $sale->items;
            if ($saleItems->isEmpty()) {
                continue;
            }

            $item = $saleItems->random();
            $returnQty = min($item->qty, rand(1, 3));

            $return = SaleReturn::create([
                'pharmacy_id' => $pharmacy->id,
                'sale_id' => $sale->id,
                'customer_id' => $sale->customer_id,
                'return_date' => now()->subDays(rand(0, 5)),
                'refund_amount' => round($item->price * $returnQty, 2),
                'reason' => collect(['Wrong medicine', 'Side effects', 'Doctor changed prescription', 'Expired batch'])->random(),
            ]);

            SaleReturnItem::create([
                'sale_return_id' => $return->id,
                'medicine_batch_id' => $item->medicine_batch_id,
                'qty' => $returnQty,
                'price' => $item->price,
            ]);

            $batch = MedicineBatch::find($item->medicine_batch_id);
            if ($batch) {
                $batch->increment('remaining_qty', $returnQty);

                StockMovement::create([
                    'pharmacy_id' => $pharmacy->id,
                    'location_id' => $batch->location_id,
                    'medicine_id' => $item->medicine_id,
                    'batch_id' => $batch->id,
                    'type' => StockMovement::TYPE_RETURN,
                    'qty' => $returnQty,
                    'reference' => 'SaleReturn',
                    'reference_id' => $return->id,
                ]);
            }
        }
    }

    private function seedPurchaseReturns(Pharmacy $pharmacy, $purchases, $suppliers, $medicines, $batches): void
    {
        $returnablePurchases = $purchases->take(2);

        foreach ($returnablePurchases as $purchase) {
            $purchaseItems = $purchase->items;
            if ($purchaseItems->isEmpty()) {
                continue;
            }

            $item = $purchaseItems->random();
            $returnQty = min($item->qty, rand(1, 10));

            $return = PurchaseReturn::create([
                'pharmacy_id' => $pharmacy->id,
                'purchase_id' => $purchase->id,
                'supplier_id' => $purchase->supplier_id,
                'return_date' => now()->subDays(rand(0, 10)),
                'amount' => round($item->purchase_price * $returnQty, 2),
            ]);

            PurchaseReturnItem::create([
                'purchase_return_id' => $return->id,
                'medicine_batch_id' => $item->medicine_batch_id,
                'qty' => $returnQty,
                'price' => $item->purchase_price,
            ]);

            $batch = MedicineBatch::find($item->medicine_batch_id);
            if ($batch) {
                $batch->decrement('remaining_qty', $returnQty);

                StockMovement::create([
                    'pharmacy_id' => $pharmacy->id,
                    'location_id' => $batch->location_id,
                    'medicine_id' => $item->medicine_id,
                    'batch_id' => $batch->id,
                    'type' => StockMovement::TYPE_RETURN,
                    'qty' => -$returnQty,
                    'reference' => 'PurchaseReturn',
                    'reference_id' => $return->id,
                ]);
            }
        }
    }

    private function seedPayments(Pharmacy $pharmacy, $purchases, $sales, $suppliers, $customers): void
    {
        $methods = ['cash', 'bKash', 'Nagad', 'bank_transfer'];

        foreach ($sales->random(15) as $sale) {
            if ($sale->due <= 0) {
                continue;
            }

            $payAmount = min($sale->due, round($sale->due * (mt_rand(30, 100) / 100), 2));

            Payment::create([
                'pharmacy_id' => $pharmacy->id,
                'sale_id' => $sale->id,
                'customer_id' => $sale->customer_id,
                'amount' => $payAmount,
                'payment_method' => $methods[array_rand($methods)],
                'transaction_no' => 'TXN-'.Str::random(8),
                'payment_date' => now()->subDays(rand(0, 15)),
                'note' => 'Test payment for sale',
            ]);
        }

        foreach ($purchases->random(8) as $purchase) {
            if ($purchase->due <= 0) {
                continue;
            }

            $payAmount = min($purchase->due, round($purchase->due * (mt_rand(30, 100) / 100), 2));

            Payment::create([
                'pharmacy_id' => $pharmacy->id,
                'purchase_id' => $purchase->id,
                'supplier_id' => $purchase->supplier_id,
                'amount' => $payAmount,
                'payment_method' => $methods[array_rand($methods)],
                'transaction_no' => 'TXN-'.Str::random(8),
                'payment_date' => now()->subDays(rand(0, 15)),
                'note' => 'Test payment for purchase',
            ]);
        }
    }

    private function seedExpenses(Pharmacy $pharmacy, $expenseCategories, $admin): void
    {
        $expenseData = [
            'Shop Rent' => ['amounts' => [15000, 20000, 25000], 'desc' => 'Monthly shop rent'],
            'Electricity Bill' => ['amounts' => [2000, 3500, 5000], 'desc' => 'Monthly electricity bill'],
            'Staff Salary' => ['amounts' => [12000, 15000, 18000], 'desc' => 'Monthly staff salary'],
            'Transportation' => ['amounts' => [500, 1000, 2000], 'desc' => 'Delivery transportation cost'],
            'Internet & Telephone Bill' => ['amounts' => [1000, 1500, 2000], 'desc' => 'Monthly internet bill'],
            'Maintenance & Repair' => ['amounts' => [500, 1500, 3000], 'desc' => 'Shop maintenance'],
            'Office Supplies' => ['amounts' => [200, 500, 1000], 'desc' => 'Printer paper, pens, etc.'],
        ];

        foreach ($expenseData as $catName => $info) {
            $category = $expenseCategories->firstWhere('name', $catName);
            if (! $category) {
                continue;
            }

            $count = rand(1, 3);
            for ($i = 0; $i < $count; $i++) {
                Expense::create([
                    'pharmacy_id' => $pharmacy->id,
                    'expense_category_id' => $category->id,
                    'amount' => $info['amounts'][array_rand($info['amounts'])],
                    'expense_date' => now()->subDays(rand(1, 60)),
                    'description' => $info['desc'],
                    'created_by' => $admin?->id,
                ]);
            }
        }
    }

    private function seedStockTransfers(Pharmacy $pharmacy, Location $location, $medicines, $admin): void
    {
        $otherLocations = Location::where('pharmacy_id', $pharmacy->id)
            ->where('id', '!=', $location->id)
            ->get();

        if ($otherLocations->isEmpty()) {
            return;
        }

        for ($i = 0; $i < 4; $i++) {
            $fromLoc = $i % 2 === 0 ? $location : $otherLocations->random();
            $toLoc = $fromLoc->id === $location->id ? $otherLocations->random() : $location;

            $transfer = StockTransfer::create([
                'pharmacy_id' => $pharmacy->id,
                'from_location_id' => $fromLoc->id,
                'to_location_id' => $toLoc->id,
                'transfer_date' => now()->subDays(rand(1, 30)),
                'note' => 'Inter-branch transfer #'.($i + 1),
                'created_by' => $admin?->id,
            ]);

            $itemCount = rand(1, 3);
            $selectedMedicines = $medicines->random(min($itemCount, $medicines->count()));

            foreach ($selectedMedicines as $medicine) {
                $qty = rand(5, 20);

                StockTransferItem::create([
                    'stock_transfer_id' => $transfer->id,
                    'medicine_id' => $medicine->id,
                    'qty' => $qty,
                ]);

                StockMovement::create([
                    'pharmacy_id' => $pharmacy->id,
                    'location_id' => $fromLoc->id,
                    'medicine_id' => $medicine->id,
                    'batch_id' => null,
                    'type' => StockMovement::TYPE_TRANSFER,
                    'qty' => -$qty,
                    'reference' => 'StockTransfer',
                    'reference_id' => $transfer->id,
                ]);

                StockMovement::create([
                    'pharmacy_id' => $pharmacy->id,
                    'location_id' => $toLoc->id,
                    'medicine_id' => $medicine->id,
                    'batch_id' => null,
                    'type' => StockMovement::TYPE_TRANSFER,
                    'qty' => $qty,
                    'reference' => 'StockTransfer',
                    'reference_id' => $transfer->id,
                ]);
            }
        }
    }

    private function seedDamagedMedicines(Pharmacy $pharmacy, Location $location, $admin, $medicines, $batches): void
    {
        $reasons = [
            'Water damage during flooding',
            'Broken packaging during transport',
            'Expired stock found during audit',
            'Pest damage in storage',
            'Contamination during handling',
        ];

        $damagedBatches = $batches->where('remaining_qty', '>', 5)->random(min(5, $batches->where('remaining_qty', '>', 5)->count()));

        foreach ($damagedBatches as $batch) {
            $qty = min($batch->remaining_qty, rand(1, 5));

            DamagedMedicine::create([
                'pharmacy_id' => $pharmacy->id,
                'location_id' => $location->id,
                'medicine_batch_id' => $batch->id,
                'qty' => $qty,
                'reason' => $reasons[array_rand($reasons)],
                'created_by' => $admin?->id,
            ]);

            $batch->decrement('remaining_qty', $qty);

            StockMovement::create([
                'pharmacy_id' => $pharmacy->id,
                'location_id' => $location->id,
                'medicine_id' => $batch->medicine_id,
                'batch_id' => $batch->id,
                'type' => StockMovement::TYPE_DAMAGE,
                'qty' => -$qty,
                'reference' => 'DamagedMedicine',
                'reference_id' => $batch->id,
            ]);
        }
    }

    private function seedNotifications(Pharmacy $pharmacy, $admin): void
    {
        $notifications = [
            ['title' => 'Low Stock Alert', 'message' => 'Napa 500mg is below minimum stock level.', 'type' => 'warning'],
            ['title' => 'Expiry Warning', 'message' => 'Amoxil batch B-XK3M-2601 expires in 30 days.', 'type' => 'warning'],
            ['title' => 'New Purchase Received', 'message' => 'Purchase PINV-000001 has been received and verified.', 'type' => 'info'],
            ['title' => 'Payment Received', 'message' => 'Customer payment of ৳5,500 received via bKash.', 'type' => 'success'],
            ['title' => 'Stock Transfer Complete', 'message' => 'Transfer from Main Branch to Gulshan Branch completed.', 'type' => 'info'],
            ['title' => 'Daily Sales Summary', 'message' => 'Today\'s total sales: ৳45,200.00', 'type' => 'info'],
            ['title' => 'Damaged Stock Report', 'message' => '5 units of Seclo 20mg marked as damaged.', 'type' => 'warning'],
            ['title' => 'Supplier Due Reminder', 'message' => 'Outstanding due of ৳12,000 to Square Pharmaceuticals.', 'type' => 'warning'],
            ['title' => 'Monthly Expense Report', 'message' => 'Total expenses this month: ৳68,500.00', 'type' => 'info'],
            ['title' => 'License Renewal', 'message' => 'Pharmacy license expires in 45 days. Please renew.', 'type' => 'danger'],
        ];

        foreach ($notifications as $i => $data) {
            Notification::create([
                'pharmacy_id' => $pharmacy->id,
                'user_id' => $admin?->id,
                'title' => $data['title'],
                'message' => $data['message'],
                'type' => $data['type'],
                'is_read' => $i > 6,
            ]);
        }
    }

    private function seedSettings(Pharmacy $pharmacy): void
    {
        $settings = [
            'invoice_prefix' => 'INV',
            'purchase_invoice_prefix' => 'PINV',
            'currency_symbol' => '৳',
            'vat_rate' => '7.5',
            'low_stock_threshold' => '20',
            'expiry_warning_days' => '90',
            'receipt_footer' => 'Thank you for your purchase!',
            'pharmacy_hours' => '9:00 AM - 10:00 PM',
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(
                ['pharmacy_id' => $pharmacy->id, 'key' => $key],
                ['value' => $value]
            );
        }
    }

    private function seedActivityLogs(Pharmacy $pharmacy, $admin): void
    {
        $actions = [
            ['action' => 'created', 'table' => 'medicines'],
            ['action' => 'updated', 'table' => 'medicines'],
            ['action' => 'created', 'table' => 'purchases'],
            ['action' => 'created', 'table' => 'sales'],
            ['action' => 'created', 'table' => 'customers'],
            ['action' => 'updated', 'table' => 'suppliers'],
            ['action' => 'deleted', 'table' => 'damaged_medicines'],
            ['action' => 'created', 'table' => 'expenses'],
            ['action' => 'created', 'table' => 'stock_transfers'],
            ['action' => 'updated', 'table' => 'settings'],
        ];

        for ($i = 0; $i < 30; $i++) {
            $log = $actions[array_rand($actions)];

            ActivityLog::create([
                'pharmacy_id' => $pharmacy->id,
                'user_id' => $admin?->id,
                'action' => $log['action'],
                'table_name' => $log['table'],
                'record_id' => rand(1, 50),
                'ip' => '127.0.0.'.rand(1, 254),
                'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
            ]);
        }
    }

    private function seedAccountTransactions(Pharmacy $pharmacy, $cashAccounts): void
    {
        if ($cashAccounts->isEmpty()) {
            return;
        }

        $descriptions = [
            'Cash deposit from sales',
            'bKash payment received',
            'Nagad payment received',
            'Bank transfer to supplier',
            'Cash withdrawal for expenses',
            'Petty cash expense',
            'Monthly rent payment',
            'Salary disbursement',
        ];

        for ($i = 0; $i < 20; $i++) {
            $account = $cashAccounts->random();
            $isCredit = $i % 3 !== 0;
            $amount = round(rand(500, 15000), 2);

            AccountTransaction::create([
                'pharmacy_id' => $pharmacy->id,
                'cash_account_id' => $account->id,
                'type' => $isCredit ? 'credit' : 'debit',
                'credit' => $isCredit ? $amount : 0,
                'debit' => $isCredit ? 0 : $amount,
                'reference' => $descriptions[array_rand($descriptions)],
                'reference_id' => null,
                'transaction_date' => now()->subDays(rand(0, 30)),
            ]);

            $newBalance = $isCredit
                ? round($account->balance + $amount, 2)
                : round($account->balance - $amount, 2);

            $account->update(['balance' => max(0, $newBalance)]);
        }
    }
}
