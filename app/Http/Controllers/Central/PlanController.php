<?php

namespace App\Http\Controllers\Central;

use App\Models\Plan;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Plan\PlanResource;
use App\Http\Requests\Plan\StorePlanRequest;
use App\Http\Requests\Plan\UpdatePlanRequest;

class PlanController extends Controller
{
    public function index()
    {
        return PlanResource::collection(Plan::get());
    }

    public function store(StorePlanRequest $request, ImageService $imageService)
    {
        // Retrieve the validated input data...
        $data = $request->validated();

        $imageNameWithStringFolderPath = $imageService->uploadImageAndGetPath($request->image, 'plans');

        // through stripe api, make plan in stripe.com
        $stripe = Stripe::make();

        $plan = $stripe->plans()->create([
            'name' => $data['name'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'interval' => $data['interval'],
            'statement_descriptor' => $data['description'],
        ]);

        // stripe always add extra 00 in amount
        $amount = (int) $plan['amount'] / 100;

        $savedPlan = Plan::create([
            'api_id' => $plan['id'],
            'image' => $imageNameWithStringFolderPath,
            'name' => $plan['name'],
            'amount' => $amount,
            'product_id' => $plan['product'],
            'description' => $plan['statement_descriptor'],
            'currency' => $plan['currency'],
            'interval' => $plan['interval'],
            'limit_clients' => $data['limit_clients'],
            'limit_suppliers' => $data['limit_suppliers'],
            'limit_employees' => $data['limit_employees'],
            'limit_domains' => $data['limit_domains'],
            'limit_purchases' => $data['limit_purchases'],
            'limit_invoices' => $data['limit_invoices'],
        ]);

        $savedPlan->features()->sync($data['features']);

        return new PlanResource($savedPlan);
    }

    public function show(Plan $plan)
    {
        return new PlanResource($plan);
    }

    /**
     * @throws \Exception
     */
    public function update(UpdatePlanRequest $request, Plan $plan, ImageService $imageService)
    {
        // update the plan name in stripe
        $stripe = Stripe::make();
        $stripe->plans()->update($plan['api_id'], [
            'name' => $request->name,
        ]);

        // if image not changed, then do not update image
        if (filter_var($request->image, FILTER_VALIDATE_URL)) {
            $plan->update($request->safe()->except('features'));
            $plan->features()->sync($request->validated('features'));

            return $this->responseWithSuccess('Plan updated successfully!');
        }

        // if image updated
        $imageService->validateBase64Image($request->image);

        $imageNameWithStringFolderPath = $imageService->uploadImageAndGetPath($request->image, 'plans');

        // update the plan name in database
        $plan->update($request->safe()->except(['image', 'features']) + [
            'image' => $imageNameWithStringFolderPath,
        ]);
        $plan->features()->sync($request->validated('features'));

        return new PlanResource($plan);
    }

    public function destroy(Plan $plan)
    {
        try {
            if (!$plan->tenants()->exists()) {
                // through stripe api, make plan in stripe.com
                $stripe = Stripe::make();
                $stripe->plans()->delete($plan->api_id);

                $plan->delete();

                return $this->responseWithSuccess($plan->name . ' deleted successfully');
            }

            return $this->responseWithError('This plan is associated with a tenant. You can not delete it.');
        } catch (\Exception $exception) {
            return $this->responseWithError($exception->getMessage());
        }
    }

    /**
     * search resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        $term = $request->term;
        $query = Plan::query();

        $query->where(function ($query) use ($term) {
            $query->where('name', 'Like', '%' . $term . '%')
                ->orWhere('description', 'Like', '%' . $term . '%')
                ->orWhere('currency', 'Like', '%' . $term . '%')
                ->orWhere('interval', 'Like', '%' . $term . '%')
                ->orWhere('status', 'Like', '%' . $term . '%');
        });

        return PlanResource::collection($query->latest()->paginate($request->perPage));
    }
}