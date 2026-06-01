<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class FileUploadSecurityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasFile('file') || $request->hasFile('avatar') || $request->hasFile('cover_image') || $request->hasFile('image')) {
            $this->validateFileUploads($request);
        }

        return $next($request);
    }

    /**
     * Validate file uploads for security.
     */
    private function validateFileUploads(Request $request): void
    {
        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'text/plain',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        $maxFileSize = 5 * 1024 * 1024; // 5MB

        foreach ($request->allFiles() as $file) {
            if (is_array($file)) {
                foreach ($file as $singleFile) {
                    $this->validateSingleFile($singleFile, $allowedMimeTypes, $maxFileSize);
                }
            } else {
                $this->validateSingleFile($file, $allowedMimeTypes, $maxFileSize);
            }
        }
    }

    /**
     * Validate a single file.
     */
    private function validateSingleFile($file, array $allowedMimeTypes, int $maxFileSize): void
    {
        // Check file size
        if ($file->getSize() > $maxFileSize) {
            abort(422, 'File size exceeds maximum allowed size of 5MB.');
        }

        // Check MIME type
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            abort(422, 'File type not allowed. Only images, PDFs, and documents are permitted.');
        }

        // Check for malicious file signatures
        $this->checkFileSignature($file);

        // Validate file extension matches MIME type
        $this->validateFileExtension($file);
    }

    /**
     * Check file signature to prevent malicious uploads.
     */
    private function checkFileSignature($file): void
    {
        $fileHandle = fopen($file->getPathname(), 'rb');
        $signature = fread($fileHandle, 4);
        fclose($fileHandle);

        // Common executable file signatures to block
        $maliciousSignatures = [
            'MZ',    // Windows executable
            'PE',    // Portable Executable
            "\x7FELF", // Linux executable
            'PK',    // ZIP (could contain executables)
            'Rar!',  // RAR archive
        ];

        foreach ($maliciousSignatures as $maliciousSignature) {
            if (str_starts_with($signature, $maliciousSignature)) {
                abort(422, 'File type not allowed for security reasons.');
            }
        }
    }

    /**
     * Validate that file extension matches MIME type.
     */
    private function validateFileExtension($file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();

        $mimeToExtensionMap = [
            'image/jpeg' => ['jpg', 'jpeg', 'jpe', 'jfif'],
            'image/png' => ['png'],
            'image/gif' => ['gif'],
            'image/webp' => ['webp'],
            'application/pdf' => ['pdf'],
            'text/plain' => ['txt'],
            'application/msword' => ['doc'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['docx']
        ];

        if (!isset($mimeToExtensionMap[$mimeType]) ||
            !in_array($extension, $mimeToExtensionMap[$mimeType])) {
            abort(422, 'File extension does not match file type.');
        }
    }
}
