<?php

namespace App\Services;

use App\Models\Branch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BranchService
{
    /**
     * Get paginated branches with explicit column selection.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Branch::select([
            'id',
            'name',
            'slug',
            'address',
            'phone',
            'whatsapp',
            'working_hours',
            'lat',
            'lng',
            'image',
            'google_maps_url',
            'is_active',
            'sort_order',
            'created_at',
        ])
        ->orderBy('sort_order', 'asc')
        ->orderBy('id', 'desc')
        ->paginate($perPage);
    }

    /**
     * Find a branch by ID or fail.
     */
    public function find(int $id): Branch
    {
        return Branch::select([
            'id',
            'name',
            'slug',
            'address',
            'phone',
            'whatsapp',
            'working_hours',
            'lat',
            'lng',
            'image',
            'google_maps_url',
            'is_active',
            'sort_order',
        ])->findOrFail($id);
    }

    /**
     * Create a new branch.
     */
    public function create(array $data, ?UploadedFile $image = null): Branch
    {
        return DB::transaction(function () use ($data, $image) {
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
                if (Branch::where('slug', $data['slug'])->exists()) {
                    $data['slug'] = $data['slug'] . '-' . Str::random(5);
                }
            }

            if ($image) {
                $data['image'] = $this->uploadImage($image);
            }

            $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : true;
            $data['sort_order'] = $data['sort_order'] ?? 0;

            return Branch::create($data);
        });
    }

    /**
     * Update an existing branch.
     */
    public function update(Branch $branch, array $data, ?UploadedFile $image = null): Branch
    {
        return DB::transaction(function () use ($branch, $data, $image) {
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
                if (Branch::where('slug', $data['slug'])->where('id', '!=', $branch->id)->exists()) {
                    $data['slug'] = $data['slug'] . '-' . Str::random(5);
                }
            }

            if ($image) {
                if ($branch->image && Storage::disk('public')->exists($branch->image)) {
                    Storage::disk('public')->delete($branch->image);
                }
                $data['image'] = $this->uploadImage($image);
            }

            $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : false;
            $data['sort_order'] = $data['sort_order'] ?? 0;

            $branch->update($data);

            return $branch->fresh();
        });
    }

    /**
     * Delete a branch and its stored image.
     */
    public function delete(Branch $branch): bool
    {
        return DB::transaction(function () use ($branch) {
            if ($branch->image && Storage::disk('public')->exists($branch->image)) {
                Storage::disk('public')->delete($branch->image);
            }

            return (bool) $branch->delete();
        });
    }

    /**
     * Upload branch image to public storage.
     */
    protected function uploadImage(UploadedFile $image): string
    {
        return $image->store('branches', 'public');
    }
}
