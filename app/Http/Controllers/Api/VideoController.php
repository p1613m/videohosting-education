<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class VideoController extends Controller
{
    /**
     * Create video
     *
     * @param VideoRequest $request
     * @return Response
     */
    public function store(VideoRequest $request): Response
    {
        $videoData = [
            'video_path' => $request->file('video_file')->store('videos'),
            'cover_path' => $request->file('cover_file')->store('covers'),
        ];

        Auth::user()->videos()->create($videoData + $request->validated());

        return response()->noContent();
    }
}
