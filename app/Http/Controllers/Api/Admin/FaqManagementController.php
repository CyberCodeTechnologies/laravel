<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqManagementController extends ApiController
{
    /**
     * Display a listing of FAQs.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Faq::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('question_en', 'like', '%' . $request->search . '%')
                  ->orWhere('question_my', 'like', '%' . $request->search . '%')
                  ->orWhere('answer_en', 'like', '%' . $request->search . '%')
                  ->orWhere('answer_my', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('is_published')) {
            $query->where('is_published', $request->boolean('is_published'));
        }

        $faqs = $query->ordered()->paginate($request->get('per_page', 15));

        return $this->success($faqs);
    }

    /**
     * Store a newly created FAQ.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category' => ['required', 'string', 'max:100'],
            'question_en' => ['required', 'string', 'max:500'],
            'answer_en' => ['required', 'string'],
            'question_my' => ['nullable', 'string', 'max:500'],
            'answer_my' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $faq = Faq::create($request->all());

        return $this->success($faq, 'FAQ created successfully', 201);
    }

    /**
     * Display the specified FAQ.
     */
    public function show(int $id): JsonResponse
    {
        $faq = Faq::find($id);

        if (!$faq) {
            return $this->notFound('FAQ not found');
        }

        return $this->success($faq);
    }

    /**
     * Update the specified FAQ.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $faq = Faq::find($id);

        if (!$faq) {
            return $this->notFound('FAQ not found');
        }

        $validator = Validator::make($request->all(), [
            'category' => ['sometimes', 'required', 'string', 'max:100'],
            'question_en' => ['sometimes', 'required', 'string', 'max:500'],
            'answer_en' => ['sometimes', 'required', 'string'],
            'question_my' => ['nullable', 'string', 'max:500'],
            'answer_my' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $faq->update($request->all());

        return $this->success($faq, 'FAQ updated successfully');
    }

    /**
     * Remove the specified FAQ.
     */
    public function destroy(int $id): JsonResponse
    {
        $faq = Faq::find($id);

        if (!$faq) {
            return $this->notFound('FAQ not found');
        }

        $faq->delete();

        return $this->success(null, 'FAQ deleted successfully');
    }

    /**
     * Toggle published status.
     */
    public function togglePublished(int $id): JsonResponse
    {
        $faq = Faq::find($id);

        if (!$faq) {
            return $this->notFound('FAQ not found');
        }

        $faq->update(['is_published' => !$faq->is_published]);

        return $this->success($faq, 'Published status toggled successfully');
    }

    /**
     * Update FAQ order.
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'faqs' => ['required', 'array'],
            'faqs.*.id' => ['required', 'exists:faqs,id'],
            'faqs.*.order' => ['required', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        foreach ($request->faqs as $faqData) {
            Faq::where('id', $faqData['id'])->update(['order' => $faqData['order']]);
        }

        return $this->success(null, 'FAQ order updated successfully');
    }

    /**
     * Get FAQ categories.
     */
    public function categories(): JsonResponse
    {
        $categories = Faq::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->orderBy('category')
            ->pluck('category');

        return $this->success($categories);
    }

    /**
     * Get FAQ statistics.
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_faqs' => Faq::count(),
            'published_faqs' => Faq::published()->count(),
            'unpublished_faqs' => Faq::where('is_published', false)->count(),
            'categories' => Faq::select('category')
                ->distinct()
                ->whereNotNull('category')
                ->where('category', '!=', '')
                ->count(),
        ];

        return $this->success($stats);
    }
}
