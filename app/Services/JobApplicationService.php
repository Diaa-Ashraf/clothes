<?php

namespace App\Services;

use App\Models\JobApplication;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class JobApplicationService
{
    /**
     * Get paginated job applications with optional status and branch filtering.
     */
    public function paginate(?string $status = null, ?int $branchId = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = JobApplication::select([
            'id',
            'name',
            'phone',
            'email',
            'branch_id',
            'position',
            'cv_file',
            'status',
            'created_at',
        ])
        ->with(['branch' => function ($q) {
            $q->select('id', 'name');
        }]);

        if ($status && in_array($status, ['pending', 'reviewed', 'accepted', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->latest('id')->paginate($perPage)->withQueryString();
    }

    /**
     * Find job application by ID or fail.
     */
    public function find(int $id): JobApplication
    {
        return JobApplication::select([
            'id',
            'name',
            'phone',
            'email',
            'branch_id',
            'position',
            'cv_file',
            'message',
            'status',
            'created_at',
        ])
        ->with(['branch' => function ($q) {
            $q->select('id', 'name', 'address');
        }])
        ->findOrFail($id);
    }

    /**
     * Update application review status.
     */
    public function updateStatus(JobApplication $application, string $status): JobApplication
    {
        if (!in_array($status, ['pending', 'reviewed', 'accepted', 'rejected'])) {
            throw new \InvalidArgumentException('حالة الطلب غير صالحة.');
        }

        $application->update(['status' => $status]);
        return $application->fresh();
    }

    /**
     * Delete application and its CV file.
     */
    public function delete(JobApplication $application): bool
    {
        return DB::transaction(function () use ($application) {
            if ($application->cv_file && Storage::disk('public')->exists($application->cv_file)) {
                Storage::disk('public')->delete($application->cv_file);
            }

            return (bool) $application->delete();
        });
    }

    /**
     * Download or stream the CV file.
     */
    public function downloadCv(JobApplication $application): StreamedResponse
    {
        if (!$application->cv_file || !Storage::disk('public')->exists($application->cv_file)) {
            abort(404, 'ملف السيرة الذاتية غير موجود.');
        }

        return Storage::disk('public')->download(
            $application->cv_file,
            'CV_' . preg_replace('/\s+/', '_', $application->name) . '.' . pathinfo($application->cv_file, PATHINFO_EXTENSION)
        );
    }

    /**
     * Create a new job application and store its CV.
     */
    public function create(array $data, $cvFile): JobApplication
    {
        return DB::transaction(function () use ($data, $cvFile) {
            $cvPath = $cvFile->store('cvs', 'public');

            $application = JobApplication::create([
                'name' => trim($data['name']),
                'phone' => trim($data['phone']),
                'email' => !empty($data['email']) ? strtolower(trim($data['email'])) : null,
                'branch_id' => !empty($data['branch_id']) ? (int)$data['branch_id'] : null,
                'position' => trim($data['position']),
                'cv_file' => $cvPath,
                'message' => $data['message'] ?? null,
                'status' => 'pending',
            ]);

            try {
                \App\Models\AdminNotification::createForJobApplication($application);
            } catch (\Throwable $e) {
                // Ignore notification error
            }

            return $application;
        });
    }
}
