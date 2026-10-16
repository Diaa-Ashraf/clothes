<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\LoginRequest;
use App\Http\Requests\Storefront\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show storefront login page.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('site.home');
        }

        return view('storefront.auth.login');
    }

    /**
     * Handle storefront login by Phone or Email.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $loginInput = trim($request->input('login', $request->input('email', '')));
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        // Check if login input is email or phone
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [
            $fieldType => $loginInput,
            'password' => $password,
        ];

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            session()->flash('success', __('تم تسجيل الدخول بنجاح! مرحباً بك في :site.', ['site' => settings('site_name', 'أزياء لافيستا')]));

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('site.home'));
        }

        throw ValidationException::withMessages([
            'login' => __('بيانات الدخول غير صحيحة. يرجى التأكد من رقم الهاتف أو البريد وكلمة المرور.'),
        ]);
    }

    /**
     * Show storefront register page.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('site.home');
        }

        return view('storefront.auth.register');
    }

    /**
     * Handle storefront user registration.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $addresses = [];
        if (!empty($validated['address'])) {
            $addresses[] = [
                'id' => 1,
                'title' => 'المنزل',
                'address' => trim($validated['address']),
                'is_default' => true,
            ];
        }

        $user = User::create([
            'name' => trim($validated['name']),
            'phone' => !empty($validated['phone']) ? trim($validated['phone']) : null,
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'address' => $validated['address'] ?? null,
            'addresses' => $addresses,
            'default_address' => $validated['address'] ?? null,
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('site.home'))->with('success', __('أهلاً بك! تم إنشاء حسابك بنجاح في :site.', ['site' => settings('site_name', 'أزياء لافيستا')]));
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('site.home')->with('status', __('تم تسجيل الخروج بنجاح. ننتظر زيارتك القادمة!'));
    }
}
