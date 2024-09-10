<?php
// app/Http/Controllers/PhotoController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Photo;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'file' => 'required|mimes:jpeg,jpg,png,gif,svg,pdf|max:5120', // 5MB max
            'product_id' => 'required|exists:products,id'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Store the file in the 'public/photos' directory
            $path = $file->store('photos', 'public');

            // Save the photo in the database
            $photo = Photo::create(['url' => Storage::url($path)]);

            // Attach the photo to the product
            $product = Product::find($request->product_id);
            $product->photos()->attach($photo->id);

            // Retrieve the newly attached photo
            $attachedPhoto = $product->photos()->where('photos.id', $photo->id)->first();

            // Format the response
            $response = [
                'id' => $photo->id,
                'url' => Storage::url($path),
                'created_at' => $photo->created_at,
                'updated_at' => $photo->updated_at,
                'pivot' => [
                    'product_id' => $request->product_id,
                    'photo_id' => $photo->id
                ]
            ];

            return response()->json($response, 200);
        }

        return response()->json(['error' => 'File not uploaded'], 400);
    }

    public function delete(int $id)
    {

        // Find the photo by id
        $photo = Photo::find($id);

        if (!$photo) {
            return response()->json(['error' => 'Photo not found'], 404);
        }

        // Detach the photo from all related products
        $photo->products()->detach();

        // Delete the photo file from storage
        Storage::disk('public')->delete(str_replace('/storage/', '', $photo->url));

        // Delete the photo from the database
        $photo->delete();

        Log::info('Photo deleted successfully', ['photo_id' => $photo->id]);

        return response()->json(['message' => 'Photo deleted successfully'], 200);
    }
}
