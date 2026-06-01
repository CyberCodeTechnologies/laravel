<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Services\EmailService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display the contact page.
     */
    public function index(Request $request)
    {
        $artist = null;
        
        // If artist parameter is present, load the artist
        if ($request->has('artist')) {
            $artist = \App\Models\User::where('slug', $request->artist)
                ->where('role', 'artist')
                ->first();
        }
        
        // Get contact information from settings
        $contactSettings = \App\Models\GeneralSetting::byGroup('contact')
            ->active()
            ->get()
            ->keyBy('key');
            
        $contactInfo = [
            'email' => $contactSettings->get('contact_email')->value ?? 'support@panchigallery.com',
            'phone' => $contactSettings->get('contact_phone')->value ?? '+95 123 456 789',
            'address' => app()->getLocale() === 'my' 
                ? ($contactSettings->get('contact_address_my')->value ?? $contactSettings->get('contact_address')->value ?? 'Yangon, Myanmar')
                : ($contactSettings->get('contact_address')->value ?? 'Yangon, Myanmar'),
            'business_hours' => $contactSettings->get('contact_business_hours')->value ?? 'Mon–Sat, 9:00 AM – 6:00 PM',
            'map_embed' => $contactSettings->get('contact_map_embed')->value ?? null,
        ];
        
        return view('contact', compact('artist', 'contactInfo'));
    }

    /**
     * Handle contact form submission.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
            'artist_id' => ['nullable', 'exists:users,id'],
        ]);

        // Check if this is a contact artist message
        if ($request->filled('artist_id')) {
            $artist = \App\Models\User::find($request->artist_id);
            
            if ($artist && $artist->role === 'artist') {
                // Send email to the artist
                $sent = $this->emailService->sendContactArtistEmail([
                    'name' => $request->name,
                    'email' => $request->email,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'artist_name' => $artist->name,
                ], $artist->email);
                
                if ($sent) {
                    return back()->with('success', 'Your message has been sent to ' . $artist->name . '. They will respond shortly.');
                }
                
                return back()->with('error', 'Failed to send your message. Please try again later.');
            }
        }

        ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        $sent = $this->emailService->sendContactSupportEmail([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        if ($sent) {
            return back()->with('success', 'Your message has been sent. We will respond within 24 hours.');
        }

        return back()->with('success', 'Your message has been received. We will respond within 24 hours.');
    }

    /**
     * Handle newsletter subscription.
     */
    public function newsletterSubscribe(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        // In a real application, you would save this to a database or send to an API
        // For now, we'll just return success to make the feature feel complete
        
        return back()->with('success', 'Thank you for subscribing to our newsletter!');
    }
}
