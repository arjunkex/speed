<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use App\Notifications\NewsletterSubscriptionConfirmationNotification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Notification;

class NewsletterSubscriptionController extends Controller
{
    /**
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendConfirmation(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $existingSubscription = NewsletterSubscription::withTrashed()->whereEmail($validated['email'])->first();

        if ($existingSubscription) {
            if ($existingSubscription->trashed()) {
                $existingSubscription->restore();
                Notification::send($existingSubscription,
                    new NewsletterSubscriptionConfirmationNotification($existingSubscription->email));
            }
        } else {
            Notification::route('mail', $validated['email'])
                ->notify(new NewsletterSubscriptionConfirmationNotification($validated['email']));
        }

        if (!$request->expectsJson()) {
            return redirect(url('/'))
                ->with([
                    'message' => trans('Confirmation email sent to :email', ['email' => $validated['email']]),
                    'data' => ['email' => $validated['email']],
                ]);
        }

        return $this->responseWithSuccess(
            trans('Confirmation email sent to :email', ['email' => $validated['email']]),
            ['email' => $validated['email']]
        );
    }

    protected function responseWithSuccess($message = '', $data = [], $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function confirm(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $subscription = NewsletterSubscription::firstOrCreate(['email' => $validated['email']]);

        if (!$request->expectsJson()) {
            return view('newsletter-confirmed', [
                'success' => true,
                'message' => trans('Newsletter subscription confirmed for :email', ['email' => $validated['email']]),
            ]);
        }

        return $this->responseWithSuccess(
            trans('Newsletter subscription confirmed for :email', ['email' => $validated['email']]),
            ['email' => $subscription->email],
        );
    }

    /**
     * @param  Request  $request
     * @param  NewsletterSubscription  $newsletterSubscription
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function unsubscribe(Request $request, string $email)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $newsletterSubscription = NewsletterSubscription::whereEmail($email)->firstOrFail();

        $newsletterSubscription->delete();

        if (!$request->expectsJson()) {
            return view('newsletter-unsubscribed', [
                    'message' => trans(
                        'Newsletter subscription for :email has been cancelled',
                        ['email' => $newsletterSubscription->email]
                    ),
                    'data' => ['email' => $newsletterSubscription->email],
                ]);
        }

        return $this->responseWithSuccess(
            trans('Newsletter subscription for :email has been cancelled', ['email' => $newsletterSubscription->email]),
            ['email' => $newsletterSubscription->email]
        );
    }

    protected function responseWithError($message = '', $data = [], $code = 400)
    {
        return response()->json([
            'error' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
