<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    /**
     * Display the user's profile settings.
     */
    public function profile(): View
    {
        $user = auth()->user();
        return view('storefront.account.profile', compact('user'));
    }

    /**
     * Update basic user profile info.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|min:3|max:150',
            'phone' => ['required', 'string', 'regex:/^(01)[0-2,5]{1}[0-9]{8}$/', 'unique:users,phone,' . $user->id],
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:300',
        ], [
            'name.required' => 'يرجى كتابة الاسم.',
            'phone.required' => 'يرجى كتابة رقم الهاتف.',
            'phone.regex' => 'رقم الهاتف غير صالح، يجب أن يكون رقم مصري صحيح.',
            'phone.unique' => 'رقم الهاتف مسجل لحساب آخر.',
            'email.required' => 'يرجى إدخال البريد الإلكتروني.',
            'email.unique' => 'البريد الإلكتروني مسجل لحساب آخر.',
        ]);

        $user->update([
            'name' => trim($validated['name']),
            'phone' => trim($validated['phone']),
            'email' => strtolower(trim($validated['email'])),
            'address' => $validated['address'] ?? $user->address,
        ]);

        return back()->with('success', __('تم تحديث بياناتك الشخصية بنجاح.'));
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.required' => 'يرجى إدخال كلمة المرور الحالية.',
            'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة.',
            'password.required' => 'يرجى إدخال كلمة المرور الجديدة.',
            'password.min' => 'كلمة المرور الجديدة يجب أن تكون 6 خانات على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور الجديدة غير متطابق.',
        ]);

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', __('تم تغيير كلمة المرور بنجاح.'));
    }

    /**
     * Display the saved delivery addresses page.
     */
    public function addresses(): View
    {
        $user = auth()->user();
        $addresses = is_array($user->addresses) ? $user->addresses : [];

        return view('storefront.account.addresses', compact('user', 'addresses'));
    }

    /**
     * Add a new delivery address to the user's saved addresses.
     */
    public function storeAddress(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'address' => 'required|string|min:5|max:300',
            'is_default' => 'nullable|boolean',
        ], [
            'title.required' => 'يرجى إدخال عنوان لهذا المكان (مثل: المنزل، العمل).',
            'address.required' => 'يرجى إدخال تفاصيل العنوان.',
            'address.min' => 'العنوان قصير جداً، يرجى كتابة تفاصيل المنطقة والشارع.',
        ]);

        $user = auth()->user();
        $addresses = is_array($user->addresses) ? $user->addresses : [];
        $isDefault = $request->boolean('is_default') || empty($addresses);

        if ($isDefault) {
            foreach ($addresses as &$addr) {
                $addr['is_default'] = false;
            }
        }

        $addresses[] = [
            'id' => time(),
            'title' => trim($validated['title']),
            'address' => trim($validated['address']),
            'is_default' => $isDefault,
        ];

        $updateData = ['addresses' => $addresses];
        if ($isDefault) {
            $updateData['address'] = trim($validated['address']);
            $updateData['default_address'] = trim($validated['address']);
        }

        $user->update($updateData);

        return back()->with('success', __('تمت إضافة العنوان الجديد بنجاح.'));
    }

    /**
     * Delete an address by its index or id.
     */
    public function deleteAddress(int $index): RedirectResponse
    {
        $user = auth()->user();
        $addresses = is_array($user->addresses) ? $user->addresses : [];

        if (isset($addresses[$index])) {
            $wasDefault = $addresses[$index]['is_default'] ?? false;
            array_splice($addresses, $index, 1);

            // If deleted address was default, promote first address
            if ($wasDefault && !empty($addresses)) {
                $addresses[0]['is_default'] = true;
                $user->address = $addresses[0]['address'];
                $user->default_address = $addresses[0]['address'];
            }

            $user->addresses = $addresses;
            $user->save();

            return back()->with('success', __('تم حذف العنوان بنجاح.'));
        }

        return back();
    }

    /**
     * Set an address as default.
     */
    public function setDefaultAddress(int $index): RedirectResponse
    {
        $user = auth()->user();
        $addresses = is_array($user->addresses) ? $user->addresses : [];

        if (isset($addresses[$index])) {
            foreach ($addresses as $k => &$addr) {
                $addr['is_default'] = ($k === $index);
            }

            $user->addresses = $addresses;
            $user->address = $addresses[$index]['address'];
            $user->default_address = $addresses[$index]['address'];
            $user->save();

            return back()->with('success', __('تم تعيين العنوان الافتراضي بنجاح.'));
        }

        return back();
    }
}
