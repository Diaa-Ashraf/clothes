<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Str;

class SaharaAtelierSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Update brand settings
        $settings = [
            'site_name' => 'SAHARA ATELIER',
            'site_tagline' => 'ELEVATED ESSENTIALS • AUTUMN/WINTER',
            'store_phone' => '+966 50 000 0000',
            'store_whatsapp' => '966500000000',
            'currency' => 'SAR',
            'currency_symbol' => 'SAR',
            'meta_title' => 'SAHARA ATELIER | Elevated Essentials',
            'meta_description' => 'Luxury desert palette, elevated essentials, quiet luxury tailoring and natural linen.',
        ];

        foreach ($settings as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        // 2. Categories
        $categories = [
            ['name' => 'WOMEN', 'slug' => 'women', 'description' => 'Women Collection'],
            ['name' => 'MEN', 'slug' => 'men', 'description' => 'Men Collection'],
            ['name' => 'COLLECTIONS', 'slug' => 'collections', 'description' => 'Exclusive Seasonal Collections'],
            ['name' => 'ATELIER', 'slug' => 'atelier', 'description' => 'Atelier Handcrafted Couture'],
            ['name' => 'ESSENTIALS', 'slug' => 'essentials', 'description' => 'Elevated Wardrobe Essentials'],
        ];

        $catModels = [];
        foreach ($categories as $index => $cat) {
            $catModels[$cat['slug']] = Category::updateOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'is_active' => true,
                    'sort_order' => $index,
                ]
            );
        }

        // 3. Sahara Atelier Spotlight Products
        $products = [
            [
                'id' => 1,
                'name' => 'TAILORED COAT',
                'sku' => 'SHR-COT-01',
                'description' => 'Classic double-breasted long tailored coat crafted from ultra-soft camel wool. Styled in a timeless silhouette with wide lapels and structured shoulders.',
                'price' => 1499.00,
                'discount_price' => null,
                'slug' => 'tailored-coat',
                'image' => 'assets/site/img/sahara/coat.jpg',
                'category_id' => $catModels['collections']->id,
                'gender' => 'women',
                'season' => 'autumn_winter',
                'material' => '100% Camel Wool',
                'fit' => 'Tailored Classic',
                'featured' => true,
                'stock_quantity' => 25,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'name' => 'SILK SHIRT',
                'sku' => 'SHR-SHR-02',
                'description' => 'Flowing relaxed-fit button-down blouse cut from fluid sand-washed mulberry silk. Features elongated cuffs and shell buttons.',
                'price' => 899.00,
                'discount_price' => null,
                'slug' => 'silk-shirt',
                'image' => 'assets/site/img/sahara/blouse.jpg',
                'category_id' => $catModels['women']->id,
                'gender' => 'women',
                'season' => 'all_season',
                'material' => '100% Mulberry Silk',
                'fit' => 'Relaxed Fluid',
                'featured' => true,
                'stock_quantity' => 30,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'name' => 'LINEN BLAZER',
                'sku' => 'SHR-BLZ-03',
                'description' => 'Single-breasted relaxed blazer tailored from heavy organic Belgian linen. Earthy desert tones with horn buttons and notch lapels.',
                'price' => 1250.00,
                'discount_price' => null,
                'slug' => 'linen-blazer',
                'image' => 'assets/site/img/sahara/blazer.jpg',
                'category_id' => $catModels['collections']->id,
                'gender' => 'unisex',
                'season' => 'spring_summer',
                'material' => '100% Pure Linen',
                'fit' => 'Relaxed Tailored',
                'featured' => true,
                'stock_quantity' => 20,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 4,
                'name' => 'DESERT WRAP GOWN',
                'sku' => 'SHR-GWN-04',
                'description' => 'Airy bohemian desert wrap gown with poet sleeves and sash belt. Inspired by golden sand dunes and effortless feminine grace.',
                'price' => 1650.00,
                'discount_price' => null,
                'slug' => 'desert-wrap-gown',
                'image' => 'assets/site/img/sahara/hero.jpg',
                'category_id' => $catModels['atelier']->id,
                'gender' => 'women',
                'season' => 'all_season',
                'material' => 'Raw Linen & Cotton Blend',
                'fit' => 'Flowing Wrap',
                'featured' => true,
                'stock_quantity' => 15,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 5,
                'name' => 'PLEATED LINEN TROUSERS',
                'sku' => 'SHR-TRS-05',
                'description' => 'Wide-leg high-waisted pleated trousers in warm sand ecru. Breathable drape tailored for elevated everyday luxury.',
                'price' => 790.00,
                'discount_price' => null,
                'slug' => 'pleated-linen-trousers',
                'image' => 'assets/site/img/sahara/coat.jpg',
                'category_id' => $catModels['essentials']->id,
                'gender' => 'unisex',
                'season' => 'all_season',
                'material' => '100% Fine Linen',
                'fit' => 'Wide Leg High Rise',
                'featured' => true,
                'stock_quantity' => 35,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 6,
                'name' => 'CASHMERE KNIT SWEATER',
                'sku' => 'SHR-SWT-06',
                'description' => 'Featherweight crewneck knit in undyed Mongolian cashmere. Effortless warmth in subtle sandy gold hues.',
                'price' => 1100.00,
                'discount_price' => null,
                'slug' => 'cashmere-knit-sweater',
                'image' => 'assets/site/img/sahara/blouse.jpg',
                'category_id' => $catModels['essentials']->id,
                'gender' => 'unisex',
                'season' => 'autumn_winter',
                'material' => '100% Pure Cashmere',
                'fit' => 'Regular Fit',
                'featured' => true,
                'stock_quantity' => 22,
                'is_available' => true,
                'is_active' => true,
            ],
        ];

        foreach ($products as $prod) {
            Product::updateOrCreate(['id' => $prod['id']], $prod);
        }
    }
}
