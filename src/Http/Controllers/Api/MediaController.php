<?php

namespace Akkurate\LaravelMedia\Http\Controllers\Api;

use Akkurate\LaravelMedia\Http\Resources\Media as MediaResource;
use Akkurate\LaravelMedia\Http\Resources\MediaCollection;
use Akkurate\LaravelMedia\Http\Resources\Resource as ResourceResource;
use Akkurate\LaravelMedia\Models\Media;
use Akkurate\LaravelMedia\Models\Resource;
use Akkurate\LaravelMedia\Models\Type;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Intervention\Image\Facades\Image;
use Spatie\QueryBuilder\QueryBuilder;

class MediaController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return MediaCollection
     *
     */
    public function index()
    {
        return new MediaCollection(
            QueryBuilder::for(Media::class)
           ->allowedFilters([])
           ->allowedSorts([])
           ->allowedIncludes(['resource'])
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
        $request->validate([
            'image' => 'mimes:jpg,jpeg,png,gif,pdf,xls,xlsx'
        ]);

        $file_info = pathinfo($_FILES["image"]['name']);
        $file_mime = mime_content_type($request->file('image')->getPathName());

        // Get image file from request
        $file = $request->file('image');

        // Give the image a unique filename
        $filename = 'img-' . uniqid() . '.' . $file_info['extension'];

        // Check if resource already existing by comparing filename and file size
        if (Resource::where('md5', md5_file($file))->count()) {
            return response()->json([
                'message' => 'Cette ressource est déjà présente dans la bibliothèque.',
                'resource' => Resource::with('media')->where('md5', md5_file($file))->first()
            ], 201);
        }

        $resource = Resource::create([
            'name' => $file_info['filename'],
            'alt' => $request->alt ?? $file_info['filename'],
            'legend' => $request->legend ?? $file_info['filename'],
            'user_id' => auth()->user()->id,
            'account_id' => auth()->user()->account_id,
            'md5' => md5_file($file),
        ]);

        $image_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];

        if (in_array($file_mime, $image_mimes)) {
            $img = Image::make($file->getPathname());
            $img->fit(300);
            $resource->update([
                'thumb' => 'data:image/'.$file_info['extension'].';base64,'.base64_encode($img->stream()),
                'type_id' => Type::where('code', 'like', 'IMG')->first()->id
            ]);
        } elseif ($file_mime === 'application/pdf') {
            $resource->update([
                'thumb' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDMwIDMwIiBoZWlnaHQ9IjMwcHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAzMCAzMCIgd2lkdGg9IjMwcHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxnPjxwYXRoIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTMwLjAxNSwyOC42NjdIMFYwaDMwLjAxNVYyOC42NjcgTTIuODg0LDI1Ljc4M2gyNC4yNDRWMi44ODYgICBIMi44ODQiIGZpbGw9IiNFQTRDM0EiIGZpbGwtcnVsZT0iZXZlbm9kZCIvPjxwYXRoIGQ9Ik0xNS40MDEsNC40MDggICBjMC40MjEsMC4xODMsMC4zMjcsMC40NiwwLjE4OCwxLjc2MmMtMC4xNDMsMS4zNDktMC42MTgsMy44MDYtMS41MzUsNi4yMThjLTAuOTE4LDIuNDE1LTIuMjgsNC43ODUtMy40NjcsNi41NDIgICBjLTEuMTg1LDEuNzU1LTIuMjAxLDIuODk2LTIuOTc0LDMuNTU0Yy0wLjc3NywwLjY1OC0xLjMxNCwwLjgzMy0xLjY2NSwwLjg5M2MtMC4zNDgsMC4wNi0wLjUwNiwwLTAuNi0wLjE3NSAgIHMtMC4xMjctMC40Ny0wLjA0Ni0wLjgxOWMwLjA3OS0wLjM1MywwLjI2OC0wLjc2MywwLjgwNC0xLjI4N2MwLjU0MS0wLjUyNywxLjQyNi0xLjE3LDIuNjYxLTEuNzcyICAgYzEuMjM1LTAuNTk5LDIuODE2LTEuMTU2LDQuMTE1LTEuNTM1YzEuMjk5LTAuMzgxLDIuMzExLTAuNTg1LDMuMTk3LTAuNzQ1YzAuODg4LTAuMTYxLDEuNjQ3LTAuMjc4LDIuMzktMC4zMzYgICBjMC43NDUtMC4wNiwxLjQ3NC0wLjA2LDIuMTg2LDBjMC43MTIsMC4wNTgsMS40MDksMC4xNzUsMi4wMTEsMC4zMTljMC42MDEsMC4xNDcsMS4xMDgsMC4zMjMsMS41NTEsMC42MDIgICBjMC40NDIsMC4yNzcsMC44MjMsMC42NTgsMS4wMTIsMS4wODJjMC4xOTIsMC40MjUsMC4xOTIsMC44OTMsMC4wMzMsMS4yMjljLTAuMTU4LDAuMzM2LTAuNDc2LDAuNTQyLTAuODM5LDAuNjU3ICAgYy0wLjM2MywwLjExNy0wLjc3NSwwLjE0Ni0xLjI2NiwwYy0wLjQ5MS0wLjE0Ni0xLjA2My0wLjQ2Ny0xLjY2My0wLjg5M2MtMC42LTAuNDIzLTEuMjM0LTAuOTQ4LTIuMDU3LTEuNzY4ICAgYy0wLjgyMy0wLjgyLTEuODM3LTEuOTM0LTIuNjkxLTMuMDE1Yy0wLjg1NC0xLjA4My0xLjU1My0yLjEzNi0yLjAyOC0zLjAyOGMtMC40NzMtMC44OTMtMC43MjctMS42MjQtMC45MzMtMi4zNTcgICBjLTAuMjA2LTAuNzMxLTAuMzY0LTEuNDYyLTAuNDI3LTIuMTJjLTAuMDYzLTAuNjYtMC4wMzMtMS4yNDUsMC4wMzEtMS43MWMwLjA2My0wLjQ3LDAuMTYtMC44MiwwLjMxNy0xLjA1NCAgIGMwLjE1OC0wLjIzNSwwLjM4MS0wLjM1MiwwLjUzOS0wLjQxYzAuMTU4LTAuMDYsMC4yNTQtMC4wNiwwLjM0OC0wLjA3M2MwLjA5NC0wLjAxNCwwLjE4OC0wLjA0NCwwLjMzMywwICAgQzE1LjA2NCw0LjIwOSwxNS4yNDgsNC4zMjEsMTUuNDAxLDQuNDA4eiIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjRUE0QzNBIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBzdHJva2UtbWl0ZXJsaW1pdD0iMTAiIHN0cm9rZS13aWR0aD0iMS41NyIvPjwvZz48L3N2Zz4=',
                'type_id' => Type::where('code', 'like', 'DOC')->first()->id
            ]);
        } elseif ($file_mime = 'application/vnd.ms-excel' || 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            $resource->update([
                'thumb' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDMwIDMwIiBoZWlnaHQ9IjMwcHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAzMCAzMCIgd2lkdGg9IjMwcHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxwYXRoIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTIzLjMzNSwyMS44ODdoLTUuMDUybC0zLjQ0Ni00Ljg3NGwtMy42MjEsNC44NzRINi4xNDIgIGw2LjI5NS03LjkxN0w3LjM1NCw3LjA0Nmg1LjEyNWwyLjM4MSwzLjc2NWwyLjUzNy0zLjc2NWg1LjIxNWwtNS4zMjksNi45MjRMMjMuMzM1LDIxLjg4NyBNMzAsMjguNjUySDBWMGgzMFYyOC42NTJ6IE0yLjg4MiwyNS43NyAgaDI0LjIzMVYyLjg4NEgyLjg4MiIgZmlsbD0iIzA4NzQzQiIgZmlsbC1ydWxlPSJldmVub2RkIi8+PC9zdmc+',
                'type_id' => Type::where('code', 'like', 'DOC')->first()->id
            ]);
        }

        // Associate Media to the Resource
        $resource
            ->addMedia($file)
            ->usingFileName($filename)
            ->preservingOriginal()
            ->toMediaCollection();

        $resource->update([
            'media_id' => $resource->getFirstMedia()->id,
        ]);

        return response()->json(array_merge($resource->toArray(), ['user' => auth()->user()]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  $uuid
     * @param  Media $media
     * @return MediaResource
     */
    public function show($uuid, Media $media)
    {
        return new MediaResource($media);
    }

    public function update($uuid, Media $media, Request $request)
    {
        $resource = Resource::where('media_id', $media->id)->first();

        $resource->update([
            'name' => $request->name,
            'alt' => $request->alt ?? $request->name,
            'legend' => $request->legend ?? $request->name,
        ]);

        return response()->json(new ResourceResource($resource), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $uuid
     * @param  Media $media
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy($uuid, Media $media)
    {
        $media->delete();

        return response()->json(null, 204);
    }
}
