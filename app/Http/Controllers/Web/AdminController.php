<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AdminController extends Controller
{
    /**
     * Admin auth form
     *
     * @return Application|Factory|View
     */
    public function authForm(): View|Factory|Application
    {
        return view('login');
    }

    /**
     * Admin auth
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function auth(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if(Auth::attempt($validated)) {
            if(Auth::user()->is_admin) {
                return redirect()->route('videos');
            } else {
                Auth::logout();
            }
        }

        return redirect()
            ->back()
            ->withErrors(['email' => 'Incorrect user data'])
            ->withInput($request->all());
    }

    /**
     * Admin logout
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('login');
    }

    /**
     * Show all videos
     *
     * @return Application|Factory|View
     */
    public function videos(): View|Factory|Application
    {
        $videos = Video::query()->orderBy('created_at', 'DESC')->get();

        return view('admin', [
            'videos' => $videos,
        ]);
    }

    /**
     * Show video
     *
     * @param Video $video
     * @return Application|Factory|View
     */
    public function showVideo(Video $video): View|Factory|Application
    {
        return view('video', compact('video'));
    }

    /**
     * Change video status
     *
     * @param Video $video
     * @return RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function changeStatus(Video $video): RedirectResponse
    {
        $newStatus = request()->get('status');
        if(in_array($newStatus, Video::$statuses)) {
            $video->status = $newStatus;
            $video->save();
        }

        return redirect()->back();
    }

    /**
     * Delete video
     *
     * @param Video $video
     * @return RedirectResponse
     */
    public function delete(Video $video): RedirectResponse
    {
        $video->delete();

        return redirect()->route('videos');
    }
}
