<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminSupportController extends Controller
{
    /**
     * Display support overview.
     */
    public function support()
    {
        $contactCount = ContactMessage::count();
        $unreadCount = ContactMessage::where('is_read', false)->count();
        $faqCount = Faq::count();

        return view('admin.support.index', compact('contactCount', 'unreadCount', 'faqCount'));
    }

    /**
     * Display all contact messages.
     */
    public function supportContacts(Request $request)
    {
        $query = ContactMessage::query();

        // Filter by read status
        if ($request->filled('status')) {
            if ($request->status === 'read') {
                $query->where('is_read', true);
            } elseif ($request->status === 'unread') {
                $query->where('is_read', false);
            }
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('subject', 'like', "%{$request->search}%");
            });
        }

        $contacts = $query->latest()->paginate(20);

        return view('admin.support.contacts', compact('contacts'));
    }

    /**
     * Mark contacts as read.
     */
    public function markContactsRead(Request $request)
    {
        $validated = $request->validate([
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:contact_messages,id',
        ]);

        ContactMessage::whereIn('id', $validated['contact_ids'])->update(['is_read' => true]);

        return redirect()->back()
            ->with('success', 'Contacts marked as read.');
    }

    /**
     * Export contacts.
     */
    public function exportContacts()
    {
        $contacts = ContactMessage::all();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=contacts.csv',
        ];

        $callback = function () use ($contacts) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Subject', 'Message', 'Read', 'Created At']);

            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->id,
                    $contact->name,
                    $contact->email,
                    $contact->subject,
                    $contact->message,
                    $contact->is_read ? 'Yes' : 'No',
                    $contact->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show contact details.
     */
    public function showContact(ContactMessage $contact)
    {
        $contact->update(['is_read' => true]);

        return view('admin.support.show-contact', compact('contact'));
    }

    /**
     * Respond to contact.
     */
    public function respondContact(Request $request, ContactMessage $contact)
    {
        $validated = $request->validate([
            'response' => 'required|string',
        ]);

        // Send email response
        \Mail::to($contact->email)->send(new \App\Mail\ContactResponse($contact, $validated['response']));

        $contact->update(['is_resolved' => true]);

        return redirect()->route('admin.support.contacts')
            ->with('success', 'Response sent successfully.');
    }

    /**
     * Resolve contact.
     */
    public function resolveContact(ContactMessage $contact)
    {
        $contact->update(['is_resolved' => true]);

        return redirect()->back()
            ->with('success', 'Contact resolved successfully.');
    }

    /**
     * Delete contact.
     */
    public function deleteContact(ContactMessage $contact)
    {
        $contact->delete();

        return redirect()->route('admin.support.contacts')
            ->with('success', 'Contact deleted successfully.');
    }

    /**
     * Display FAQ management.
     */
    public function supportFaq()
    {
        $faqs = Faq::latest()->paginate(20);

        return view('admin.support.faq', compact('faqs'));
    }

    /**
     * Show the form for creating a new FAQ.
     */
    public function createFaq()
    {
        return view('admin.support.create-faq');
    }

    /**
     * Store a newly created FAQ.
     */
    public function storeFaq(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        Faq::create($validated);

        return redirect()->route('admin.support.faq')
            ->with('success', 'FAQ created successfully.');
    }

    /**
     * Show the form for editing FAQ.
     */
    public function editFaq(Faq $faq)
    {
        return view('admin.support.edit-faq', compact('faq'));
    }

    /**
     * Update FAQ.
     */
    public function updateFaq(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        $faq->update($validated);

        return redirect()->route('admin.support.faq')
            ->with('success', 'FAQ updated successfully.');
    }

    /**
     * Delete FAQ.
     */
    public function deleteFaq(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.support.faq')
            ->with('success', 'FAQ deleted successfully.');
    }
}
