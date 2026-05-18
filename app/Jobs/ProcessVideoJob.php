<?php

namespace App\Jobs;

use App\Models\Media;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $mediaId) {}

    public function handle(): void
    {
        $media = Media::find($this->mediaId);
        if (!$media) return;

        $media->update(['status' => 'processing']);

        $inputPath = storage_path('app/public/' . $media->file_path);

        if (!file_exists($inputPath)) {
            $media->update(['status' => 'failed', 'processing_error' => 'Input file missing']);
            return;
        }

        $outputFile = "posts/videos/" . uniqid() . ".mp4";
        $outputPath = storage_path('app/public/' . $outputFile);

        if (!file_exists(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0777, true);
        }

        $command = "ffmpeg -i \"$inputPath\" -vcodec libx264 -crf 28 \"$outputPath\" 2>&1";

        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            $media->update([
                'status' => 'failed',
                'processing_error' => implode("\n", $output)
            ]);
            return;
        }

        $media->update([
            'file_path' => $outputFile,
            'status' => 'completed',
            'size' => filesize($outputPath),
            'duration' => 0, // You might want to calculate the actual duration
        ]);

        if (file_exists($media->getOriginal('file_path'))) {
            unlink(storage_path('app/public/' . $media->getOriginal('file_path')));
        }
    }
}
