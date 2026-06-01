<?php

namespace App\Http\Controllers\Api\Admin;

trait ApiStandardizationTrait
{
    /**
     * Apply standard pagination parameters.
     */
    protected function applyPagination($query, $request)
    {
        $perPage = $request->get('per_page', 15);
        $perPage = min($perPage, 100); // Max 100 items per page

        return $query->paginate($perPage);
    }

    /**
     * Apply standard search.
     */
    protected function applySearch($query, $request, $fields = [])
    {
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request, $fields) {
                foreach ($fields as $field) {
                    $q->orWhere($field, 'like', '%' . $request->search . '%');
                }
            });
        }

        return $query;
    }

    /**
     * Apply standard date range filter.
     */
    protected function applyDateRange($query, $request, $field = 'created_at')
    {
        if ($request->filled('date_from')) {
            $query->whereDate($field, '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate($field, '<=', $request->date_to);
        }

        return $query;
    }

    /**
     * Apply standard sorting.
     */
    protected function applySorting($query, $request, $allowedSortFields = ['created_at'])
    {
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        return $query;
    }

    /**
     * Standard success response with pagination.
     */
    protected function paginatedSuccess($data, $message = null, $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ],
        ], $statusCode);
    }
}
