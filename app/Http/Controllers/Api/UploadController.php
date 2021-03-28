<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\UploadRequest;

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
}
