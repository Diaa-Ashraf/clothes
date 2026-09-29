<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\StoreReviewRequest;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * Store a product review by authenticated user.
     */
    public function store(StoreReviewRequest $request): JsonResponse|RedirectResponse
    {
        $userId = auth()->id();
        $productId = (int)$request->input('product_id');

        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $productId)
            ->where('user_id', $userId)
            ->first();

        if ($existingReview) {
            $existingReview->update([
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
                'approved' => true, // auto approve
            ]);
            $message = 'تم تحديث تقييمك للمنتج بنجاح.';
        } else {
            Review::create([
                'product_id' => $productId,
                'user_id' => $userId,
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
                'approved' => true,
            ]);
            $message = 'شكراً لك! تم إضافة تقييمك بنجاح.';
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
