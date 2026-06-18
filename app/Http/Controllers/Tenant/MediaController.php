<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    private const IMAGE_TYPES = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

    public function index()
    {
        $media = $this->tenantMediaItems(request());
        return view('tenant.media.index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|max:10240', // 10MB per file
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $files = $request->file('files');
        $tenantId = auth()->user()->tenant_id;
        $fileCount = count($files);
        $hasUploadGroupColumn = $this->mediaHasColumn('upload_group_id');
        $hasDescriptionColumn = $this->mediaHasColumn('description');
        $uploadGroupId = $fileCount > 1 && $hasUploadGroupColumn ? (string) Str::uuid() : null;
        $uploadTitle = $request->input('title');
        $uploadDescription = $request->input('description');
        $count = 0;

        foreach ($files as $file) {
            $path = $file->store('tenant/' . $tenantId, 'public');
            $data = [
                'tenant_id' => $tenantId,
                'title' => $uploadTitle,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ];

            if ($hasUploadGroupColumn) {
                $data['upload_group_id'] = $uploadGroupId;
            }

            if ($hasDescriptionColumn) {
                $data['description'] = $uploadDescription;
            }

            Media::create($data);
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

    public function preview(Media $medium)
    {
        if ($medium->tenant_id !== auth()->user()->tenant_id) abort(403);
        if (!$this->isImageMedia($medium)) abort(404);

        $hasUploadGroupColumn = $this->mediaHasColumn('upload_group_id');
        $query = Media::where('tenant_id', auth()->user()->tenant_id);
        if ($hasUploadGroupColumn && $medium->upload_group_id) {
            $query->where('upload_group_id', $medium->upload_group_id)->oldest('id');
        } else {
            $query->whereKey($medium->id);
        }

        $items = $query->get()->filter(fn (Media $item) => $this->isImageMedia($item))->values();
        $index = $items->search(fn (Media $item) => $item->id === $medium->id);

        if ($index === false) abort(404);

        $previous = $items->get($index - 1);
        $next = $items->get($index + 1);

        return response()->json([
            'id' => $medium->id,
            'title' => $medium->title ?: $medium->file_name,
            'description' => $this->mediaHasColumn('description') ? $medium->description : null,
            'url' => asset('storage/' . $medium->file_path),
            'download_url' => asset('storage/' . $medium->file_path),
            'file_name' => $medium->file_name,
            'date' => $medium->created_at->format('M d, Y'),
            'position' => $index + 1,
            'total' => $items->count(),
            'previous_id' => $previous?->id,
            'next_id' => $next?->id,
        ]);
    }

    public function destroy(Media $medium)
    {
        if ($medium->tenant_id !== auth()->user()->tenant_id) abort(403);
        Storage::disk('public')->delete($medium->file_path);
        $medium->delete();
        return response()->json(['success' => true, 'message' => 'File deleted']);
    }

    protected function tenantMediaItems(Request $request): LengthAwarePaginator
    {
        $perPage = 24;
        $page = LengthAwarePaginator::resolveCurrentPage();

        $media = Media::where('tenant_id', auth()->user()->tenant_id)->latest()->get();
        $hasUploadGroupColumn = $this->mediaHasColumn('upload_group_id');
        $hasDescriptionColumn = $this->mediaHasColumn('description');
        $items = $media
            ->groupBy(fn (Media $item) => $hasUploadGroupColumn && $item->upload_group_id ? $item->upload_group_id : 'media-' . $item->id)
            ->map(function ($group) {
                $cover = $group->first(fn (Media $item) => $this->isImageMedia($item)) ?: $group->first();
                $isAlbum = $this->mediaHasColumn('upload_group_id') && $cover->upload_group_id && $group->count() > 1;

                return (object) [
                    'type' => $isAlbum ? 'album' : 'media',
                    'cover' => $cover,
                    'count' => $group->count(),
                    'title' => $cover->title ?: ($isAlbum ? 'Album' : $cover->file_name),
                    'description' => $this->mediaHasColumn('description') ? $cover->description : null,
                    'created_at' => $group->max('created_at'),
                    'is_image' => $this->isImageMedia($cover),
                ];
            })
            ->sortByDesc('created_at')
            ->values();

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }

    protected function isImageMedia(Media $media): bool
    {
        return in_array(strtolower($media->file_type), self::IMAGE_TYPES, true);
    }

    protected function mediaHasColumn(string $column): bool
    {
        static $columns = [];

        return $columns[$column] ??= Schema::hasColumn('media', $column);
    }
}
