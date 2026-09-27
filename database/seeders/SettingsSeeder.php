<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // 1. General & Branding
            [
                'group' => 'general',
                'key' => 'site_name',
                'value' => 'ألبان وسوبرماركت التوحيد',
                'type' => 'text',
                'description' => 'اسم المتجر الرسمي',
            ],
            [
                'group' => 'general',
                'key' => 'site_tagline',
                'value' => 'أصالة الطعم البلدي وجودة السوبرماركت في العامرية',
                'type' => 'text',
                'description' => 'الشعار اللفظي للمتجر',
            ],
            [
                'group' => 'general',
                'key' => 'site_logo',
                'value' => 'assets/site/img/logo.png',
                'type' => 'image',
                'description' => 'شعار المتجر الرسمي (Logo)',
            ],
            [
                'group' => 'general',
                'key' => 'site_favicon',
                'value' => 'assets/site/img/logo.png',
                'type' => 'image',
                'description' => 'أيقونة التاب في المتصفح (Favicon)',
            ],
            [
                'group' => 'general',
                'key' => 'default_currency',
                'value' => 'EGP',
                'type' => 'text',
                'description' => 'العملة الأساسية (الجنيه المصري)',
            ],
            [
                'group' => 'general',
                'key' => 'currency_symbol',
                'value' => 'ج.م',
                'type' => 'text',
                'description' => 'رمز العملة المعروض للعملاء',
            ],

            // 2. Contact & Hours
            [
                'group' => 'contact',
                'key' => 'store_phone',
                'value' => '01000000000',
                'type' => 'text',
                'description' => 'رقم هاتف المتجر والشكاوى الرئيسي',
            ],
            [
                'group' => 'contact',
                'key' => 'store_phone_secondary',
                'value' => '034400000',
                'type' => 'text',
                'description' => 'هاتف إضافي أو خط أرضي',
            ],
            [
                'group' => 'contact',
                'key' => 'store_whatsapp',
                'value' => '201000000000',
                'type' => 'text',
                'description' => 'رقم الواتساب الموحد لاستقبال الطلبات',
            ],
            [
                'group' => 'contact',
                'key' => 'store_email',
                'value' => 'info@altawheed-dairy.com',
                'type' => 'text',
                'description' => 'البريد الإلكتروني الرسمي',
            ],
            [
                'group' => 'contact',
                'key' => 'address',
                'value' => 'شارع المستشفى - بجوار الإسعاف، العامرية، الإسكندرية',
                'type' => 'text',
                'description' => 'عنوان الإدارة والفرع الرئيسي',
            ],
            [
                'group' => 'contact',
                'key' => 'working_hours',
                'value' => 'يومياً: من 8:00 صباحاً حتى 2:00 بعد منتصف الليل',
                'type' => 'text',
                'description' => 'مواعيد العمل الرسمية واستقبال الزوار',
            ],
            [
                'group' => 'contact',
                'key' => 'delivery_working_hours',
                'value' => 'خدمة التوصيل السريع متاحة يومياً من 8:00 ص حتى 2:00 ص',
                'type' => 'text',
                'description' => 'مواعيد حركة أسطول التوصيل للمنازل',
            ],
            [
                'group' => 'contact',
                'key' => 'google_maps_url',
                'value' => 'https://maps.google.com',
                'type' => 'text',
                'description' => 'رابط موقع الفرع الرئيسي على خرائط جوجل',
            ],

            // 3. Payments & InstaPay
            [
                'group' => 'payments',
                'key' => 'enable_cod',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'تفعيل الدفع نقداً عند الاستلام',
            ],
            [
                'group' => 'payments',
                'key' => 'enable_instapay',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'تفعيل الدفع الإلكتروني عبر InstaPay',
            ],
            [
                'group' => 'payments',
                'key' => 'instapay_username',
                'value' => 'altawheed@instapay',
                'type' => 'text',
                'description' => 'عنوان الدفع اللحظي (InstaPay IPA / Username)',
            ],
            [
                'group' => 'payments',
                'key' => 'instapay_number',
                'value' => '01000000000',
                'type' => 'text',
                'description' => 'رقم هاتف حساب إنستاباي',
            ],
            [
                'group' => 'payments',
                'key' => 'instapay_name',
                'value' => 'ألبان وسوبرماركت التوحيد',
                'type' => 'text',
                'description' => 'اسم صاحب الحساب في تطبيق إنستاباي',
            ],
            [
                'group' => 'payments',
                'key' => 'instapay_instructions',
                'value' => 'يرجى تحويل القيمة عبر تطبيق InstaPay ثم إرفاق صورة إشعار التحويل لتأكيد تجهيز الطلب فوراً.',
                'type' => 'textarea',
                'description' => 'تعليمات الدفع الموضحة للعميل في صفحة الدفع',
            ],
            [
                'group' => 'payments',
                'key' => 'enable_vodafone_cash',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'تفعيل الدفع عبر محافظ الكاش (فودافون/أورانج/اتصالات)',
            ],
            [
                'group' => 'payments',
                'key' => 'vodafone_cash_number',
                'value' => '01000000000',
                'type' => 'text',
                'description' => 'رقم محفظة فودافون كاش الرسمية',
            ],

            // 4. Shipping & Delivery
            [
                'group' => 'shipping',
                'key' => 'delivery_fee',
                'value' => '20',
                'type' => 'number',
                'description' => 'رسوم التوصيل الافتراضية داخل نطاق العامرية (ج.م)',
            ],
            [
                'group' => 'shipping',
                'key' => 'free_delivery_threshold',
                'value' => '300',
                'type' => 'number',
                'description' => 'قيمة الطلب المؤهلة للشحن والتوصيل المجاني (ج.م)',
            ],
            [
                'group' => 'shipping',
                'key' => 'min_order_amount',
                'value' => '50',
                'type' => 'number',
                'description' => 'الحد الأدنى لقيمة الطلب (ج.م)',
            ],
            [
                'group' => 'shipping',
                'key' => 'estimated_delivery_time',
                'value' => '30 - 45 دقيقة',
                'type' => 'text',
                'description' => 'متوسط وقت التوصيل المتوقع',
            ],
            [
                'group' => 'shipping',
                'key' => 'delivery_coverage_areas',
                'value' => 'العامرية، كينج مريوط، شارع الإسعاف، النهضة، عبد القادر، وقرى مريوط',
                'type' => 'text',
                'description' => 'مناطق التغطية بالتوصيل الفوري',
            ],
            [
                'group' => 'shipping',
                'key' => 'allow_store_pickup',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'إتاحة خيار الاستلام المباشر من أحد الفروع مجاناً',
            ],

            // 5. Announcement Bar
            [
                'group' => 'announcements',
                'key' => 'announcement_bar_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'تفعيل الشريط الإعلاني العلوي في الموقع',
            ],
            [
                'group' => 'announcements',
                'key' => 'announcement_bar_text',
                'value' => 'التوصيل السريع متاح لجميع مناطق العامرية وقرى مريوط والإسكندرية',
                'type' => 'text',
                'description' => 'نص الإعلان أو العرض الترويجي في الشريط العلوي',
            ],
            [
                'group' => 'announcements',
                'key' => 'announcement_bar_link',
                'value' => '',
                'type' => 'text',
                'description' => 'رابط اختياري يفتح عند النقر على الشريط الإعلاني',
            ],

            // 6. Social Media
            [
                'group' => 'social',
                'key' => 'facebook_link',
                'value' => 'https://facebook.com/altawheed.dairy',
                'type' => 'text',
                'description' => 'رابط صفحة فيسبوك',
            ],
            [
                'group' => 'social',
                'key' => 'instagram_link',
                'value' => 'https://instagram.com/altawheed.dairy',
                'type' => 'text',
                'description' => 'رابط حساب إنستغرام',
            ],
            [
                'group' => 'social',
                'key' => 'tiktok_link',
                'value' => 'https://tiktok.com/@altawheed.dairy',
                'type' => 'text',
                'description' => 'رابط حساب تيك توك',
            ],
            [
                'group' => 'social',
                'key' => 'youtube_link',
                'value' => '',
                'type' => 'text',
                'description' => 'رابط قناة يوتيوب',
            ],

            // 7. SEO & Analytics
            [
                'group' => 'seo',
                'key' => 'meta_title',
                'value' => 'ألبان وسوبرماركت التوحيد | العامرية - الإسكندرية',
                'type' => 'text',
                'description' => 'عنوان المتجر الافتراضي في محركات البحث',
            ],
            [
                'group' => 'seo',
                'key' => 'meta_description',
                'value' => 'ألبان وسوبرماركت التوحيد في العامرية، الإسكندرية. أجود منتجات الألبان والأجبان الطبيعية والسلع الغذائية وخدمة توصيل فورية.',
                'type' => 'textarea',
                'description' => 'الوصف التعريفي للمتجر في نتائج محركات البحث',
            ],
            [
                'group' => 'seo',
                'key' => 'meta_keywords',
                'value' => 'ألبان التوحيد, سوبرماركت التوحيد, جبن بلدي, ألبان العامرية, توصيل طلبات العامرية, سوبرماركت الإسكندرية, كينج مريوط',
                'type' => 'text',
                'description' => 'الكلمات المفتاحية للموقع',
            ],
            [
                'group' => 'seo',
                'key' => 'og_image',
                'value' => 'assets/site/img/og-cover.png',
                'type' => 'image',
                'description' => 'صورة المشاركة على شبكات التواصل (OG Image)',
            ],
            [
                'group' => 'seo',
                'key' => 'google_analytics_id',
                'value' => '',
                'type' => 'text',
                'description' => 'معرف Google Analytics (مثل G-XXXXXXXXXX)',
            ],
            [
                'group' => 'seo',
                'key' => 'facebook_pixel_id',
                'value' => '',
                'type' => 'text',
                'description' => 'معرف Facebook Pixel للحملات الإعلانية',
            ],

            // 8. Content & Policies
            [
                'group' => 'policies',
                'key' => 'about_us_content',
                'value' => 'ألبان وسوبرماركت التوحيد، الاسم الرائد في تقديم أجود منتجات الألبان البلدية الطبيعية والجبن الطازج والسلع الغذائية في العامرية والإسكندرية عبر 4 فروع وخدمة توصيل فورية.',
                'type' => 'textarea',
                'description' => 'نبذة تعريفية شاملة عن تاريخ ورؤية ألبان التوحيد',
            ],
            [
                'group' => 'policies',
                'key' => 'about_us_short',
                'value' => 'من أكبر مراكز الألبان والسوبرماركت في منطقة العامرية والإسكندرية. نوفر أجود منتجات الألبان الطازجة، الأجبان الطبيعية، والسلع الغذائية بأسعار الجملة والتجزئة وخدمة توصيل سريعة حتى باب بيتك.',
                'type' => 'textarea',
                'description' => 'نبذة مختصرة لفوتر الموقع',
            ],
            [
                'group' => 'policies',
                'key' => 'terms_content',
                'value' => '',
                'type' => 'textarea',
                'description' => 'محتوى مخصص لصفحة الشروط والأحكام (فارغ = استخدام النص القياسي)',
            ],
            [
                'group' => 'policies',
                'key' => 'privacy_content',
                'value' => '',
                'type' => 'textarea',
                'description' => 'محتوى مخصص لصفحة سياسة الخصوصية (فارغ = استخدام النص القياسي)',
            ],
            [
                'group' => 'policies',
                'key' => 'return_policy_content',
                'value' => '',
                'type' => 'textarea',
                'description' => 'محتوى مخصص لصفحة سياسة الاسترجاع (فارغ = استخدام النص القياسي)',
            ],

            // 9. Features & System
            [
                'group' => 'features',
                'key' => 'reviews_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'تفعيل تقييمات وآراء العملاء على المنتجات',
            ],
            [
                'group' => 'features',
                'key' => 'wishlist_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'تفعيل ميزة قائمة المفضلة للعملاء',
            ],
            [
                'group' => 'features',
                'key' => 'whatsapp_floating_button',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'إظهار زر واتساب العائم في أسفل الشاشة',
            ],
            [
                'group' => 'features',
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'تفعيل وضع الصيانة أو إغلاق المتجر المؤقت',
            ],
            [
                'group' => 'features',
                'key' => 'maintenance_message',
                'value' => 'نعتذر منكم، الموقع يخضع لصيانة وتحديثات دورية وسيعود للعمل خلال دقائق.',
                'type' => 'textarea',
                'description' => 'رسالة وضع الصيانة المعروضة للزوار',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
