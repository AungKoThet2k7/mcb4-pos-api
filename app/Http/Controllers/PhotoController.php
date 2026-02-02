<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Http\Resources\PhotoResource;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhotoRequest $request)
    {
        $file = Storage::put('/', $request->file('photo'));

        $photo = Photo::create(['url' => $file]);

        return response()->json([
            'message' => 'Photo uploaded successfully',
            'data' => new PhotoResource($photo),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($url)
    {
        // Delete photo from Storage
        Storage::delete($url);

        // Delete Photo from Db
        Photo::where('url', $url)->delete();

        return response()->json([
            'message' => 'Photo deleted successfully',
        ]);
    }
}
