<?php

namespace App\Support;

/**
 * Real-world reference data used by seeders/factories — genuine Bangladeshi pharmaceutical
 * manufacturers, real generic (INN) drug names, real therapeutic categories, etc. This is
 * curated reference data, not fabricated brand-level product records: seed data built from
 * this should never be presented as an authoritative real product/SKU registry.
 */
class PharmacyReferenceData
{
    /**
     * Real, well-known Bangladeshi pharmaceutical manufacturers.
     */
    public const MANUFACTURERS = [
        'Square Pharmaceuticals PLC',
        'Beximco Pharmaceuticals Ltd.',
        'Incepta Pharmaceuticals Ltd.',
        'Renata PLC',
        'ACI Limited',
        'Opsonin Pharma Ltd.',
        'Aristopharma Ltd.',
        'Eskayef Pharmaceuticals Ltd.',
        'Drug International Ltd.',
        'Healthcare Pharmaceuticals Ltd.',
        'ACME Laboratories Ltd.',
        'General Pharmaceuticals Ltd.',
        'Popular Pharmaceuticals Ltd.',
        'Radiant Pharmaceuticals Ltd.',
        'Orion Pharma Ltd.',
        'Navana Pharmaceuticals Ltd.',
        'Globe Pharmaceuticals Ltd.',
        'Sanofi Bangladesh Ltd.',
        'GlaxoSmithKline Bangladesh Ltd.',
        'Novartis (Bangladesh) Ltd.',
        'Ibn Sina Pharmaceutical Industry Ltd.',
        'Somatec Pharmaceuticals Ltd.',
        'Kumudini Pharma Ltd.',
        'Jayson Pharmaceuticals Ltd.',
    ];

    /**
     * Real generic (INN) drug names, spanning common therapeutic classes.
     */
    public const GENERICS = [
        'Paracetamol', 'Ibuprofen', 'Diclofenac Sodium', 'Aspirin', 'Naproxen',
        'Omeprazole', 'Esomeprazole', 'Pantoprazole', 'Rabeprazole', 'Ranitidine', 'Famotidine',
        'Amoxicillin', 'Amoxicillin + Clavulanic Acid', 'Azithromycin', 'Ciprofloxacin',
        'Levofloxacin', 'Cefixime', 'Cefuroxime Axetil', 'Cefradine', 'Clarithromycin',
        'Doxycycline', 'Metronidazole', 'Flucloxacillin',
        'Metformin Hydrochloride', 'Glimepiride', 'Gliclazide', 'Insulin Glargine', 'Sitagliptin',
        'Amlodipine Besylate', 'Atorvastatin', 'Rosuvastatin', 'Losartan Potassium',
        'Telmisartan', 'Bisoprolol Fumarate', 'Atenolol', 'Furosemide', 'Spironolactone',
        'Cetirizine Hydrochloride', 'Fexofenadine', 'Loratadine', 'Levocetirizine', 'Montelukast Sodium',
        'Salbutamol', 'Theophylline', 'Prednisolone', 'Dexamethasone',
        'Domperidone', 'Ondansetron', 'Esomeprazole Magnesium', 'Loperamide',
        'Multivitamins & Minerals', 'Calcium Carbonate + Vitamin D3', 'Ferrous Fumarate',
        'Folic Acid', 'Vitamin B Complex', 'Vitamin C (Ascorbic Acid)', 'Zinc Sulfate',
        'Oral Rehydration Salt (ORS)', 'Ivermectin', 'Albendazole', 'Mebendazole',
        'Clotrimazole', 'Fluconazole', 'Ketoconazole', 'Miconazole',
    ];

    /**
     * Real therapeutic/medicine categories used in pharmacy inventory classification.
     */
    public const CATEGORIES = [
        'Analgesic & Antipyretic',
        'Antibiotic',
        'Antacid & Anti-ulcerant',
        'Antihistamine & Anti-allergic',
        'Antidiabetic',
        'Antihypertensive & Cardiac',
        'Vitamin & Mineral Supplement',
        'Respiratory & Anti-asthmatic',
        'Gastrointestinal',
        'Dermatology (Skin Care)',
        'Ophthalmic (Eye Care)',
        'ENT (Ear, Nose & Throat)',
        'Anti-parasitic & Anthelmintic',
        'Hormone & Endocrine',
        'NSAID / Pain & Inflammation',
        'Sedative & Anti-anxiety',
        'Antifungal',
        'Antiviral',
        'Oral Care',
        'Contraceptive & Reproductive Health',
        'Pediatric Care',
    ];

    /**
     * Common medicine dosage forms.
     */
    public const MEDICINE_TYPES = ['Tablet', 'Capsule', 'Syrup', 'Injection', 'Cream', 'Ointment', 'Drops', 'Suspension', 'Inhaler'];

    /**
     * Common pharmacy stock units.
     */
    public const UNITS = [
        ['name' => 'Box', 'short_name' => 'Box'],
        ['name' => 'Strip', 'short_name' => 'Strp'],
        ['name' => 'Piece', 'short_name' => 'Pcs'],
        ['name' => 'Bottle', 'short_name' => 'Btl'],
        ['name' => 'Tube', 'short_name' => 'Tube'],
        ['name' => 'Vial', 'short_name' => 'Vial'],
    ];

    /**
     * Real Dhaka-area names, used for branch/location seed data.
     */
    public const LOCATIONS = [
        'Dhanmondi Branch', 'Gulshan Branch', 'Uttara Branch', 'Mirpur Branch',
        'Mohammadpur Branch', 'Banani Branch', 'Motijheel Branch', 'Farmgate Branch',
        'Malibagh Branch', 'Badda Branch', 'Bashundhara Branch', 'Wari Branch',
    ];

    /**
     * Real-world pharmacy expense categories.
     */
    public const EXPENSE_CATEGORIES = [
        'Shop Rent', 'Electricity Bill', 'Staff Salary', 'Transportation',
        'Medicine Wastage / Expiry Loss', 'Marketing & Promotion', 'Maintenance & Repair',
        'Internet & Telephone Bill', 'Office Supplies', 'Water & Gas Bill', 'Miscellaneous',
    ];

    /**
     * Real Bangladeshi banks / mobile financial services, for cash/bank account seed data.
     */
    public const CASH_ACCOUNTS = [
        'bKash Merchant Account',
        'Nagad Merchant Account',
        'Dutch-Bangla Bank Account',
        'City Bank Account',
        'BRAC Bank Account',
        'Islami Bank Bangladesh Account',
    ];

    /**
     * Common Bangladeshi given names, combined with SURNAMES to build realistic (not
     * specific-real-person) customer/staff names — appropriate for seed data, since
     * inventing specific real individuals' identities would not be.
     */
    public const GIVEN_NAMES = [
        'Mohammad Rafiqul', 'Abdul Karim', 'Md. Shahidul', 'Aminul', 'Kamrul Hasan',
        'Habibur Rahman', 'Nazrul', 'Delwar Hossain', 'Mizanur', 'Saiful Islam',
        'Zahirul', 'Rakibul Hasan', 'Enamul', 'Mahbubur', 'Shafiqul',
        'Fatima', 'Rahima', 'Nasrin', 'Salma', 'Rokeya',
        'Shirin', 'Ferdousi', 'Nasima', 'Rehana', 'Taslima',
        'Jesmin', 'Shahnaz', 'Sultana Razia', 'Nusrat Jahan', 'Farzana',
    ];

    public const SURNAMES = [
        'Rahman', 'Islam', 'Hossain', 'Ahmed', 'Uddin', 'Alam', 'Chowdhury',
        'Khan', 'Akter', 'Begum', 'Karim', 'Miah', 'Sarkar', 'Talukder', 'Molla',
    ];
}
