<?php

namespace Akkurate\LaravelMedia\Http\Controllers\Back;

use Illuminate\Routing\Controller;
use Illuminate\View\View;

class MediaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  $uuid
     * @return View
     */
    public function index($uuid)
    {
        return view('media::back.medias.index');
    }
}
