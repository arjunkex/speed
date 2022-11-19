<?php

namespace App\Http\Controllers\Central;

use Exception;
use App\Models\SettingImage;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Setting\SettingImageResource;
use App\Http\Requests\Setting\StoreSettingImageRequest;
use App\Http\Requests\Setting\UpdateSettingImageRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class CentralSettingImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $settingImages = SettingImage::query();

        if ($request->type) {
            $settingImages = $settingImages->where('type', $request->type);
        }

        return SettingImageResource::collection($settingImages->latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreSettingImageRequest  $request
     * @param  ImageService  $imageService
     * @return JsonResponse
     */
    public function store(StoreSettingImageRequest $request, ImageService $imageService)
    {
        $imageNameWithStringFolderPath = $imageService->uploadImageFileAndGetPath($request->image, 'settings');

        SettingImage::create(
            $request->safe()->except(['image', 'points']) + [
                'image' => $imageNameWithStringFolderPath,
                'points' => json_encode($request->points),
            ]
        );

        return $this->responseWithSuccess('Image uploaded successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  SettingImage  $settingImage
     * @return SettingImageResource
     */
    public function show(SettingImage $settingImage)
    {
        return new SettingImageResource($settingImage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateSettingImageRequest  $request
     * @param  SettingImage  $settingImage
     * @param  ImageService  $imageService
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function update(UpdateSettingImageRequest $request, SettingImage $settingImage, ImageService $imageService)
    {
        if (Str::contains($request->image, 'http')) {
            $settingImage->update(
                $request->safe()->except(['image', 'points']) + [
                    'points' => json_encode($request->points),
                ]
            );

            return $this->responseWithSuccess('Image updated successfully!');
        }

        $imageNameWithStringFolderPath = $imageService->uploadImageFileAndGetPath($request->image, 'settings');

        $settingImage->update(
            $request->safe()->except(['image', 'points']) + [
                'points' => json_encode($request->points),
                'image' => $imageNameWithStringFolderPath,
            ]
        );

        return $this->responseWithSuccess('Image updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  SettingImage  $settingImage
     * @param  ImageService  $imageService
     * @return JsonResponse
     */
    public function destroy(SettingImage $settingImage, ImageService $imageService)
    {
        $imageService->checkImageExistsAndDelete($settingImage->image, 'settings');
        $settingImage->delete();

        return $this->responseWithSuccess('Image deleted successfully!');
    }

    /**
     * search resource from storage.
     *
     * @param  Request  $request
     * @return AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        $term = $request->term;
        $query = SettingImage::query();

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $query->where(function ($query) use ($term) {
            $query->where('title', 'Like', '%' . $term . '%')
                ->orWhere('description', 'Like', '%' . $term . '%')
                ->orWhere('name', 'Like', '%' . $term . '%')
                ->orWhere('type', 'Like', '%' . $term . '%');
        });

        return SettingImageResource::collection($query->latest()->paginate($request->perPage));
    }
}
