<?php
namespace App\Services\Media;

use App\Jobs\ProcessVideoJob;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VideoUploadService
{
    public function upload(Post $post, $file)
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $tempPath = $file->storeAs(
            'tmp/videos',
            $filename,
            'public'
        );

        $media = $post->media()->create([
            'file_path' => $tempPath,
            'file_type' => 'video',
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'status' => 'pending',
        ]);

        Log::info('dispatching job', ['media_id' => $media->id]);
        ProcessVideoJob::dispatch($media->id);

        return $media;
    }
}