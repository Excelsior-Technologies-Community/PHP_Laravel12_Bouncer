<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'collection_name' => 'required|string|max:255',
        ]);

        $path = $request->file('file')->store('media', 'public');

        $media = Media::create([
            'user_id' => auth()->id(),
            'model_type' => $request->model_type,
            'model_id' => $request->model_id,
            'collection_name' => $request->collection_name,
            'name' => pathinfo($path, PATHINFO_FILENAME),
            'file_name' => basename($path),
            'mime_type' => $request->file('file')->getClientMimeType(),
            'disk' => 'public',
            'size' => $request->file('file')->getSize(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true, 'media' => $media]);
    }

    public function destroy(Media $media)
    {
        Storage::disk('public')->delete($media->file_name);
        $media->delete();

        return back()->with('success', 'Media deleted successfully');
    }
}
