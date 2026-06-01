<?php

namespace App\Http\Controllers;

use App\Models\PageContent;
use Illuminate\Http\Request;

class AdminPageContentController extends Controller
{
    /**
     * Display all page contents.
     */
    public function index(Request $request)
    {
        $query = PageContent::query();
        
        if ($request->filled('page')) {
            $query->where('page', $request->page);
        }
        
        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }
        
        $contents = $query->orderBy('page')->orderBy('section')->orderBy('sort_order')->paginate(50);
        
        $pages = PageContent::distinct()->pluck('page')->sort()->values();
        $sections = PageContent::distinct()->pluck('section')->sort()->values();
        
        return view('admin.page-contents.index', compact('contents', 'pages', 'sections'));
    }

    /**
     * Show the form for creating new page content.
     */
    public function create()
    {
        return view('admin.page-contents.create');
    }

    /**
     * Store new page content.
     */
    public function store(Request $request)
    {
        $request->validate([
            'page' => ['required', 'string', 'max:255'],
            'section' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255', 'unique:page_contents,key'],
            'content_en' => ['required', 'string'],
            'content_my' => ['nullable', 'string'],
            'type' => ['required', 'in:text,html,image'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        PageContent::create([
            'page' => $request->page,
            'section' => $request->section,
            'key' => $request->key,
            'content_en' => $request->content_en,
            'content_my' => $request->content_my,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.page-contents.index')
            ->with('success', 'Page content created successfully.');
    }

    /**
     * Show the form for editing page content.
     */
    public function edit(PageContent $pageContent)
    {
        return view('admin.page-contents.edit', compact('pageContent'));
    }

    /**
     * Update page content.
     */
    public function update(Request $request, PageContent $pageContent)
    {
        $request->validate([
            'page' => ['required', 'string', 'max:255'],
            'section' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255', 'unique:page_contents,key,' . $pageContent->id],
            'content_en' => ['required', 'string'],
            'content_my' => ['nullable', 'string'],
            'type' => ['required', 'in:text,html,image'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
        ]);

        $pageContent->update([
            'page' => $request->page,
            'section' => $request->section,
            'key' => $request->key,
            'content_en' => $request->content_en,
            'content_my' => $request->content_my,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.page-contents.index')
            ->with('success', 'Page content updated successfully.');
    }

    /**
     * Delete page content.
     */
    public function destroy(PageContent $pageContent)
    {
        $pageContent->delete();

        return redirect()->route('admin.page-contents.index')
            ->with('success', 'Page content deleted successfully.');
    }

    /**
     * Bulk update page content for a specific page.
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'page' => ['required', 'string'],
            'contents' => ['required', 'array'],
            'contents.*.key' => ['required', 'string'],
            'contents.*.content_en' => ['required', 'string'],
            'contents.*.content_my' => ['nullable', 'string'],
        ]);

        foreach ($request->contents as $contentData) {
            PageContent::updateOrCreate(
                ['key' => $contentData['key']],
                [
                    'page' => $request->page,
                    'section' => $contentData['section'] ?? 'general',
                    'content_en' => $contentData['content_en'],
                    'content_my' => $contentData['content_my'] ?? null,
                    'type' => $contentData['type'] ?? 'text',
                    'is_active' => true,
                ]
            );
        }

        return back()->with('success', 'Page content updated successfully.');
    }
}
