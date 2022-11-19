<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Services\ImageService;
use Exception;
use Illuminate\Http\Request;


class BrandController extends Controller
{
    private $imageService;
    // define middleware
    public function __construct(ImageService $imageService)
    {
        $this->middleware('can:brands-management', ['except' => ['allBrands']]);
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return BrandResource::collection(Brand::latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBrandRequest $request)
    {
        try {
            // upload brand logo and set the name
            $imageName = '';
            if ($request->image) {
                $imageName = $this->imageService->uploadImageAndGetPath($request->image, 'brands');
            }

            // store brand
            Brand::create([
                'name' => $request->name,
                'code' => $request->shortCode,
                'image' => $imageName,
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Brand added successfully');
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        try {
            $brand = Brand::where('slug', $slug)->first();

            return new BrandResource($brand);
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBrandRequest $request, $slug)
    {
        $brand = Brand::where('slug', $slug)->first();

        try {
            // delete the old logo and upload the new one
            $imageName = $brand->image;
            if ($request->image) {
                if ($imageName) {
                    $this->imageService->checkImageExistsAndDelete($imageName, 'brands');
                }
                $imageName = $this->imageService->uploadImageAndGetPath($request->image, 'brands');
            }

            // update brand
            $brand->update([
                'name' => $request->name,
                'code' => $request->shortCode,
                'image' => $imageName,
                'note' => clean($request->note),
                'status' => $request->status,
            ]);

            return $this->responseWithSuccess('Brand updated successfully');
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        try {
            $brand = Brand::where('slug', $slug)->first();

            // delete the old logo and upload the new one
            $imageName = $brand->image;
            if ($imageName) {
                if ($imageName) {
                    $this->imageService->checkImageExistsAndDelete($imageName, 'brands');
                }
            }

            $brand->delete();

            return $this->responseWithSuccess('Brand deleted successfully');
        } catch (Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * search resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $term = $request->term;

        $query = Brand::where('name', 'LIKE', '%'.$term.'%')
            ->orWhere('code', 'LIKE', '%'.$term.'%')
            ->orWhere('note', 'LIKE', '%'.$term.'%')
            ->latest()->paginate($request->perPge);

        return BrandResource::collection($query);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allBrands()
    {
        $brands = Brand::where('status', 1)->latest()->get();

        return BrandResource::collection($brands);
    }
}
