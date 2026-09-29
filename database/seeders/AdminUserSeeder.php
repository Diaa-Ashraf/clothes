<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminPassword = env('ADMIN_INITIAL_PASSWORD');
        $isGenerated = false;

        if (empty($adminPassword)) {
            $adminPassword = \Illuminate\Support\Str::password(16);
            $isGenerated = true;
        }

        $admin = User::firstOrCreate(
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

        if ($isGenerated) {
            $this->command?->warn("Generated Super Admin Password: {$adminPassword}");
            $this->command?->info("Please change this password immediately upon first login!");
        }
    }
}
