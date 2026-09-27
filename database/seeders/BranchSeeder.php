<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds for Al-Tawheed 4 branches in Al-Ameriya.
     * Note: Data marked [TBD] - placeholder details until finalized with client.
     */
    public function run(): void
    {
        $branches = [
            [
                'name' => 'فرع العامرية الرئيسي',
                'slug' => 'ameriya-main',
                'address' => 'شارع الجمهورية - أمام موقف العامرية، العامرية، الإسكندرية',
                'phone' => '01000000001',
                'whatsapp' => '201000000001',
                'working_hours' => [
                    'from' => '08:00 ص',
                    'to' => '02:00 ص',
                    'days' => 'يومياً طوال أيام الأسبوع',
                ],
                'lat' => 31.0253,
                'lng' => 29.8000,
                'google_maps_url' => 'https://maps.google.com/?q=31.0253,29.8000',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'فرع مساكن النهضة',
                'slug' => 'nahda-branch',
                'address' => 'منطقة النهضة - بجوار مجمع المدارس، العامرية، الإسكندرية',
                'phone' => '01000000002',
                'whatsapp' => '201000000002',
                'working_hours' => [
                    'from' => '08:00 ص',
                    'to' => '01:00 ص',
                    'days' => 'يومياً طوال أيام الأسبوع',
                ],
                'lat' => 31.0120,
                'lng' => 29.8150,
                'google_maps_url' => 'https://maps.google.com/?q=31.0120,29.8150',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'فرع عبد القادر',
                'slug' => 'abdel-qader-branch',
                'address' => 'طريق إسكندرية - مطروح الصحراوي، مدخل عبد القادر، العامرية',
                'phone' => '01000000003',
                'whatsapp' => '201000000003',
                'working_hours' => [
                    'from' => '08:00 ص',
                    'to' => '02:00 ص',
                    'days' => 'يومياً طوال أيام الأسبوع',
                ],
                'lat' => 31.0400,
                'lng' => 29.8300,
                'google_maps_url' => 'https://maps.google.com/?q=31.0400,29.8300',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'فرع الكافوري ومريوط',
                'slug' => 'kafoury-maryout-branch',
                'address' => 'محور التعمير - تقاطع الكافوري أمام مدخل بحيرة مريوط، العامرية',
                'phone' => '01000000004',
                'whatsapp' => '201000000004',
                'working_hours' => [
                    'from' => '08:00 ص',
                    'to' => '01:00 ص',
                    'days' => 'يومياً طوال أيام الأسبوع',
                ],
                'lat' => 31.0550,
                'lng' => 29.7900,
                'google_maps_url' => 'https://maps.google.com/?q=31.0550,29.7900',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($branches as $branchData) {
            Branch::updateOrCreate(
                ['slug' => $branchData['slug']],
                $branchData
            );
        }
    }
}
