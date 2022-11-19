<?php

namespace App\Http\Controllers\Central;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Jobs\SendNewsletterJob;
use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use App\Jobs\SendNotificationToTenantJob;
use App\Http\Resources\NewsletterResource;
use App\Http\Requests\NewsletterSendRequest;

class NewsletterController extends Controller
{
    /**
     * Get subscriber data
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return NewsletterResource::collection(
            NewsletterSubscription::latest()->paginate($request->perPage)
        );
    }

    /**
     * search resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        $term  = $request->term;
        $query = NewsletterSubscription::query();
        $query->where(function ($query) use ($term) {
            $query->Where('email', 'Like', '%' . $term . '%');
        });

        return NewsletterResource::collection($query->latest()->paginate($request->perPage));
    }

    /**
     * @throws \Throwable
     */
    public function send(NewsletterSendRequest $request)
    {
        $sentTo   = $request->sent_to;
        $subject  = $request->subject;
        $greeting = $request->greeting;
        $body     = $request->body;

        if ($sentTo == 'all') {
            Tenant::chunk(200, function ($subscribers) use ($subject, $greeting, $body) {
                foreach ($subscribers as $subscriber) {
                    SendNotificationToTenantJob::dispatch($subscriber, $subject, $greeting, $body);
                }
            });
            NewsletterSubscription::chunk(200, function ($subscribers) use ($subject, $greeting, $body) {
                foreach ($subscribers as $subscriber) {
                    SendNewsletterJob::dispatch($subscriber, $subject, $greeting, $body);
                }
            });
        } elseif ($sentTo == 'tenants') {
            Tenant::chunk(200, function ($subscribers) use ($subject, $greeting, $body) {
                foreach ($subscribers as $subscriber) {
                    SendNotificationToTenantJob::dispatch($subscriber, $subject, $greeting, $body);
                }
            });
        } elseif ($sentTo == 'subscribers') {
            NewsletterSubscription::chunk(200, function ($subscribers) use ($subject, $greeting, $body) {
                foreach ($subscribers as $subscriber) {
                    SendNewsletterJob::dispatch($subscriber, $subject, $greeting, $body);
                }
            });
        }

        return $this->responseWithSuccess('Newsletter sent successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  NewsletterSubscription  $newsletter
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(NewsletterSubscription $newsletter)
    {
        $newsletter->delete();

        return $this->responseWithSuccess('Subscriber deleted successfully.');
    }
}