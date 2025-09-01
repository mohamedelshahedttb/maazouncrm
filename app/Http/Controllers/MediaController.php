<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function serve($mediaId)
    {
        $media = Media::findOrFail($mediaId);
        $path = storage_path('app/public/' . $media->getPath());
        
        if (!file_exists($path)) {
            abort(404, 'File not found');
        }
        
        $mimeType = mime_content_type($path);
        $fileName = $media->file_name;
        
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"'
        ]);
    }

    public function download($mediaId)
    {
        $media = Media::findOrFail($mediaId);
        $path = storage_path('app/public/' . $media->getPath());
        
        if (!file_exists($path)) {
            abort(404, 'File not found');
        }
        
        $mimeType = mime_content_type($path);
        $fileName = $media->file_name;
        
        return response()->download($path, $fileName, [
            'Content-Type' => $mimeType
        ]);
    }
}
