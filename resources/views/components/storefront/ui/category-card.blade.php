{{-- resources/views/components/storefront/ui/category-card.blade.php --}}
@props(['category'])

@php
    $imageUrl = $category->image_url ?: null;
@endphp

<a href="{{ route('category.product', $category->id) }}" 
   class="group flex flex-col items-center text-center p-3.5 rounded-2xl bg-[#FDFBF7] border border-[#EADFC8] hover:border-[#CDB78F] hover:shadow-md transition-all duration-300 w-28 sm:w-36 shrink-0 hover:-translate-y-0.5">
    <!-- Circle Thumbnail with Zoom -->
    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-[#F3E9D2] border border-[#EADFC8] group-hover:border-[#CDB78F] overflow-hidden p-2 flex items-center justify-center transition-colors mb-2.5">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" 
                 alt="{{ $category->name }}" 
                 loading="lazy"
                 class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
        @else
            <svg class="w-8 h-8 text-[#6B5642]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
        @endif
    </div>

    <!-- Category Name -->
    <h4 class="font-serif font-bold text-xs sm:text-sm text-[#2B2118] group-hover:text-[#A9552F] transition-colors line-clamp-1">
        {{ $category->name }}
    </h4>

    <!-- Product Count (if loaded) -->
    @if(isset($category->products_count))
        <span class="text-[10px] text-[#8F7B63] font-medium mt-0.5">
            {{ $category->products_count }} قطعة
        </span>
    @endif
</a>
