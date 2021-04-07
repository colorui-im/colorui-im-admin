<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\UploadRequest;
use Ramsey\Uuid\Uuid;

class UploadController extends Controller
{
    public function image(UploadRequest $request)
    {
        $path = $request->file('file')->store('chat/images', 'public');

        return json_encode(['code' => 0, 'msg' => '', 'data' => ['src' => asset('storage/' . $path)]]);

    }

    public function file(UploadRequest $request)
    {

        $path = $request->file('file')->store('chat/files', 'public');

        return json_encode(['code' => 0, 'msg' => '', 'data' => ['src' => asset('storage/' . $path), 'name' => $request->file('file')->getClientOriginalName()]]);

    }
    public function video(UploadRequest $request)
    {

        $path = $request->file('file')->store('chat/videos', 'public');

        return json_encode(['code' => 0, 'msg' => '', 'data' => ['src' => asset('storage/' . $path), 'name' => $request->file('file')->getClientOriginalName()]]);

    }
    public function fileAudio(UploadRequest $request)
    {

        $path = $request->file('file')->store('chat/audios', 'public');

        return json_encode(['code' => 0, 'msg' => '', 'data' => ['src' => asset('storage/' . $path), 'name' => $request->file('file')->getClientOriginalName()]]);

    }

    public function audio(UploadRequest $request)
    {
        // $path = $request->file('file')->store('chat/files', 'public');
        $data = str_replace('data:audio/mp3;base64,', '', $request->audio);  
        $data = base64_decode($data);
        $uuid = Uuid::uuid4();
        $filename = $uuid.'.mp3';
        \Storage::disk('public')->put('chat/audios/'.$filename,$data);
        $path = 'chat/audios/'.$filename;

        return json_encode(['code' => 0, 'msg' => '', 'data' => ['src' => asset('storage/' . $path), 'name' => '']]);
    }
}
