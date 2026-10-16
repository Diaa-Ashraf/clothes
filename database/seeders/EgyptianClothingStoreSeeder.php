<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Deal;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EgyptianClothingStoreSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Supermarket categories 1..8 and english 28..32 are inactive
        Category::whereBetween('id', [1, 8])->update(['is_active' => false]);
        Category::whereIn('slug', ['women', 'men', 'collections', 'atelier', 'essentials'])->update(['is_active' => false]);

        // 2. Ensure Clothing Categories (9 to 27) are active
        Category::whereBetween('id', [9, 27])->update(['is_active' => true]);

        // 3. Clear old non-clothing products or re-seed authentic clothing
        // Let's delete old supermarket products (where category_id in 1..8 or null or english)
        Product::whereIn('category_id', [1, 2, 3, 4, 5, 6, 7, 8, 28, 29, 30, 31, 32])->delete();

        // 4. Create/get fashion brands
        $brandTownTeam = Brand::firstOrCreate(['slug' => 'town-team'], [
            'name' => 'تاون تيم (Town Team)',
            'description' => 'ملابس كاجوال ورسمية شبابية بأعلى جودة مصرية.',
            'is_active' => true,
        ]);

        $brandConcrete = Brand::firstOrCreate(['slug' => 'concrete'], [
            'name' => 'كونكريت (Concrete)',
            'description' => 'أرقى خطوط البدل والقمصان الرجالية الفخمة.',
            'is_active' => true,
        ]);

        $brandCottonil = Brand::firstOrCreate(['slug' => 'cottonil'], [
            'name' => 'قطونيل للملابس القطنية',
            'description' => 'خامات قطن مصري 100% فائقة النعومة والراحة.',
            'is_active' => true,
        ]);

        $brandDefacto = Brand::firstOrCreate(['slug' => 'lavista-signature'], [
            'name' => 'أزياء لافيستا سيجنتشر',
            'description' => 'تشكيلات حصرية مصنعة خصيصاً لبوتيك لافيستا للأزياء.',
            'is_active' => true,
        ]);

        $brandVelvet = Brand::firstOrCreate(['slug' => 'velvet-egypt'], [
            'name' => 'فيلفيت ليدي (Velvet Lady)',
            'description' => 'أرقى الأزياء والفساتين والعبايات النسائية المودرن.',
            'is_active' => true,
        ]);

        // 5. Authentic Egyptian Clothing Products Catalog
        $clothingProducts = [
            // ================== ملابس رجالية ==================
            [
                'category_id' => 10, // قمصان وتيشيرتات رجالي
                'brand_id' => $brandTownTeam->id,
                'name' => 'قميص أكسفورد كلاسيك قطن 100% كحلي غامق',
                'slug' => 'men-oxford-shirt-navy',
                'description' => 'قميص رجالي بقصة مريحة منتظمة مصنوع من أنقى خيوط القطن المصري، مناسب للعمل والمناسبات الرسمية والكاجوال، مريح للغاية ولا يتجعد بسهولة.',
                'price' => 450.00,
                'discount_price' => 380.00,
                'image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&q=80',
                'gender' => 'men',
                'featured' => true,
                'stock_quantity' => 45,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 10,
                'brand_id' => $brandCottonil->id,
                'name' => 'تيشيرت بولو كاجوال قطن بي كيه زيتي ملكي',
                'slug' => 'men-polo-shirt-olive',
                'description' => 'تيشيرت بولو رجالي بياقة كلاسيكية وأزرار عاجية، خامة قطنية ممشطة خفيفة ومقاومة للانكماش مع ثبات ألوان يدوم طويلاً.',
                'price' => 320.00,
                'discount_price' => 260.00,
                'image' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=800&q=80',
                'gender' => 'men',
                'featured' => true,
                'stock_quantity' => 60,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 10,
                'brand_id' => $brandTownTeam->id,
                'name' => 'هودي شتوي أوفر سايز ميلتون مبطن أوف وايت',
                'slug' => 'men-oversized-winter-hoodie-beige',
                'description' => 'سويت شيرت هودي شتوي ببطانة داخلية ناعمة توفر أقصى درجات الدفء، بقصة أوفر سايز عصرية وجيب كنغر أمامي.',
                'price' => 550.00,
                'discount_price' => 450.00,
                'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80',
                'gender' => 'men',
                'featured' => true,
                'stock_quantity' => 35,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 11, // بناطيل وجينز رجالي
                'brand_id' => $brandTownTeam->id,
                'name' => 'بنطلون جينز سليم فيت أزرق كلاسيك',
                'slug' => 'men-slim-fit-jeans-blue',
                'description' => 'بنطلون جينز رجالي من قماش الدنيم المطاطي المريح، بتصميم خمسة جيوب متين وغسيل أزرق معاصر يناسب الإطلالات اليومية.',
                'price' => 520.00,
                'discount_price' => 440.00,
                'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=800&q=80',
                'gender' => 'men',
                'featured' => false,
                'stock_quantity' => 50,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 11,
                'brand_id' => $brandConcrete->id,
                'name' => 'بنطلون جبردين تشينو كاجوال بيج رملي',
                'slug' => 'men-chino-pants-sand-beige',
                'description' => 'بنطلون تشينو قطني ناعم باللون البيج الرملي الأنيق، قصة مريحة تمنحك إطلالة سمارت كاجوال راقية للعمل والخروجات.',
                'price' => 480.00,
                'discount_price' => null,
                'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?auto=format&fit=crop&w=800&q=80',
                'gender' => 'men',
                'featured' => false,
                'stock_quantity' => 40,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 12, // بدل وبليزرات
                'brand_id' => $brandConcrete->id,
                'name' => 'بليزر كاجوال كاروهات رمادي إيطالي',
                'slug' => 'men-casual-blazer-grey-plaid',
                'description' => 'بليزر رجالي أنيق بقصة سليم عصرية وخامة صوف مخلوطة خفيفة الوزن، بطانة داخلية ناعمة مع زرارين وجيوب جانبية أنيقة.',
                'price' => 1350.00,
                'discount_price' => 1150.00,
                'image' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=800&q=80',
                'gender' => 'men',
                'featured' => true,
                'stock_quantity' => 20,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 12,
                'brand_id' => $brandConcrete->id,
                'name' => 'بدلة رجالي كاملة كلاسيك كحلي ملكي',
                'slug' => 'men-classic-royal-navy-suit',
                'description' => 'بدلة رجالية فاخرة قطعتين (جاكيت + بنطلون) بتفصيل محكم وتشطيب يدوي للأفراح والمناسبات الرسمية والاجتماعات الهامة.',
                'price' => 2400.00,
                'discount_price' => 1990.00,
                'image' => 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=800&q=80',
                'gender' => 'men',
                'featured' => true,
                'stock_quantity' => 15,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 13, // جاكيتات ومعاطف شتوية
                'brand_id' => $brandTownTeam->id,
                'name' => 'جاكيت بامب ووتر بروف مبطن كابتونيه أسود',
                'slug' => 'men-puffer-jacket-black',
                'description' => 'جاكيت شتوي ووتر بروف مقاوم للماء والرياح، حشوة فايبر حرارية عازلة مع كابيشون قابل للفصل وسوستة متينة وسهلة الحركة.',
                'price' => 890.00,
                'discount_price' => 740.00,
                'image' => 'https://images.unsplash.com/photo-1548883354-7622d03aca27?auto=format&fit=crop&w=800&q=80',
                'gender' => 'men',
                'featured' => true,
                'stock_quantity' => 30,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 13,
                'brand_id' => $brandDefacto->id,
                'name' => 'معطف صوف شتوي طويل جملي دافئ',
                'slug' => 'men-long-camel-wool-coat',
                'description' => 'معطف شتوي كلاسيكي طويل بلون الجمل الدافئ، خامة صوف فاخرة توفر مظهراً مهيباً وفخامة استثنائية في الأيام الباردة.',
                'price' => 1650.00,
                'discount_price' => 1390.00,
                'image' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=800&q=80',
                'gender' => 'men',
                'featured' => true,
                'stock_quantity' => 18,
                'is_available' => true,
                'is_active' => true,
            ],

            // ================== أزياء نسائية ==================
            [
                'category_id' => 15, // فساتين وعبايات راقية
                'brand_id' => $brandVelvet->id,
                'name' => 'فستان دريس شيفون تركي طويل مشجر للمناسبات',
                'slug' => 'women-floral-chiffon-maxi-dress',
                'description' => 'دريس نسائي راقي من خامة الشيفون المبطن بنقوش زهرية رقيقة، مزود بحزام خصر لتحديد القوام وأكمام طويلة مطاطية تناسب المحجبات.',
                'price' => 780.00,
                'discount_price' => 650.00,
                'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=800&q=80',
                'gender' => 'women',
                'featured' => true,
                'stock_quantity' => 25,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 15,
                'brand_id' => $brandVelvet->id,
                'name' => 'عباية خروج خليجية سوداء بتطريز كمبيوتر ملكي',
                'slug' => 'women-black-embroidered-abaya',
                'description' => 'عباية استقبال وخروج نسائية مصنوعة من قماش الكريب السعودي الملكي بلون أسود فاحم مع تطريز راقي على الأكمام والصدر.',
                'price' => 950.00,
                'discount_price' => 790.00,
                'image' => 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=800&q=80',
                'gender' => 'women',
                'featured' => true,
                'stock_quantity' => 30,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 16, // بلوزات وتوب أنيق
                'brand_id' => $brandVelvet->id,
                'name' => 'بلوزة ستان حرير سهرة أنيقة بفيونكة عنق بيج',
                'slug' => 'women-satin-silk-blouse-beige',
                'description' => 'بلوزة نسائية فاخرة من الستان الحريري اللامع بانسيابية فائقة، تصميم ياقة برباط أنيق وأكمام بليسيه ناعمة للسهرات والمناسبات.',
                'price' => 440.00,
                'discount_price' => 360.00,
                'image' => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=800&q=80',
                'gender' => 'women',
                'featured' => false,
                'stock_quantity' => 40,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 17, // جيبات وبناطيل نسائية
                'brand_id' => $brandVelvet->id,
                'name' => 'بنطلون وايد ليج نسائي قماش واسع مريح أسود',
                'slug' => 'women-wide-leg-pants-black',
                'description' => 'بنطلون نسائي واسع بقصة تريند مريحة وخامة جبردين ليكرا ناعمة، يمنحك راحة تامة طوال اليوم وأناقة معاصرة لافتة.',
                'price' => 460.00,
                'discount_price' => 390.00,
                'image' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=800&q=80',
                'gender' => 'women',
                'featured' => false,
                'stock_quantity' => 35,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 18, // معاطف وكارديجان نسائي
                'brand_id' => $brandDefacto->id,
                'name' => 'معطف صوف نسائي طويل دابل فيس كمل وجملي',
                'slug' => 'women-long-wool-coat-camel',
                'description' => 'بالطو نسائي شتوي طويل بقصة كلاسيكية فخمة وياقة عريضة مع حزام خصر، دافئ جداً ومصنوع من أجود خامات الصوف المعالج.',
                'price' => 1450.00,
                'discount_price' => 1190.00,
                'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80',
                'gender' => 'women',
                'featured' => true,
                'stock_quantity' => 20,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 18,
                'brand_id' => $brandDefacto->id,
                'name' => 'كارديجان شتوي تريكو أوفر سايز ناعم سيمون',
                'slug' => 'women-knit-cardigan-oversize',
                'description' => 'كارديجان نسائي مفتوح تريكو ناعم مع جيوب أمامية وأكمام ساقطة مريحة، مثالي للارتداء فوق الملابس اليومية في الشتاء.',
                'price' => 580.00,
                'discount_price' => 490.00,
                'image' => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?auto=format&fit=crop&w=800&q=80',
                'gender' => 'women',
                'featured' => false,
                'stock_quantity' => 25,
                'is_available' => true,
                'is_active' => true,
            ],

            // ================== أطفال ومواليد ==================
            [
                'category_id' => 20, // ملابس أولاد
                'brand_id' => $brandCottonil->id,
                'name' => 'ترنج أطفال أولادي شتوي ميلتون مبطن كحلي وأصفر',
                'slug' => 'boys-winter-tracksuit-navy',
                'description' => 'طقم ترنج أطفال قطعتين (جاكيت بسوستة وبنطلون) خامة ميلتون ثقيل مبطن بوبرة قطنية دافئة، يتحمل الغسيل والاستخدام المتكرر.',
                'price' => 460.00,
                'discount_price' => 370.00,
                'image' => 'https://images.unsplash.com/photo-1519457431-44ccd64a579b?auto=format&fit=crop&w=800&q=80',
                'gender' => 'kids',
                'featured' => true,
                'stock_quantity' => 45,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 21, // ملابس بنات
                'brand_id' => $brandVelvet->id,
                'name' => 'فستان بناتي تول تركي منفوش للمناسبات والأعياد وردي',
                'slug' => 'girls-party-tulle-dress-pink',
                'description' => 'فستان بناتي أنيق بطبقات من التول الناعم المبطن بقطن مريح لعدم تهيج بشرة الطفل، مع فيونكة ستان على الخصر وسوستة خلفية.',
                'price' => 520.00,
                'discount_price' => 420.00,
                'image' => 'https://images.unsplash.com/photo-1518831959646-742c3a14ebf7?auto=format&fit=crop&w=800&q=80',
                'gender' => 'kids',
                'featured' => true,
                'stock_quantity' => 30,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 20,
                'brand_id' => $brandTownTeam->id,
                'name' => 'قميص جينز أولادي كاجوال بأزرار كبس كحلي',
                'slug' => 'boys-denim-shirt-casual',
                'description' => 'قميص جينز عصري للأولاد من قماش الدينيم الخفيف الناعم، مع جيوب على الصدر وقصة مريحة تناسب الخروجات والمناسبات.',
                'price' => 340.00,
                'discount_price' => 280.00,
                'image' => 'https://images.unsplash.com/photo-1503919545889-aef636e10ad4?auto=format&fit=crop&w=800&q=80',
                'gender' => 'kids',
                'featured' => false,
                'stock_quantity' => 35,
                'is_available' => true,
                'is_active' => true,
            ],

            // ================== أحذية وحقائب ==================
            [
                'category_id' => 23, // أحذية رجالية
                'brand_id' => $brandDefacto->id,
                'name' => 'سنيكرز كاجوال طبي مريح جلد أبيض رجالي',
                'slug' => 'men-white-leather-sneakers',
                'description' => 'حذاء رياضي كاجوال رجالي مصنوع من الجلد المتين مع فرش طبي ميموري فوم مريح للقدمين ونعل مرن خفيف الوزن.',
                'price' => 620.00,
                'discount_price' => 490.00,
                'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=800&q=80',
                'gender' => 'men',
                'featured' => true,
                'stock_quantity' => 40,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 23,
                'brand_id' => $brandConcrete->id,
                'name' => 'حذاء كلاسيك جلد طبيعي بقري أوكسفورد هافان',
                'slug' => 'men-classic-oxford-leather-shoes-tan',
                'description' => 'حذاء رجالي كلاسيك مصنوع يدوياً من أفخم جلود الأبقار الطبيعية المصرية، نعل متين ومريح مناسب للبدل الرسمية والأناقة المكتملة.',
                'price' => 850.00,
                'discount_price' => 690.00,
                'image' => 'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?auto=format&fit=crop&w=800&q=80',
                'gender' => 'men',
                'featured' => false,
                'stock_quantity' => 25,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 24, // حقائب وأحذية نسائية
                'brand_id' => $brandVelvet->id,
                'name' => 'شنطة يد نسائية جلد فاخرة مع حزام كروس كافيه وذهبي',
                'slug' => 'women-luxury-crossbody-leather-handbag',
                'description' => 'حقيبة يد نسائية بتشطيب جلدي راقي وإكسسوارات ذهبية لامعة لا تصدأ، مقسمة من الداخل مع جيب بسحاب لحفظ الأغراض بأمان.',
                'price' => 680.00,
                'discount_price' => 540.00,
                'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80',
                'gender' => 'women',
                'featured' => true,
                'stock_quantity' => 30,
                'is_available' => true,
                'is_active' => true,
            ],

            // ================== إكسسوارات وساعات ==================
            [
                'category_id' => 27, // أحزمة ومحافظ
                'brand_id' => $brandDefacto->id,
                'name' => 'محفظة رجالي جلد طبيعي هاند ميد مقاومة للخدش بني',
                'slug' => 'men-genuine-leather-wallet-brown',
                'description' => 'محفظة جيب رجالية مصنوعة من الجلد الطبيعي الأصلي 100%، تحتوي على أماكن للبطاقات الائتمانية والعملات الورقية وجيب للبطاقة الشخصية.',
                'price' => 260.00,
                'discount_price' => 195.00,
                'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?auto=format&fit=crop&w=800&q=80',
                'gender' => 'men',
                'featured' => false,
                'stock_quantity' => 50,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 27,
                'brand_id' => $brandDefacto->id,
                'name' => 'حزام جلد طبيعي دابل فيس رجالي أسود وبني بتوكة دوارة',
                'slug' => 'men-reversible-leather-belt-black-brown',
                'description' => 'حزام رجالي عملي يمكن ارتداؤه على الوجهين (أسود أو بني) بمجرد لف التوكة المعدنية المصقولة، مناسب للجينز والبنطلون القماش.',
                'price' => 240.00,
                'discount_price' => 180.00,
                'image' => 'https://images.unsplash.com/photo-1624222247344-550fb60583dc?auto=format&fit=crop&w=800&q=80',
                'gender' => 'men',
                'featured' => false,
                'stock_quantity' => 60,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => 26, // ساعات يد
                'brand_id' => $brandDefacto->id,
                'name' => 'ساعة يد كوارتز كلاسيك ستانلس ستيل مينا سوداء',
                'slug' => 'classic-stainless-steel-quartz-watch',
                'description' => 'ساعة يد فاخرة بمحرك كوارتز ياباني دقيق، زجاج مقاوم للخدش، حزام من الستانلس ستيل المقاوم للصدأ ومقاومة للماء للاستخدام اليومي.',
                'price' => 890.00,
                'discount_price' => 690.00,
                'image' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=800&q=80',
                'gender' => 'unisex',
                'featured' => true,
                'stock_quantity' => 20,
                'is_available' => true,
                'is_active' => true,
            ],
        ];

        foreach ($clothingProducts as $prod) {
            $prod['quantity'] = $prod['stock_quantity'] ?? 50;
            $prod['sku'] = 'EGY-' . strtoupper(Str::slug($prod['slug']));
            $prod['unit_type'] = 'قطعة';
            Product::updateOrCreate(
                ['slug' => $prod['slug']],
                $prod
            );
        }

        // 6. Also create Deals for the Flash Sale section
        Deal::truncate();
        $discountedProducts = Product::whereNotNull('discount_price')->take(6)->get();
        foreach ($discountedProducts as $idx => $p) {
            $discount = round((($p->price - $p->discount_price) / $p->price) * 100);
            Deal::create([
                'product_id' => $p->id,
                'discount_percent' => $discount,
                'original_price' => $p->price,
                'deal_price' => $p->discount_price,
                'badge_text' => 'خصم ' . $discount . '%',
                'starts_at' => now()->subDays(1),
                'ends_at' => now()->addDays(14),
                'sort_order' => $idx + 1,
                'is_active' => true,
            ]);
        }
    }
}
