<?php

namespace App\Http\Controllers\Central;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Page\PageResource;
use App\Http\Requests\Page\StorePageRequest;
use App\Http\Requests\Page\UpdatePageRequest;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return PageResource::collection(Page::latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Page\StorePageRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePageRequest $request)
    {
        $page = Page::create($request->validated());

        return $this->responseWithSuccess('Page created successfully.', new PageResource($page));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Page $page)
    {
        return $this->responseWithSuccess('Page retrieved successfully', new PageResource($page));
    }

    /**
     * Display the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function showBySlug($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return $this->responseWithSuccess('Page retrieved successfully', new PageResource($page));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Page\UpdatePageRequest  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        $page->slug = null;
        $page->update($request->validated());

        return $this->responseWithSuccess('Page updated successfully.', new PageResource($page));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return $this->responseWithSuccess('Page deleted successfully.');
    }

    /**
     * search resource from storage.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function search(Request $request)
    {
        $term = $request->term;
        $query = Page::query();

        $query->where(function ($query) use ($term) {
            $query->where('name', 'Like', '%' . $term . '%');
        });

        return PageResource::collection($query->latest()->paginate($request->perPage));
    }
}