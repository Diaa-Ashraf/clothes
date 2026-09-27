<?php

namespace App\Http\Controllers;

use App\Helpers\StorageHelper;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's luxury customer dashboard and profile.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Eager load customer orders with their items and products
        $orders = $user->orders()
            ->with(['orderItems.product'])
            ->latest()
            ->paginate(8);

        // Load wishlist items for quick portal access
        $wishlistItems = $user->wishlistItems()
            ->with(['product.category'])
            ->latest()
            ->take(8)
            ->get();

        // Customer Account Key Metrics
        $stats = [
            'orders_count' => $user->orders()->count(),
            'wishlist_count' => $user->wishlistItems()->count(),
            'total_spent' => (float) $user->orders()->whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])->sum('total_price'),
        ];

        return view('profile.edit', compact('user', 'orders', 'wishlistItems', 'stats'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle Avatar upload if provided
        if ($request->hasFile('avatar')) {
            $avatarFilename = StorageHelper::uploadImage(
                $request,
                'avatar',
                'avatars',
                $user->name
            );
            $validated['avatar'] = $avatarFilename;
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated')->with('success', __('تم تحديث بيانات الملف الشخصي بنجاح.'));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => __('يرجى إدخال كلمة المرور الحالية لتأكيد الحذف.'),
            'password.current_password' => __('كلمة المرور الحالية غير صحيحة.'),
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', __('تم حذف حسابك بنجاح. نتمنى رؤيتك قريباً.'));
    }
}
