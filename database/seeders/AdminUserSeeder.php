<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminPassword = env('ADMIN_INITIAL_PASSWORD', '12345678');

        $admin = User::updateOrCreate(
            ['email' => 'admin@altawheed.com'],
            [
                'name' => 'مدير ألبان التوحيد',
                'password' => Hash::make($adminPassword),
                'phone' => '01000000000',
                'address' => 'العامرية، الإسكندرية',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['super_admin']);

        // Also ensure admin@regalest.com if existing has super_admin role and valid password
        $oldAdmin = User::where('email', 'admin@regalest.com')->first();
        if ($oldAdmin) {
            $oldAdmin->update([
                'name' => 'مدير النظام',
                'password' => Hash::make($adminPassword),
            ]);
            $oldAdmin->syncRoles(['super_admin']);
        }
    }
}
