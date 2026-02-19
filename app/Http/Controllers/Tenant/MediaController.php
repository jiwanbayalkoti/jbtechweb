<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::where('tenant_id', auth()->user()->tenant_id)
            ->latest()
            ->paginate(24);
        return view('tenant.media.index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|max:10240', // 10MB per file
            'title' => 'nullable|string|max:255',
        ]);

        $files = $request->file('files');
        $tenantId = auth()->user()->tenant_id;
        $singleTitle = $request->input('title');
        $count = 0;

        foreach ($files as $file) {
            $path = $file->store('tenant/' . $tenantId, 'public');
            Media::create([
                'tenant_id' => $tenantId,
                'title' => count($files) === 1 ? $singleTitle : null,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
            $count++;
        }

        $message = $count === 1 ? 'File uploaded' : $count . ' files uploaded';
        return redirect()->back()->with('success', $message);
    }

    public function uploadEditorImage(Request $request)
    {
        $request->validate(['file' => 'required|image|max:5120']);
        $tenantId = auth()->user()->tenant_id;
        $path = $request->file('file')->store('tenant/' . $tenantId, 'public');
        Media::create([
            'tenant_id' => $tenantId,
            'title' => null,
            'file_name' => $request->file('file')->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $request->file('file')->getClientOriginalExtension(),
            'mime_type' => $request->file('file')->getMimeType(),
            'file_size' => $request->file('file')->getSize(),
        ]);
        return response()->json(['url' => asset('storage/' . $path)]);
    }

    public function destroy(Media $medium)
    {
        if ($medium->tenant_id !== auth()->user()->tenant_id) abort(403);
        Storage::disk('public')->delete($medium->file_path);
        $medium->delete();
        return response()->json(['success' => true, 'message' => 'File deleted']);
    }
}
