<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneralSetting\StoreGeneralSettingRequest;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Models\GeneralSetting;
use App\Services\ImageService;
use Illuminate\Http\Request;


class GeneralController extends Controller
{
    protected $imageService;
    // define middleware
    public function __construct(ImageService $imageService)
    {
        $this->middleware('can:general-settings', ['only' => ['updateGeneralSettings']]);

        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGeneralSettings()
    {
        $query = GeneralSetting::get();
        $settings = [
            'companyName' => $query->where('key', 'company_name')->first()->value,
            'companyTagline' => $query->where('key', 'company_tagline')->first()->value,
            'email' => $query->where('key', 'email_address')->first()->value,
            'phone' => $query->where('key', 'phone_number')->first()->value,
            'address' => $query->where('key', 'address')->first()->value,
            'clientPrefix' => $query->where('key', 'client_prefix')->first()->value,
            'supplierPrefix' => $query->where('key', 'supplier_prefix')->first()->value,
            'employeePrefix' => $query->where('key', 'employee_prefix')->first()->value,
            'proCatPrefix' => $query->where('key', 'product_cat_prefix')->first()->value,
            'proSubCatPrefix' => $query->where('key', 'product_sub_cat_prefix')->first()->value,
            'productPrefix' => $query->where('key', 'product_prefix')->first()->value,
            'expCatPrefix' => $query->where('key', 'exp_cat_prefix')->first()->value,
            'expSubCatPrefix' => $query->where('key', 'exp_sub_cat_prefix')->first()->value,
            'purchasePrefix' => $query->where('key', 'pur_prefix')->first()->value,
            'purchaseReturnPrefix' => $query->where('key', 'pur_return_prefix')->first()->value,
            'quotationPrefix' => $query->where('key', 'quotation_prefix')->first()->value,
            'invoicePrefix' => $query->where('key', 'invoice_prefix')->first()->value,
            'invoiceReturnPrefix' => $query->where('key', 'invoice_return_prefix')->first()->value,
            'adjustmentPrefix' => $query->where('key', 'adjustment_prefix')->first()->value,
            'currency' => new CurrencyResource(Currency::where('id', (int) $query->where('key', 'default_currency')->first()->value)->first()),
            'language' => $query->where('key', 'default_language')->first()->value,

            'logo' => global_asset($query->where('key', 'logo')->first()->value),
            'blackLogo' => global_asset($query->where('key', 'logo_black')->first()->value),
            'smallLogo' => global_asset($query->where('key', 'small_logo')->first()->value),
            'favicon' => global_asset($query->where('key', 'favicon')->first()->value),
            'copyright' => $query->where('key', 'copyright')->first()->value,
            'defaultClientSlug' => $query->where('key', 'default_client_slug')->first()->value,
            'defaultAccountSlug' => $query->where('key', 'default_account_slug')->first()->value,
            'defaultVatRateSlug' => $query->where('key', 'default_vat_rate_slug')->first()->value,
        ];

        return $settings;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateGeneralSettings(StoreGeneralSettingRequest $request)
    {
        // get settings data
        $allSettings = GeneralSetting::get();

        // upload logo
        $logoName = $allSettings->where('key', 'logo')->first()->value;
        if ($request->logo) {
            if ($logoName) {
                $this->imageService->checkImageExistsAndDelete($logoName);
            }
            $logoName = $this->imageService->uploadImageAndGetPath($request->logo);
        }

        // upload black logo
        $blackLogoName = $allSettings->where('key', 'logo_black')->first()->value;
        if ($request->blackLogo) {
            if ($blackLogoName) {
                $this->imageService->checkImageExistsAndDelete($blackLogoName);
            }
            $blackLogoName = $this->imageService->uploadImageAndGetPath($request->blackLogo);
        }

        // upload small logo
        $smallLogoName = $allSettings->where('key', 'small_logo')->first()->value;
        if ($request->smallLogo) {
            if ($smallLogoName) {
                $this->imageService->checkImageExistsAndDelete($smallLogoName);
            }
            $smallLogoName = $this->imageService->uploadImageAndGetPath($request->smallLogo);
        }

        // upload favicon
        $favicon = $allSettings->where('key', 'favicon')->first()->value;
        if ($request->favicon) {
            if ($favicon) {
                $this->imageService->checkImageExistsAndDelete($favicon);
            }
            $favicon = $this->imageService->uploadImageAndGetPath($request->favicon);
        }

        // update general settings
        $allSettings->where('key', 'company_name')->first()->update(['value' => clean($request->companyName)]);
        $allSettings->where('key', 'company_tagline')->first()->update(['value' => clean($request->companyTagline)]);
        $allSettings->where('key', 'email_address')->first()->update(['value' => $request->emailAddress]);
        $allSettings->where('key', 'phone_number')->first()->update(['value' => $request->phoneNumber]);
        $allSettings->where('key', 'address')->first()->update(['value' => clean($request->address)]);
        $allSettings->where('key', 'client_prefix')->first()->update(['value' => clean($request->clientPrefix)]);
        $allSettings->where('key', 'supplier_prefix')->first()->update(['value' => clean($request->supplierPrefix)]);
        $allSettings->where('key', 'employee_prefix')->first()->update(['value' => clean($request->employeePrefix)]);
        $allSettings->where('key', 'product_cat_prefix')->first()->update(['value' => clean($request->proCatPrefix)]);
        $allSettings->where('key', 'product_sub_cat_prefix')->first()->update(['value' => clean($request->proSubCatPrefix)]);
        $allSettings->where('key', 'product_prefix')->first()->update(['value' => clean($request->productPrefix)]);
        $allSettings->where('key', 'exp_cat_prefix')->first()->update(['value' => clean($request->expCatPrefix)]);
        $allSettings->where('key', 'exp_sub_cat_prefix')->first()->update(['value' => clean($request->expSubCatPrefix)]);
        $allSettings->where('key', 'pur_prefix')->first()->update(['value' => clean($request->purchasePrefix)]);
        $allSettings->where('key', 'pur_return_prefix')->first()->update(['value' => clean($request->purchaseReturnPrefix)]);
        $allSettings->where('key', 'quotation_prefix')->first()->update(['value' => clean($request->quotationPrefix)]);
        $allSettings->where('key', 'invoice_prefix')->first()->update(['value' => clean($request->invoicePrefix)]);
        $allSettings->where('key', 'invoice_return_prefix')->first()->update(['value' => clean($request->invoiceReturnPrefix)]);
        $allSettings->where('key', 'adjustment_prefix')->first()->update(['value' => clean($request->adjustmentPrefix)]);
        $allSettings->where('key', 'default_currency')->first()->update(['value' => clean($request->currency['id'])]);
        $allSettings->where('key', 'default_language')->first()->update(['value' => clean($request->language)]);
        $allSettings->where('key', 'logo')->first()->update(['value' => $logoName]);
        $allSettings->where('key', 'logo_black')->first()->update(['value' => $blackLogoName]);
        $allSettings->where('key', 'small_logo')->first()->update(['value' => $smallLogoName]);
        $allSettings->where('key', 'favicon')->first()->update(['value' => $favicon]);
        $allSettings->where('key', 'copyright')->first()->update(['value' => clean($request->copyrightText)]);
        $allSettings->where('key',
            'default_client_slug')->first()->update(['value' => clean($request->defaultClient['slug'])]);
        $allSettings->where('key',
            'default_account_slug')->first()->update(['value' => clean($request->defaultAccount['slug'])]);
        $allSettings->where('key',
            'default_vat_rate_slug')->first()->update(['value' => clean($request->defaultVatRate['slug'])]);

        return redirect()->back()->withSuccess('Settings updated successfully!');
    }
}
