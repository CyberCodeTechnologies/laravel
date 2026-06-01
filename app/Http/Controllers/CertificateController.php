<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificateController extends Controller
{
    /**
     * Display the verification page for a certificate.
     */
    public function verify($certificate_code)
    {
        $certificate = Certificate::with(['artwork.artist', 'artwork.category'])
            ->where('certificate_code', $certificate_code)
            ->first();
        
        if (!$certificate) {
            return view('verify.not-found', compact('certificate_code'));
        }
        
        return view('verify.certificate', compact('certificate'));
    }

    /**
     * Generate certificate for an artwork (admin only).
     */
    public function generate(Artwork $artwork)
    {
        $this->authorize('generateCertificate', $artwork);
        
        // Check if certificate already exists
        if ($artwork->certificate()->exists()) {
            return back()->with('error', 'Certificate already exists for this artwork.');
        }
        
        // Generate certificate code
        $certificateCode = Certificate::generateCertificateCode();
        
        // Generate QR code
        $qrCodePath = $this->generateQrCode($certificateCode);
        
        // Generate certificate text
        $certificateText = $this->generateCertificateText($artwork);
        
        // Create certificate record
        $certificate = Certificate::create([
            'artwork_id' => $artwork->id,
            'artist_id' => $artwork->artist_id,
            'certificate_code' => $certificateCode,
            'qr_code' => $qrCodePath,
            'artist_signature' => $this->generateDigitalSignature($artwork->artist),
            'panchi_signature' => $this->generateDigitalSignature(null, true),
            'issue_date' => now(),
            'certificate_text' => $certificateText,
            'is_verified' => true,
        ]);
        
        // Generate PDF certificate
        $pdfPath = $this->generateCertificatePdf($certificate);
        $certificate->update(['certificate_pdf' => $pdfPath]);
        
        return back()->with('success', 'Certificate generated successfully.');
    }

    /**
     * Download certificate PDF.
     */
    public function download(Certificate $certificate)
    {
        if (!$certificate->certificate_pdf) {
            return back()->with('error', 'Certificate PDF not available.');
        }
        
        return Storage::disk('public')->download($certificate->certificate_pdf, "certificate-{$certificate->certificate_code}.pdf");
    }

    /**
     * Generate QR code for certificate verification.
     */
    private function generateQrCode($certificateCode)
    {
        $verificationUrl = route('verify.certificate', $certificateCode);
        
        $qrCode = QrCode::format('png')
            ->size(200)
            ->margin(2)
            ->generate($verificationUrl);
        
        $path = "certificates/qrcodes/{$certificateCode}.png";
        Storage::disk('public')->put($path, $qrCode);
        
        return $path;
    }

    /**
     * Generate certificate text content.
     */
    private function generateCertificateText(Artwork $artwork)
    {
        $certificateCode = Certificate::generateCertificateCode();
        
        return "CERTIFICATE OF AUTHENTICITY\n\n" .
               "This is to certify that the artwork described below is an authentic original work created by the artist.\n\n" .
               "Title: {$artwork->title}\n" .
               "Artist: {$artwork->artist->name}\n" .
               "Medium: " . ucfirst($artwork->medium) . "\n" .
               "Dimensions: {$artwork->dimensions}\n" .
               "Year: " . ($artwork->year ?? 'N/A') . "\n" .
               "Certificate Code: {$certificateCode}\n" .
               "Issue Date: " . now()->format('F j, Y') . "\n\n" .
               "This certificate verifies the authenticity of the artwork and serves as proof of its origin. " .
               "The artwork has been verified and registered in the Panchi Gallery database.\n\n" .
               "Panchi Gallery - Authenticating Art Since 2024";
    }

    /**
     * Generate digital signature.
     */
    private function generateDigitalSignature($user = null, $isPanchi = false)
    {
        if ($isPanchi) {
            return base64_encode('Panchi Gallery Digital Signature - ' . now()->format('Y-m-d H:i:s'));
        }
        
        if ($user) {
            return base64_encode($user->name . ' - Artist Signature - ' . now()->format('Y-m-d H:i:s'));
        }
        
        return base64_encode('Digital Signature - ' . now()->format('Y-m-d H:i:s'));
    }

    /**
     * Generate PDF certificate.
     */
    private function generateCertificatePdf(Certificate $certificate)
    {
        $data = [
            'certificate' => $certificate,
            'artwork' => $certificate->artwork,
            'artist' => $certificate->artist,
        ];
        
        $pdf = PDF::loadView('certificates.template', $data);
        
        $filename = "certificates/pdfs/{$certificate->certificate_code}.pdf";
        Storage::disk('public')->put($filename, $pdf->output());
        
        return $filename;
    }

    /**
     * Verify certificate via API.
     */
    public function verifyApi(Request $request)
    {
        $request->validate([
            'certificate_code' => ['required', 'string'],
        ]);
        
        $certificate = Certificate::with(['artwork.artist', 'artwork.category'])
            ->where('certificate_code', $request->certificate_code)
            ->first();
        
        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Certificate not found.',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'certificate' => [
                'certificate_code' => $certificate->certificate_code,
                'artwork' => [
                    'title' => $certificate->artwork->title,
                    'artist' => $certificate->artwork->artist->name,
                    'medium' => $certificate->artwork->medium,
                    'dimensions' => $certificate->artwork->dimensions,
                    'year' => $certificate->artwork->year,
                ],
                'issue_date' => $certificate->issue_date->format('Y-m-d'),
                'is_verified' => $certificate->is_verified,
                'current_owner' => $certificate->current_owner?->name,
                'ownership_history' => $certificate->ownership_history,
            ],
        ]);
    }
}
