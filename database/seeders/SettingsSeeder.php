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
                'value' => 'أزياء لافيستا',
                'type' => 'text',
                'description' => 'اسم المتجر الرسمي',
            ],
            [
                'group' => 'general',
                'key' => 'site_tagline',
                'value' => 'أناقة راقية، خامات قطنية مصرية فاخرة، وتصاميم استثنائية',
                'type' => 'text',
                'description' => 'الشعار اللفظي للمتجر',
            ],
            [
                'group' => 'general',
                'key' => 'site_logo',
                'value' => null,
                'type' => 'image',
                'description' => 'شعار المتجر الرسمي (Logo)',
            ],
            [
                'group' => 'general',
                'key' => 'site_favicon',
                'value' => null,
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
                'value' => '01200000000',
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
                'value' => 'support@lavista-fashion.com',
                'type' => 'text',
                'description' => 'البريد الإلكتروني الرسمي',
            ],
            [
                'group' => 'contact',
                'key' => 'address',
                'value' => 'فروعنا: الإسكندرية (العامرية) • القاهرة • طنطا',
                'type' => 'text',
                'description' => 'عنوان الإدارة والفرع الرئيسي',
            ],
            [
                'group' => 'contact',
                'key' => 'store_address',
                'value' => 'فروعنا: الإسكندرية (العامرية) • القاهرة • طنطا',
                'type' => 'text',
                'description' => 'عناوين الفروع المعروضة في الفوتر',
            ],
            [
                'group' => 'contact',
                'key' => 'working_hours',
                'value' => 'يومياً: من 10:00 صباحاً حتى 11:00 مساءً',
                'type' => 'text',
                'description' => 'مواعيد العمل الرسمية واستقبال الزوار',
            ],
            [
                'group' => 'contact',
                'key' => 'delivery_working_hours',
                'value' => 'شحن وتوصيل فوري لجميع محافظات مصر مع حق المعاينة والقياس قبل الاستلام',
                'type' => 'text',
                'description' => 'مواعيد وخدمة التوصيل للعملاء',
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
                'value' => 'أزياء لافيستا',
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

            // 5. Announcement & Topbar Strip
            [
                'group' => 'announcements',
                'key' => 'topbar_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'تفعيل شريط الترويسة العلوي الملون',
            ],
            [
                'group' => 'announcements',
                'key' => 'topbar_phone',
                'value' => '01000000000',
                'type' => 'text',
                'description' => 'هاتف خدمة العملاء المباشر بالشريط العلوي',
            ],
            [
                'group' => 'announcements',
                'key' => 'topbar_payment_note',
                'value' => 'دفع عند الاستلام • فودافون كاش • إنستاباي',
                'type' => 'text',
                'description' => 'نص طرق الدفع بجوار الهاتف في التوب بار',
            ],
            [
                'group' => 'announcements',
                'key' => 'topbar_announcement',
                'value' => 'شحن سريع لكافة محافظات مصر • معاينة وقياس قبل الدفع • استبدال مجاني 14 يوماً',
                'type' => 'text',
                'description' => 'نص إعلان الضمان والشحن المركزي للكمبيوتر',
            ],
            [
                'group' => 'announcements',
                'key' => 'topbar_announcement_mobile',
                'value' => 'معاينة وقياس قبل الدفع • شحن لكافة المحافظات',
                'type' => 'text',
                'description' => 'نص إعلان الشريط المختصر للموبايل',
            ],
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
                'value' => 'شحن سريع لكافة محافظات مصر • معاينة وقياس قبل الدفع • استبدال مجاني 14 يوماً',
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

            // 6. Social Media & Channels
            [
                'group' => 'social',
                'key' => 'facebook_link',
                'value' => 'https://facebook.com/altawhid.fashion',
                'type' => 'text',
                'description' => 'رابط صفحة فيسبوك',
            ],
            [
                'group' => 'social',
                'key' => 'instagram_link',
                'value' => 'https://instagram.com/altawhid.fashion',
                'type' => 'text',
                'description' => 'رابط حساب إنستغرام',
            ],
            [
                'group' => 'social',
                'key' => 'tiktok_link',
                'value' => 'https://tiktok.com/@lavista.fashion',
                'type' => 'text',
                'description' => 'رابط حساب تيك توك',
            ],
            [
                'group' => 'social',
                'key' => 'youtube_link',
                'value' => 'https://youtube.com/@lavistafashion',
                'type' => 'text',
                'description' => 'رابط قناة يوتيوب',
            ],
            [
                'group' => 'social',
                'key' => 'twitter_link',
                'value' => 'https://x.com/lavistafashion',
                'type' => 'text',
                'description' => 'رابط حساب منصة X (تويتر سابقاً)',
            ],
            [
                'group' => 'social',
                'key' => 'telegram_link',
                'value' => 'https://t.me/lavistafashion',
                'type' => 'text',
                'description' => 'رابط قناة تيليجرام',
            ],
            [
                'group' => 'social',
                'key' => 'snapchat_link',
                'value' => 'https://snapchat.com/add/lavistafashion',
                'type' => 'text',
                'description' => 'رابط حساب سناب شات',
            ],

            // 7. SEO & Analytics
            [
                'group' => 'seo',
                'key' => 'meta_title',
                'value' => 'أزياء لافيستا | أرقى الملابس والتنسيقات في مصر',
                'type' => 'text',
                'description' => 'عنوان المتجر الافتراضي في محركات البحث',
            ],
            [
                'group' => 'seo',
                'key' => 'meta_description',
                'value' => 'أزياء لافيستا — وجهتك الأولى لأرقى الملابس الجاهزة النسائية والرجالية والأطفال مع ضمان المعاينة والقياس قبل الاستلام.',
                'type' => 'textarea',
                'description' => 'الوصف التعريفي للمتجر في نتائج محركات البحث',
            ],
            [
                'group' => 'seo',
                'key' => 'meta_keywords',
                'value' => 'أزياء لافيستا, بوتيك لافيستا, ملابس راقية, فساتين, بدلات, أزياء كاجوال, مقاسات خاصة, توصيل ملابس مصر',
                'type' => 'text',
                'description' => 'الكلمات المفتاحية لمحركات البحث',
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
                'value' => 'أزياء لافيستا، الاسم الرائد في تقديم أجود الملابس الجاهزة والأزياء العصرية والفساتين والتنسيقات الفاخرة لكافة أفراد الأسرة في مصر، مع خدمة معاينة وقياس وتوصيل سريع حتى باب بيتك.',
                'type' => 'textarea',
                'description' => 'نبذة تعريفية شاملة عن تاريخ ورؤية أزياء لافيستا',
            ],
            [
                'group' => 'policies',
                'key' => 'about_us_short',
                'value' => 'بوتيك أزياء مصري رائد في عالم الموضة والملابس الراقية، نوفر أجود الخامات والتصاميم العصرية مع شحن سريع وحق المعاينة والقياس قبل الاستلام.',
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
