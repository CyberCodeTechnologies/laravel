<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPublicProxyController extends Controller
{
    /**
     * Forward /public/admin/* and /public/public/admin/* to internal /admin/* routes (XAMPP).
     */
    public function __invoke(Request $request, ?string $path = null): Response
    {
        // Enforce HTTPS for proxied admin public URLs
        if (! $request->isSecure()) {
            $secure = preg_replace('/^http:/i', 'https:', $request->fullUrl());
            return redirect()->to($secure, 301);
        }

        $user = $request->user();

        if (! $user || ! method_exists($user, 'isAdmin') || ! $user->isAdmin()) {
            abort(403);
        }

        $adminPath = '/admin'.($path ? '/'.ltrim($path, '/') : '/dashboard');

        $parameters = array_merge($request->query->all(), $request->request->all());

        $subRequest = Request::create(
            $adminPath,
            $request->getMethod(),
            $parameters,
            $request->cookies->all(),
            $request->allFiles(),
            $request->server->all(),
            $request->getContent() ?: null
        );

        $subRequest->headers->replace($request->headers->all());
        $subRequest->setUserResolver(fn () => $user);

        return app()->handle($subRequest);
    }
}
