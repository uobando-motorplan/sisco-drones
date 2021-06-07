<?php

namespace App\Http\Controllers;

use App\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $folders = Folder::whereSegment(Folder::DRONES)
            ->orderBy('name')
            ->get();

        return view('folders.index', compact('folders'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Folder  $folder
     * @return \Illuminate\Http\Response
     */
    public function show(Folder $folder)
    {
        $this->authorize('view', $folder);

        return view('folders.show', compact('folder'));
    }
}
