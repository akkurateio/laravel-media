<?php

namespace Akkurate\LaravelMedia\Http\Controllers\Api;

use Akkurate\LaravelMedia\Http\Resources\Type as TypeResource;
use Akkurate\LaravelMedia\Http\Resources\TypeCollection;
use Akkurate\LaravelMedia\Models\Type;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class TypeController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return TypeCollection
     *
     */
    public function index()
    {
        return new TypeCollection(
            QueryBuilder::for(Type::class)
           ->allowedFilters(['code','name','description','priority','is_active'])
           ->allowedSorts(['code','name','description','priority','is_active'])
           ->allowedIncludes(['items'])
           ->jsonPaginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $uuid
     * @param  Request $request
     * @return JsonResponse
     */
    public function store($uuid, Request $request)
    {
        $type = Type::create($request->all());

        return response()->json($type, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  $uuid
     * @param  Type $type
     * @return TypeResource
     */
    public function show($uuid, Type $type)
    {
        return new TypeResource($type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $uuid
     * @param  Request $request
     * @param  Type $type
     * @return TypeResource
     */
    public function update($uuid, Request $request, Type $type)
    {
        $type->update($request->all());

        return new TypeResource($type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $uuid
     * @param  Type $type
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($uuid, Type $type)
    {
        $type->delete();

        return response()->json(null, 204);
    }
}
