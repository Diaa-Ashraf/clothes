{{-- resources/views/components/storefront/ui/category-card.blade.php --}}
@props(['category'])

@php
 $imageUrl = $category->image_url ?: null;
@endphp

<a href="{{ route('category.product', $category->id) }}" 
 class="group flex flex-col items-center text-center p-3 rounded-2xl bg-white border border-cream hover:border-primary-400 hover:shadow-card-hover transition-all duration-300 w-28 sm:w-36 shrink-0">
 <!-- Circle Thumbnail with Zoom -->
 <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-canvas-base border-2 border-cream group-hover:border-primary-500 overflow-hidden p-2 flex items-center justify-center transition-colors mb-2">
 @if($imageUrl)
 <img src="{{ $imageUrl }}" 
 alt="{{ $category->name }}" 
 loading="lazy"
 class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
 @else
 <span class="text-2xl sm:text-3xl"></span>
 @endif
 </div>

 <!-- Category Name -->
 <h4 class="font-bold text-xs sm:text-sm text-charcoal-heading group-hover:text-primary-800 transition-colors line-clamp-1">
 {{ $category->name }}
 </h4>

 <!-- Product Count (if loaded) -->
 @if(isset($category->products_count))
 <span class="text-[10px] text-charcoal-muted mt-0.5">
 {{ $category->products_count }} صنف
 </span>
 @endif
</a>
