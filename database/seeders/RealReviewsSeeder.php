<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;

class RealReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::where('is_available', true)->take(12)->get();
        if ($products->isEmpty()) {
            return;
        }

        $reviewsData = [
            [
                'reviewer_name' => 'أحمد محمود عبد الفتاح',
                'governorate' => 'القاهرة — مدينة نصر',
                'rating' => 5.0,
                'comment' => 'استلمت قميص أكسفورد وبليزر كاجوال، الخامات ممتازة جداً وتقفيل نظيف والجميل إن المندوب استنى لحد ما قست واتأكدت من المقاس قبل ما أدفع. شكراً ليكم على الذوق والأمانة.',
                'created_at' => now()->subDays(2),
            ],
            [
                'reviewer_name' => 'مروة خليل الشناوي',
                'governorate' => 'الإسكندرية — سموحة',
                'rating' => 5.0,
                'comment' => 'الدريس والبلوزة الستان تحفة بجد ونفس الصورة بالظبط، الألوان على الطبيعة راقية جداً والتوصيل كان تاني يوم على طول في الإسكندرية. تجربة شراء ممتازة وهكررها أكيد.',
                'created_at' => now()->subDays(3),
            ],
            [
                'reviewer_name' => 'إسلام حسن الغندور',
                'governorate' => 'الدقهلية — المنصورة',
                'rating' => 5.0,
                'comment' => 'ترنجات الأطفال الأولادي خامة ميلتون قطن تقيل ومبطنة تدفي بجد ومش بتوبر مع الغسيل وسعرها ممتاز مقارنة بالمحلات في المنصورة. المقاسات مظبوطة بالمللي.',
                'created_at' => now()->subDays(5),
            ],
            [
                'reviewer_name' => 'محمود طارق القاضي',
                'governorate' => 'الجيزة — الشيخ زايد',
                'rating' => 5.0,
                'comment' => 'البنطلون الجينز السليم فيت ممتاز ومريح جداً في الحركة، القماش استريتش ناعم والمقاس جه مظبوط تماماً. خدمة التوصيل محترفة وسريعة.',
                'created_at' => now()->subDays(6),
            ],
            [
                'reviewer_name' => 'سارة إبراهيم بدوي',
                'governorate' => 'الغربية — طنطا',
                'rating' => 5.0,
                'comment' => 'المعطف الصوف ناعم وثقيل وتشطيبه ممتاز زيه زي البراندات العالمية، وسرعة الشحن للمحافظات فاجأتني. شكراً لأزياء التوحيد.',
                'created_at' => now()->subDays(8),
            ],
            [
                'reviewer_name' => 'كريم عصام يوسف',
                'governorate' => 'بورسعيد — حي الشرق',
                'rating' => 5.0,
                'comment' => 'طلبت التيشيرت البولو والقميص الصيفي، القطن 100% بارد وخفيف في اللبس والألوان ثابتة بعد الغسيل. ميزة المعاينة قبل الدفع تدي ثقة كاملة.',
                'created_at' => now()->subDays(10),
            ],
        ];

        foreach ($reviewsData as $index => $data) {
            $product = $products->get($index % $products->count());
            Review::updateOrCreate(
                [
                    'reviewer_name' => $data['reviewer_name'],
                    'product_id' => $product->id,
                ],
                [
                    'governorate' => $data['governorate'],
                    'rating' => $data['rating'],
                    'comment' => $data['comment'],
                    'approved' => true,
                    'is_featured' => true,
                    'created_at' => $data['created_at'],
                    'updated_at' => $data['created_at'],
                ]
            );
        }
    }
}
