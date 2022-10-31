<?php

namespace App\Observers;

use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class VideoObserver
{
    /**
     * Handle the Video "deleted" event.
     *
     * @param Video $video
     * @return void
     */
    public function deleted(Video $video): void
    {
        Storage::delete($video->cover_path);
        Storage::delete($video->video_path);
    }
}
