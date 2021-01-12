<?php

namespace Akkurate\LaravelMedia\Http\Controllers\Api;

use Akkurate\LaravelCore\Http\Controllers\Controller;
use Akkurate\LaravelMedia\Filters\FilterWithAllTags;
use Akkurate\LaravelMedia\Http\Resources\Resource as ResourceResource;
use Akkurate\LaravelMedia\Http\Resources\ResourcesCollection;
use Akkurate\LaravelMedia\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ResourceController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return ResourcesCollection
     *
     */
    public function index()
    {
        return new ResourcesCollection(QueryBuilder::for(Resource::class)
            ->allowedFilters([
                AllowedFilter::custom('tags', new FilterWithAllTags),
                AllowedFilter::scope('search'),
            ])
            ->allowedSorts([])
            ->allowedIncludes(['user','media','tags','type'])
            ->jsonPaginate()
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  $uuid
     * @param  Resource $resource
     * @return ResourceResource
     */
    public function show($uuid, Resource $resource)
    {
        return new ResourceResource($resource);
    }


    /**
     * @param $uuid
     * @param  Resource $resource
     * @param Request $request
     * @return JsonResponse
     */
    public function attachMedia($uuid, Resource $resource, Request $request)
    {
        $model = $request->model_type::find($request->model_id);
        $model->resources()->attach($resource);
        return response()->json([], 200);
    }

    /**
     * @param $uuid
     * @param Resource $resource
     * @param Request $request
     * @return JsonResponse
     */
    public function detachMedia($uuid, Resource $resource, Request $request)
    {
        $model = $request->model_type::find($request->model_id);
        $model->resources()->detach($resource);
        return response()->json([], 200);
    }

    /**
     * @param $uuid
     * @param Resource $resource
     * @param Request $request
     * @return JsonResponse
     */
    public function attachTag($uuid, Resource $resource, Request $request)
    {
        $resource->attachTag($request->tag);
        return response()->json([], 200);
    }

    /**
     * @param $uuid
     * @param Resource $resource
     * @param Request $request
     * @return JsonResponse
     */
    public function detachTag($uuid, Resource $resource, Request $request)
    {
        $resource->detachTag($request->tag);
        return response()->json([], 200);
    }

}
