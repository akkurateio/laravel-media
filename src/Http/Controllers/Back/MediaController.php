<?php

namespace Akkurate\LaravelMedia\Http\Controllers\Back;

use Illuminate\View\View;
use Akkurate\LaravelCore\Http\Controllers\Controller;

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
