<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WishlistItem;
use App\Models\User;


class WishlistController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            $wishlistItems = collect();
            return view('site.wishlist', compact('wishlistItems'));
        }

        $wishlistItems = auth()->user()->wishlistItems()->with(['product.category', 'product.deals'])->get();
        return view('site.wishlist', compact('wishlistItems'));
    }
    
    public function toggle($productId)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'status' => 'unauthenticated',
                'message' => __('يرجى تسجيل الدخول لحفظ المنتج في المفضلة.')
            ], 401);
        }

        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => __('المنتج غير متوفر.')
            ], 404);
        }

        $user = auth()->user();
        $wishlistItem = $user->wishlistItems()->where('product_id', $productId)->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            $inWishlist = false;
            $message = __('تمت إزالة المنتج من المفضلة');
        } else {
            WishlistItem::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            $inWishlist = true;
            $message = __('تمت إضافة المنتج للمفضلة ❤️');
        }

        $count = $user->wishlistItems()->count();

        return response()->json([
            'success' => true,
            'in_wishlist' => $inWishlist,
            'message' => $message,
            'wishlist_count' => $count
        ]);
    }

    public function add($productId)
    {
        return $this->toggle($productId);
    }
    
    public function remove($productId)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => __('Please login to manage wishlist')], 401);
        }
        
        $user = auth()->user();
        $wishlistItem = $user->wishlistItems()->where('product_id', $productId)->first();
        
        if (!$wishlistItem) {
            return response()->json(['success' => false, 'message' => __('Product not found in wishlist')], 404);
        }
        
        $wishlistItem->delete();
        
        return response()->json([
            'success' => true, 
            'in_wishlist' => false,
            'message' => __('تمت إزالة المنتج من المفضلة'),
            'wishlist_count' => $user->wishlistItems()->count()
        ]);
    }
    
    public function count()
    {
        if (!auth()->check()) {
            return response()->json(['count' => 0]);
        }
        
        $count = auth()->user()->wishlistItems()->count();
        return response()->json(['count' => $count]);
    }
    
    public function clear()
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => __('Please login to manage wishlist')]);
        }
        
        $user = auth()->user();
        $user->wishlistItems()->delete();
        
        return response()->json([
            'success' => true, 
            'message' => __('Wishlist cleared'),
            'wishlist_count' => 0
        ]);
    }
}