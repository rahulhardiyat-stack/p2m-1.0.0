<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaController extends Controller
{
    private ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    public function index(Request $request)
    {
        $query = Media::with('user')
            ->when($request->folder, fn($q) => $q->where('folder', $request->folder))
            ->when($request->search, fn($q) => $q->where('original_name', 'like', "%{$request->search}%"))
            ->when($request->type, fn($q) => $q->where('mime_type', 'like', $request->type . '%'))
            ->latest();

        $media = $query->paginate(24)->withQueryString();

        $folders = Media::select('folder')
            ->distinct()
            ->orderBy('folder')
            ->pluck('folder');

        if ($request->expectsJson()) {
            return response()->json($media);
        }

        return view('admin.media.index', compact('media', 'folders'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'nullable|file|max:10240',
            'file' => 'nullable|file|max:10240',
            'folder' => 'nullable|string|max:100',
        ]);

        $folder = $request->input('folder', '/');
        $uploaded = [];
        $urls = [];

        $files = $request->file('files');
        if (!$files && $request->hasFile('file')) {
            $files = [$request->file('file')];
        }

        if (!$files) {
            return response()->json(['error' => 'No files uploaded'], 400);
        }

        foreach ($files as $file) {
            $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $mediaFolder = ltrim($folder, '/');
            $storePath = 'media' . ($mediaFolder ? '/' . $mediaFolder : '');
            $path = "{$storePath}/{$filename}";

            // Compress images before storing
            if (str_starts_with($file->getMimeType(), 'image/')) {
                $image = $this->imageManager->read($file->getRealPath());

                // Resize if too large
                if ($image->width() > 1920 || $image->height() > 1080) {
                    $image->scaleDown(1920, 1080);
                }

                $fullPath = storage_path("app/public/{$path}");
                if (!file_exists(dirname($fullPath))) {
                    mkdir(dirname($fullPath), 0755, true);
                }
                $image->toJpeg(85)->save($fullPath);
                $size = filesize($fullPath);
                $width = $image->width();
                $height = $image->height();
            }
            else {
                $file->storeAs($storePath, $filename, 'public');
                $size = $file->getSize();
                $width = null;
                $height = null;
            }

            $media = Media::create([
                'user_id' => auth()->id(),
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'url' => asset("storage/{$path}"),
                'folder' => $folder,
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'size' => $size,
                'width' => $width,
                'height' => $height,
            ]);

            // Auto-sync with Gallery for all images
            if (str_starts_with($file->getMimeType(), 'image/')) {
                \App\Models\Gallery::updateOrCreate(
                    ['file_path' => $path],
                    [
                        'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                        'type' => 'photo',
                        'album' => 'Imported',
                        'is_active' => true,
                        'language' => app()->getLocale(),
                    ]
                );
            }

            $uploaded[] = $media;
            $urls[] = $media->url;
        }

        return response()->json([
            'success' => true,
            'files' => $uploaded,
            'data' => $urls,
            'message' => count($uploaded) . ' file berhasil diupload!',
        ]);
    }
    public function destroy(Media $media)
    {
        try {
            $filePath = $media->path;
            
            // Delete from storage
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            
            // Delete associated Gallery entries
            \App\Models\Gallery::where('file_path', $filePath)
                ->orWhere('file_path', 'storage/' . $filePath)
                ->delete();
            
            // Delete DB record
            $media->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Media berhasil dihapus secara permanen.'
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error deleting media: " . $e->getMessage());
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal menghapus media: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Gagal menghapus media.');
        }

        return back()->with('success', 'Media berhasil dihapus permanen.');
    }

    public function folder(Request $request)
    {
        $folders = Media::select('folder')->distinct()->orderBy('folder')->pluck('folder');
        return response()->json($folders);
    }

    public function picker(Request $request)
    {
        $query = Media::query()->latest();
        
        if ($request->search) {
            $query->where('original_name', 'like', "%{$request->search}%");
        }
        
        if ($request->type) {
            $query->where('mime_type', 'like', $request->type . '%');
        }

        $media = $query->paginate(18);

        if ($request->ajax()) {
            return view('admin.media._picker_content', [
                'media' => $media,
                'total' => $media->total()
            ])->render();
        }

        return view('admin.media.picker', compact('media'));
    }

    public function json($id)
    {
        $item = Media::findOrFail($id);
        return response()->json([
            'id' => $item->id,
            'filename' => $item->original_name,
            'url' => $item->url,
            'path' => $item->path,
            'size' => $item->size_formatted,
            'dimensions' => $item->width && $item->height ? "{$item->width} x {$item->height}" : '-',
            'mime' => $item->mime_type,
            'created_at' => $item->created_at->format('d M Y H:i'),
            'delete_url' => route('admin.media.destroy', $item->id),
            'extension' => $item->extension,
        ]);
    }

    public function jsonByPath(Request $request)
    {
        $path = $request->path; // path from gallery->file_path or similar
        if (!$path) return response()->json(['error' => 'No path provided'], 400);

        // Sanitize path: remove starting 'storage/' or '/' if exists
        $cleanPath = preg_replace('/^(\/?storage\/|\/)/', '', $path);

        // Try to find in Media table using the clean path
        $item = Media::where('path', 'like', "%{$cleanPath}%")->first();
        
        if ($item) {
            return $this->json($item->id);
        }

        // Fallback: Get real info from filesystem (storage/app/public/...)
        $fullPath = storage_path('app/public/' . $cleanPath);
        $exists = file_exists($fullPath);
        
        $size = '-';
        $dimensions = '-';
        $mime = 'image/jpeg';

        if ($exists) {
            $bytes = @filesize($fullPath);
            if ($bytes !== false) {
                if ($bytes >= 1048576) { $size = number_format($bytes / 1048576, 1) . ' MB'; }
                elseif ($bytes >= 1024) { $size = number_format($bytes / 1024, 0) . ' KB'; }
                else { $size = $bytes . ' B'; }
            }

            $info = @getimagesize($fullPath);
            if ($info) {
                $dimensions = $info[0] . ' x ' . $info[1];
                $mime = $info['mime'];
            }
        }

        return response()->json([
            'id' => $item ? $item->id : null,
            'filename' => $item ? $item->original_name : basename($cleanPath),
            'url' => $item ? $item->url : asset('storage/' . $cleanPath),
            'path' => $item ? $item->path : $cleanPath,
            'size' => $item ? $item->size_formatted : $size,
            'dimensions' => $item ? ($item->width . ' x ' . $item->height) : $dimensions,
            'mime' => $item ? $item->mime_type : $mime,
            'delete_url' => $item ? route('admin.media.destroy', $item->id) : null,
        ]);
    }
}
