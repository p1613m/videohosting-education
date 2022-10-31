<?php

namespace App\Models;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'video_path',
        'cover_path',
        'status',
    ];

    static $statuses = [
        'on-check',
        'publish',
        'rejected',
        'archived',
    ];

    /**
     * Video's likes
     *
     * @return HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(VideoLike::class, 'video_id', 'id');
    }

    /**
     * Video's comments
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(VideoComment::class, 'video_id', 'id');
    }

    /**
     * Video's user relationship
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Has user like on this video
     *
     * @return bool
     */
    public function hasLike(): bool
    {
        return $this->likes()->where('user_id', Auth::id())->exists();
    }

    /**
     * Upload video file
     *
     * @param UploadedFile|null $file
     * @return void
     */
    public function uploadVideo(?UploadedFile $file): void
    {
        if($file) {
            if($this->video_path) {
                Storage::delete($this->video_path);
            }

            $this->video_path = $file->store('public/videos');
            $this->save();
        }
    }

    /**
     * Upload cover image
     *
     * @param UploadedFile|null $file
     * @return void
     */
    public function uploadCover(?UploadedFile $file): void
    {
        if($file) {
            if($this->cover_path) {
                Storage::delete($this->cover_path);
            }

            $this->cover_path = $file->store('public/covers');
            $this->save();
        }
    }

    /**
     * Mutator for video file url
     *
     * @return Application|UrlGenerator|string
     */
    public function getVideoUrlAttribute(): string|UrlGenerator|Application
    {
        return url(Storage::url($this->video_path));
    }

    /**
     * Mutator for cover image url
     *
     * @return Application|UrlGenerator|string
     */
    public function getCoverUrlAttribute(): string|UrlGenerator|Application
    {
        return url(Storage::url($this->cover_path));
    }
}
