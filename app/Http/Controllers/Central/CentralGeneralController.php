<?php

namespace App\Http\Controllers\Central;

use App\Http\Requests\Setting\UpdatePricingPlanSettingsRequest;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Feature;
use App\Models\SettingImage;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use App\Http\Requests\Setting\UpdateHeroSettingsRequest;
use App\Http\Requests\Setting\UpdateWhyUsSettingsRequest;
use App\Http\Requests\Setting\UpdateAboutUsSettingsRequest;
use App\Http\Requests\Setting\UpdateAdvancedSettingsRequest;
use App\Http\Requests\Setting\UpdateFeaturesSettingsRequest;
use App\Http\Requests\Setting\UpdateCustomHtmlSettingsRequest;
use App\Http\Requests\Setting\UpdateGetStartedSettingsRequest;
use App\Http\Requests\Setting\UpdateNewsletterSettingsRequest;
use App\Http\Requests\Setting\UpdateAllFeaturesSettingsRequest;
use App\Http\Requests\Setting\UpdateTestimonialSettingsRequest;
use App\Http\Requests\Setting\UpdateBusinessStartSettingsRequest;
use App\Http\Requests\Setting\UpdateSoftwareOverviewSettingsRequest;

class CentralGeneralController extends Controller
{
    // define middleware
    public function __construct()
    {
        $this->middleware('can:general-settings', ['only' => ['updateGeneralSettings']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGeneralSettings()
    {
        $query = GeneralSetting::get();

        $plans = Plan::with('features')->get();

        $allFeatures = Feature::all();

        // remove features that already exist in plans and add to all_features
        foreach ($plans as &$plan) {
            $features = $allFeatures->filter(function ($feature) use ($plan) {
                return !$plan->features->contains($feature->id);
            });
            $plan->all_features = $features;
        }

        $pages = Page::where('status', 1)->get();

        $settings = collect([
            'companyName' => $query->where('key', 'company_name')->first()?->value,
            'companyTagline' => $query->where('key', 'company_tagline')->first()?->value,
            'email' => $query->where('key', 'email_address')->first()?->value,
            'phone' => $query->where('key', 'phone_number')->first()?->value,
            'address' => $query->where('key', 'address')->first()?->value,
            'language' => $query->where('key', 'default_language')->first()?->value,
            'logo' => global_asset('images/' . $query->where('key', 'logo')->first()?->value),
            'blackLogo' => global_asset('images/' . $query->where('key', 'logo_black')->first()?->value),
            'smallLogo' => global_asset('images/' . $query->where('key', 'small_logo')->first()?->value),
            'favicon' => global_asset('images/' . $query->where('key', 'favicon')->first()?->value),
            'copyright' => $query->where('key', 'copyright')->first()?->value,

            'facebook_link' => $query->where('key', 'facebook_link')->first()?->value,
            'instagram_link' => $query->where('key', 'instagram_link')->first()?->value,
            'twitter_link' => $query->where('key', 'twitter_link')->first()?->value,
            'linkedin_link' => $query->where('key', 'linkedin_link')->first()?->value,
            'trial_day_count' => $query->where('key', 'trial_day_count')->first()?->value ?? 14,

            'why_us_cards' => SettingImage::where('status', 1)->where('type', 'why_us_cards')->get(),
            'features' => SettingImage::where('status', 1)->where('type', 'features')->get(),
            'explorers' => SettingImage::where('status', 1)->where('type', 'explorers')->get(),
            'all_features' => SettingImage::where('status', 1)->where('type', 'all_features')->get(),
            'software_overview_images' => SettingImage::where('status', 1)->where(
                'type',
                'software_overview_images'
            )->get(),
            'testimonials' => SettingImage::where('status', 1)->where('type', 'testimonials')->get(),
            'brands' => SettingImage::where('status', 1)->where('type', 'brands')->get(),
            'plans' => $plans,
            'information_plans' => $pages->where('type', Page::TYPE_INFORMATION)->values(),
            'need_help_plans' => $pages->where('type', Page::TYPE_NEED_HELP)->values(),
        ]);

        $settings = $settings->merge(settings()->all());

        return response()->json($settings);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateGeneralSettings(Request $request)
    {
        $validator = $request->validate([
            'companyName' => 'required|string|max:30',
            'companyTagline' => 'required|string|max:255|min:3',
            'emailAddress' => 'required|string|email|max:80',
            'phoneNumber' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'language' => 'required|string|min:2|max:10',
            'copyrightText' => 'required|string|max:100',

            'facebook_link' => ['nullable', 'url', 'max:255'],
            'instagram_link' => ['nullable', 'url', 'max:255'],
            'twitter_link' => ['nullable', 'url', 'max:255'],
            'linkedin_link' => ['nullable', 'url', 'max:255'],
            'trial_day_count' => ['min:0', 'integer'],
        ]);

        // get settings data
        $allSettings = GeneralSetting::get();

        // upload logo
        $logoName = $allSettings->where('key', 'logo')->first()->value;
        if ($request->logo) {
            if ($logoName != 'logo.png') {
                @unlink(public_path('images/' . $logoName));
            }
            $logoName = 'central-logo' . '.' . explode(
                '/',
                explode(':', substr($request->logo, 0, strpos($request->logo, ';')))[1]
            )[1];
            Image::make($request->logo)->save(public_path('images/') . $logoName);
        }

        // upload black logo
        $blackLogoName = $allSettings->where('key', 'logo_black')->first()->value;
        if ($request->blackLogo) {
            if ($blackLogoName != 'logo-black.png') {
                @unlink(public_path('images/' . $blackLogoName));
            }
            $blackLogoName = 'central-logo-black' . '.' . explode(
                '/',
                explode(':', substr($request->blackLogo, 0, strpos($request->blackLogo, ';')))[1]
            )[1];
            Image::make($request->blackLogo)->save(public_path('images/') . $blackLogoName);
        }

        // upload small logo
        $smallLogoName = $allSettings->where('key', 'small_logo')->first()->value;
        if ($request->smallLogo) {
            if ($smallLogoName != 'small-logo.png') {
                @unlink(public_path('images/' . $smallLogoName));
            }
            $smallLogoName = 'central-small-logo' . '.' . explode(
                '/',
                explode(':', substr($request->smallLogo, 0, strpos($request->smallLogo, ';')))[1]
            )[1];
            Image::make($request->smallLogo)->save(public_path('images/') . $smallLogoName);
        }

        // upload favicon
        $favicon = $allSettings->where('key', 'favicon')->first()->value;
        if ($request->favicon) {
            if ($favicon != 'favicon.png') {
                @unlink(public_path('images/' . $favicon));
            }
            $favicon = 'central-favicon' . '.' . explode(
                '/',
                explode(':', substr($request->favicon, 0, strpos($request->favicon, ';')))[1]
            )[1];
            Image::make($request->favicon)->save(public_path('images/') . $favicon);
        }

        // update general settings
        $allSettings->where('key', 'company_name')->first()->update(['value' => clean($request->companyName)]);
        $allSettings->where('key', 'company_tagline')->first()->update(['value' => clean($request->companyTagline)]);
        $allSettings->where('key', 'email_address')->first()->update(['value' => $request->emailAddress]);
        $allSettings->where('key', 'phone_number')->first()->update(['value' => $request->phoneNumber]);
        $allSettings->where('key', 'address')->first()->update(['value' => clean($request->address)]);
        $allSettings->where('key', 'default_language')->first()->update(['value' => clean($request->language)]);
        $allSettings->where('key', 'logo')->first()->update(['value' => $logoName]);
        $allSettings->where('key', 'logo_black')->first()->update(['value' => $blackLogoName]);
        $allSettings->where('key', 'small_logo')->first()->update(['value' => $smallLogoName]);
        $allSettings->where('key', 'favicon')->first()->update(['value' => $favicon]);
        $allSettings->where('key', 'copyright')->first()->update(['value' => clean($request->copyrightText)]);

        $allSettings->where('key', 'facebook_link')->first()->update(['value' => $request->facebook_link]);
        $allSettings->where('key', 'instagram_link')->first()->update(['value' => $request->instagram_link]);
        $allSettings->where('key', 'twitter_link')->first()->update(['value' => $request->twitter_link]);
        $allSettings->where('key', 'linkedin_link')->first()->update(['value' => $request->linkedin_link]);
        $allSettings->where('key', 'trial_day_count')->first()->update(['value' => $request->trial_day_count]);

        return $this->responseWithSuccess('Settings updated successfully!');
    }

    public function getHeroSettings()
    {
        $data['hero_tagline'] = settings()->get('hero_tagline');
        $data['hero_title'] = settings()->get('hero_title');
        $data['hero_description'] = settings()->get('hero_description');
        $data['hero_demo_button_text'] = settings()->get('hero_demo_button_text');
        $data['hero_demo_button_link'] = settings()->get('hero_demo_button_link');
        $data['hero_get_started_button_text'] = settings()->get('hero_get_started_button_text');
        $data['hero_get_started_button_link'] = settings()->get('hero_get_started_button_link');

        return $this->responseWithSuccess('Settings retrieved successfully!', $data);
    }

    public function updateHeroSettings(UpdateHeroSettingsRequest $request)
    {
        settings()->set($request->validated());

        settings()->save();

        return $this->responseWithSuccess('Settings updated successfully!');
    }

    public function getAboutUsSettings()
    {
        $data['about_us_tagline'] = settings()->get('about_us_tagline');
        $data['about_us_title'] = settings()->get('about_us_title');
        $data['about_us_description'] = settings()->get('about_us_description');

        return $this->responseWithSuccess('Settings retrieved successfully!', $data);
    }

    public function updateAboutUsSettings(UpdateAboutUsSettingsRequest $request)
    {
        settings()->set($request->validated());

        settings()->save();

        return $this->responseWithSuccess('Settings updated successfully!');
    }

    public function getWhyUsSettings()
    {
        $data['why_us_tagline'] = settings()->get('why_us_tagline');
        $data['why_us_title'] = settings()->get('why_us_title');
        $data['why_us_description'] = settings()->get('why_us_description');

        return $this->responseWithSuccess('Settings retrieved successfully!', $data);
    }

    public function updateWhyUsSettings(UpdateWhyUsSettingsRequest $request)
    {
        settings()->set($request->validated());

        settings()->save();

        return $this->responseWithSuccess('Settings updated successfully!');
    }

    public function getBusinessStartSettings()
    {
        $data['business_start_section_tagline'] = settings()->get('business_start_section_tagline');
        $data['business_start_section_title'] = settings()->get('business_start_section_title');
        $data['business_start_section_description'] = settings()->get('business_start_section_description');
        $data['business_start_support_list'] = json_decode(settings()->get('business_start_support_list', '[]'));

        return $this->responseWithSuccess('Settings retrieved successfully!', $data);
    }

    public function updateBusinessStartSettings(UpdateBusinessStartSettingsRequest $request)
    {
        settings()->set(
            $request->safe()->except('business_start_support_list') + [
                'business_start_support_list' => json_encode($request->business_start_support_list),
            ]
        );

        settings()->save();

        return $this->responseWithSuccess('Settings updated successfully!');
    }

    public function getFeaturesSettings()
    {
        $data['features_section_tagline'] = settings()->get('features_section_tagline');
        $data['features_section_title'] = settings()->get('features_section_title');
        $data['features_section_description'] = settings()->get('features_section_description');

        return $this->responseWithSuccess('Settings retrieved successfully!', $data);
    }

    public function updateFeaturesSettings(UpdateFeaturesSettingsRequest $request)
    {
        settings()->set($request->validated());

        settings()->save();

        return $this->responseWithSuccess('Settings updated successfully!');
    }

    public function getAllFeaturesSettings()
    {
        $data['all_features_section_tagline'] = settings()->get('all_features_section_tagline');
        $data['all_features_section_title'] = settings()->get('all_features_section_title');

        return $this->responseWithSuccess('Settings retrieved successfully!', $data);
    }

    public function getGetStartedSettings()
    {
        $data['get_started_box_title'] = settings()->get('get_started_box_title');
        $data['get_started_box_description'] = settings()->get('get_started_box_description');
        $data['get_started_box_button_text'] = settings()->get('get_started_box_button_text');
        $data['get_started_box_button_link'] = settings()->get('get_started_box_button_link');

        return $this->responseWithSuccess('Settings retrieved successfully!', $data);
    }

    public function updateAllFeaturesSettings(UpdateAllFeaturesSettingsRequest $request)
    {
        settings()->set($request->validated());

        settings()->save();

        return $this->responseWithSuccess('Settings updated successfully!');
    }

    public function updateGetStartedSettings(UpdateGetStartedSettingsRequest $request)
    {
        settings()->set($request->validated());

        settings()->save();

        return $this->responseWithSuccess('Settings updated successfully!');
    }

    public function getSoftwareOverviewSettings()
    {
        $data['software_overview_section_tagline'] = settings()->get('software_overview_section_tagline');
        $data['software_overview_section_title'] = settings()->get('software_overview_section_title');

        return $this->responseWithSuccess('Settings retrieved successfully!', $data);
    }

    public function updateSoftwareOverviewSettings(UpdateSoftwareOverviewSettingsRequest $request)
    {
        settings()->set($request->validated());

        settings()->save();

        return $this->responseWithSuccess('Settings updated successfully!');
    }

    public function getPricingPlanSettings()
    {
        $data['pricing_plan_section_tagline'] = settings()->get('pricing_plan_section_tagline');
        $data['pricing_plan_section_title'] = settings()->get('pricing_plan_section_title');

        return $this->responseWithSuccess('Settings retrieved successfully!', $data);
    }

    public function updatePricingPlanSettings(UpdatePricingPlanSettingsRequest $request)
    {
        settings()->set($request->validated());

        settings()->save();

        return $this->responseWithSuccess('Settings updated successfully!');
    }

    public function getNewsletterSettings()
    {
        $data['newsletter_section_title'] = settings()->get('newsletter_section_title');
        $data['newsletter_section_description'] = settings()->get('newsletter_section_description');

        return $this->responseWithSuccess('Settings retrieved successfully!', $data);
    }

    public function updateNewsletterSettings(UpdateNewsletterSettingsRequest $request)
    {
        settings()->set($request->validated());

        settings()->save();

        return $this->responseWithSuccess('Settings updated successfully!');
    }

    public function getTestimonialSettings()
    {
        $data['testimonial_section_tagline'] = settings()->get('testimonial_section_tagline');
        $data['testimonial_section_title'] = settings()->get('testimonial_section_title');

        return $this->responseWithSuccess('Settings retrieved successfully!', $data);
    }

    public function updateTestimonialSettings(UpdateTestimonialSettingsRequest $request)
    {
        settings()->set($request->validated());

        settings()->save();

        return $this->responseWithSuccess('Settings updated successfully!');
    }

    public function getAdvancedSettings()
    {
        $editor = DotenvEditor::load();

        $data['stripe_public_key'] = $editor->getKey('STRIPE_KEY');
        $data['stripe_private_key'] = $editor->getKey('STRIPE_SECRET');

        return $this->responseWithSuccess('Advanced settings retrieved successfully!', $data);
    }

    /**
     * @throws \Brotzka\DotenvEditor\Exceptions\DotEnvException
     */
    public function updateAdvancedSettings(UpdateAdvancedSettingsRequest $request)
    {
        $editor = DotenvEditor::load();
        $editor->setKey('STRIPE_KEY', $request->stripe_public_key);
        $editor->setKey('STRIPE_SECRET', $request->stripe_private_key);
        $editor->save();

        return $this->responseWithSuccess('Advanced settings updated successfully!');
    }

    public function getCustomHtmlSettings()
    {
        $data['custom_html'] = settings()->get('custom_html');

        return $this->responseWithSuccess('Settings retrieved successfully!', $data);
    }

    public function updateCustomHtmlSettings(UpdateCustomHtmlSettingsRequest $request)
    {
        settings()->set($request->validated());

        settings()->save();

        return $this->responseWithSuccess('Settings updated successfully!');
    }
}
