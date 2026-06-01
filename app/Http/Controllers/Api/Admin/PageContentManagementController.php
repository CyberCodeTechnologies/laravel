<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\PageContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageContentManagementController extends ApiController
{
    /**
     * Display a listing of page contents.
     */
    public function index(Request $request): JsonResponse
    {
        $query = PageContent::query();

        if ($request->filled('page')) {
            $query->where('page', $request->page);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        if ($request->filled('key')) {
            $query->where('key', 'like', '%' . $request->key . '%');
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $pageContents = $query->orderBy('page')
            ->orderBy('section')
            ->orderBy('sort_order')
            ->paginate($request->get('per_page', 15));

        return $this->success($pageContents);
    }

    /**
     * Store a newly created page content.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page' => ['required', 'string', 'max:100'],
            'section' => ['required', 'string', 'max:100'],
            'key' => ['required', 'string', 'max:255'],
            'content_en' => ['required', 'string'],
            'content_my' => ['nullable', 'string'],
            'type' => ['nullable', 'in:text,image,video,html,json'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $pageContent = PageContent::create($request->all());

        return $this->success($pageContent, 'Page content created successfully', 201);
    }

    /**
     * Display the specified page content.
     */
    public function show(int $id): JsonResponse
    {
        $pageContent = PageContent::find($id);

        if (!$pageContent) {
            return $this->notFound('Page content not found');
        }

        return $this->success($pageContent);
    }

    /**
     * Update the specified page content.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $pageContent = PageContent::find($id);

        if (!$pageContent) {
            return $this->notFound('Page content not found');
        }

        $validator = Validator::make($request->all(), [
            'page' => ['sometimes', 'required', 'string', 'max:100'],
            'section' => ['sometimes', 'required', 'string', 'max:100'],
            'key' => ['sometimes', 'required', 'string', 'max:255'],
            'content_en' => ['sometimes', 'required', 'string'],
            'content_my' => ['nullable', 'string'],
            'type' => ['nullable', 'in:text,image,video,html,json'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $pageContent->update($request->all());

        return $this->success($pageContent, 'Page content updated successfully');
    }

    /**
     * Remove the specified page content.
     */
    public function destroy(int $id): JsonResponse
    {
        $pageContent = PageContent::find($id);

        if (!$pageContent) {
            return $this->notFound('Page content not found');
        }

        $pageContent->delete();

        return $this->success(null, 'Page content deleted successfully');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(int $id): JsonResponse
    {
        $pageContent = PageContent::find($id);

        if (!$pageContent) {
            return $this->notFound('Page content not found');
        }

        $pageContent->update(['is_active' => !$pageContent->is_active]);

        return $this->success($pageContent, 'Active status toggled successfully');
    }

    /**
     * Bulk update page contents.
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'contents' => ['required', 'array'],
            'contents.*.id' => ['required', 'exists:page_contents,id'],
            'contents.*.content_en' => ['nullable', 'string'],
            'contents.*.content_my' => ['nullable', 'string'],
            'contents.*.is_active' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        foreach ($request->contents as $contentData) {
            $pageContent = PageContent::find($contentData['id']);
            if ($pageContent) {
                $updateData = array_intersect_key($contentData, array_flip(['content_en', 'content_my', 'is_active']));
                $pageContent->update($updateData);
            }
        }

        return $this->success(null, 'Page contents updated successfully');
    }

    /**
     * Get content by page and section.
     */
    public function getByPageSection(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page' => ['required', 'string'],
            'section' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $query = PageContent::where('page', $request->page);

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        $contents = $query->active()
            ->orderBy('sort_order')
            ->get();

        return $this->success($contents);
    }

    /**
     * Get all pages.
     */
    public function pages(): JsonResponse
    {
        $pages = PageContent::select('page')
            ->distinct()
            ->orderBy('page')
            ->pluck('page');

        return $this->success($pages);
    }

    /**
     * Get sections for a page.
     */
    public function sections(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $sections = PageContent::where('page', $request->page)
            ->select('section')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        return $this->success($sections);
    }
}
