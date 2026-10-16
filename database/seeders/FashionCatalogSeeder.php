<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;

class FashionCatalogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Fashion Attributes & Values
        $sizeAttr = Attribute::firstOrCreate(['code' => 'size'], [
            'name' => 'المقاس',
            'type' => 'button',
            'is_filterable' => true,
            'sort_order' => 1,
        ]);

        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL', '38', '40', '42', '44'];
        foreach ($sizes as $idx => $size) {
            AttributeValue::firstOrCreate([
                'attribute_id' => $sizeAttr->id,
                'value' => $size,
            ], [
                'sort_order' => $idx + 1,
            ]);
        }

        $colorAttr = Attribute::firstOrCreate(['code' => 'color'], [
            'name' => 'اللون',
            'type' => 'color',
            'is_filterable' => true,
            'sort_order' => 2,
        ]);

        $colors = [
            ['value' => 'أسود كلاسيك', 'color_code' => '#1A1A1A'],
            ['value' => 'أبيض ناصع', 'color_code' => '#FFFFFF'],
            ['value' => 'بيج فانيلا', 'color_code' => '#E2D2B3'],
            ['value' => 'أزرق كحلي داكن', 'color_code' => '#1B2A47'],
            ['value' => 'زيتي ملكي', 'color_code' => '#5F6B3A'],
            ['value' => 'جملي دافئ', 'color_code' => '#CDB78F'],
            ['value' => 'رصاصي هادئ', 'color_code' => '#8F7B63'],
            ['value' => 'طوبي / تيراكوتا', 'color_code' => '#A9552F'],
            ['value' => 'نبيتي داكن', 'color_code' => '#9E3B2E'],
        ];

        foreach ($colors as $idx => $c) {
            AttributeValue::firstOrCreate([
                'attribute_id' => $colorAttr->id,
                'value' => $c['value'],
            ], [
                'color_code' => $c['color_code'],
                'sort_order' => $idx + 1,
            ]);
        }

        $materialAttr = Attribute::firstOrCreate(['code' => 'material'], [
            'name' => 'الخامة',
            'type' => 'select',
            'is_filterable' => true,
            'sort_order' => 3,
        ]);

        $materials = ['قطن مصري 100%', 'كتان طبيعي', 'صوف كشمير ناعم', 'حرير طبيعي', 'دينيم (جينز ياباني فاخر)', 'مزيج قطن ولكرا'];
        foreach ($materials as $idx => $m) {
            AttributeValue::firstOrCreate([
                'attribute_id' => $materialAttr->id,
                'value' => $m,
            ], [
                'sort_order' => $idx + 1,
            ]);
        }

        $fitAttr = Attribute::firstOrCreate(['code' => 'fit'], [
            'name' => 'نوع القصة',
            'type' => 'select',
            'is_filterable' => true,
            'sort_order' => 4,
        ]);

        $fits = ['Regular Fit (قصة مريحة تقليدية)', 'Slim Fit (قصة محددة للقوام)', 'Oversized (قصة واسعة عصرية)', 'Relaxed Fit (قصة انسيابية)'];
        foreach ($fits as $idx => $f) {
            AttributeValue::firstOrCreate([
                'attribute_id' => $fitAttr->id,
                'value' => $f,
            ], [
                'sort_order' => $idx + 1,
            ]);
        }

        // 2. Seed Fashion Brands
        $brands = [
            [
                'name' => 'دار الأناقة للأزياء',
                'slug' => 'dar-al-anaqah',
                'description' => 'علامة فاخرة تقدم أرقى الأزياء الكلاسيكية والعصرية بجودة تصنيع فائقة.',
                'website' => 'https://dar-alanaqah.com',
                'sort_order' => 1,
            ],
            [
                'name' => 'أوريزو كوتور (ORYZO Studio)',
                'slug' => 'oryzo-studio',
                'description' => 'تصاميم معاصرة مستوحاة من خطوط الموضة الإيطالية بالألوان الترابية الهادئة.',
                'website' => 'https://oryzo.com',
                'sort_order' => 2,
            ],
            [
                'name' => 'لورينزو (Lorenzo Milano)',
                'slug' => 'lorenzo-milano',
                'description' => 'تشكيلات البدل والقمصان والملابس الرسمية الرجالية الفخمة.',
                'website' => 'https://lorenzo.it',
                'sort_order' => 3,
            ],
            [
                'name' => 'إيجيبشن كوتون كلوب',
                'slug' => 'egyptian-cotton-club',
                'description' => 'أجود تيشيرتات وقمصان من القطن المصري طويل التيلة 100%.',
                'website' => 'https://cottonclub.eg',
                'sort_order' => 4,
            ],
            [
                'name' => 'فيلفيت كوليكشن',
                'slug' => 'velvet-collection',
                'description' => 'أرقى العبايات والفساتين والأزياء النسائية المحتشمة ذات التفاصيل الدقيقة.',
                'website' => 'https://velvet.fashion',
                'sort_order' => 5,
            ],
        ];

        foreach ($brands as $b) {
            Brand::firstOrCreate(['slug' => $b['slug']], [
                'name' => $b['name'],
                'description' => $b['description'],
                'website' => $b['website'],
                'sort_order' => $b['sort_order'],
                'is_active' => true,
            ]);
        }

        // 3. Seed Fashion Categories Tree
        $fashionTree = [
            [
                'name' => 'ملابس رجالية',
                'slug' => 'men-fashion',
                'description' => 'تشكيلات البدل، القمصان، البناطيل، والملابس الكاجوال والرسمية للرجال.',
                'sort_order' => 1,
                'children' => [
                    ['name' => 'قمصان وتيشيرتات رجالي', 'slug' => 'men-shirts-tshirts', 'description' => 'قمصان كلاسيك، كاجوال، وتيشيرتات قطنية'],
                    ['name' => 'بناطيل وجينز رجالي', 'slug' => 'men-pants-jeans', 'description' => 'بناطيل قماش رسمية، كاجوال تشينو وجينز'],
                    ['name' => 'بدل وبليزرات رجالية', 'slug' => 'men-suits-blazers', 'description' => 'بدل رسمية، بليزرات للمناسبات والأفراح'],
                    ['name' => 'جاكيتات ومعاطف شتوية', 'slug' => 'men-jackets-coats', 'description' => 'معاطف صوف وجاكيتات عصرية شتوية'],
                ],
            ],
            [
                'name' => 'أزياء نسائية',
                'slug' => 'women-fashion',
                'description' => 'فساتين راقية، عبايات خليجية، أطقم عصرية وبلوزات أنيقة.',
                'sort_order' => 2,
                'children' => [
                    ['name' => 'فساتين وعبايات راقية', 'slug' => 'women-dresses-abayas', 'description' => 'فساتين سهرة، دريسات كاجوال وعبايات فاخرة'],
                    ['name' => 'بلوزات وتوب أنيق', 'slug' => 'women-blouses-tops', 'description' => 'بلوزات حريرية، شميزات وقمصان نسائية'],
                    ['name' => 'جيبات وبناطيل نسائية', 'slug' => 'women-skirts-pants', 'description' => 'جيبات واسعة، بناطيل كلاسيك مريحة'],
                    ['name' => 'معاطف وكارديجان شتوي', 'slug' => 'women-coats-cardigans', 'description' => 'كارديجان صوف ومعاطف نسائية شتوية'],
                ],
            ],
            [
                'name' => 'أطفال ومواليد',
                'slug' => 'kids-fashion',
                'description' => 'ملابس وأطقم ناعمة للأولاد والبنات من أجود الخامات الصحية.',
                'sort_order' => 3,
                'children' => [
                    ['name' => 'ملابس أولاد', 'slug' => 'boys-clothing', 'description' => 'أطقم أولادي، تيشيرتات وبناطيل'],
                    ['name' => 'ملابس بنات', 'slug' => 'girls-clothing', 'description' => 'فساتين بناتي وأطقم أنيقة'],
                ],
            ],
            [
                'name' => 'أحذية وحقائب جلدية',
                'slug' => 'shoes-and-leather-bags',
                'description' => 'أحذية كلاسيكية وسنيكرز طبيعي، حقائب يد ومحافظ من الجلد الطبيعي.',
                'sort_order' => 4,
                'children' => [
                    ['name' => 'أحذية رجالية كلاسيك وسنيكرز', 'slug' => 'men-shoes', 'description' => 'أحذية جلد طبيعي كلاسيك وسنيكرز كاجوال'],
                    ['name' => 'أحذية وحقائب يد نسائية', 'slug' => 'women-shoes-handbags', 'description' => 'أحذية كعب عالي، فلات وحقائب جلدية'],
                ],
            ],
            [
                'name' => 'إكسسوارات وساعات',
                'slug' => 'accessories-and-watches',
                'description' => 'ساعات يد فاخرة، أحزمة جلدية، أزرار أكمام، ومحافظ أنيقة.',
                'sort_order' => 5,
                'children' => [
                    ['name' => 'ساعات يد رجالية ونسائية', 'slug' => 'luxury-watches', 'description' => 'أفخم الساعات بتصاميم كلاسيكية وعصرية'],
                    ['name' => 'أحزمة ومحافظ جلدية', 'slug' => 'belts-and-wallets', 'description' => 'أحزمة جلد طبيعي ومحافظ متينة'],
                ],
            ],
        ];

        foreach ($fashionTree as $mainCat) {
            $parent = Category::updateOrCreate(
                ['slug' => $mainCat['slug']],
                [
                    'name' => $mainCat['name'],
                    'description' => $mainCat['description'],
                    'sort_order' => $mainCat['sort_order'],
                    'parent_id' => null,
                    'is_active' => true,
                ]
            );

            if (!empty($mainCat['children'])) {
                foreach ($mainCat['children'] as $childIdx => $child) {
                    Category::updateOrCreate(
                        ['slug' => $child['slug']],
                        [
                            'name' => $child['name'],
                            'description' => $child['description'],
                            'sort_order' => $childIdx + 1,
                            'parent_id' => $parent->id,
                            'is_active' => true,
                        ]
                    );
                }
            }
        }
    }
}
