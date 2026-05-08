<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Crypto\KeyManager;
use App\Services\Crypto\RSACrypto;
use App\Services\Crypto\ECCCrypto;
use App\Services\Crypto\HMACService;

class PostController extends Controller
{
    public function index()
    {
        // Get only the logged-in user's posts
        $posts = Post::where('user_id', Auth::id())->latest()->get();
        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $keyManager = new KeyManager();
        $rsa = new RSACrypto();
        $hmac = new HMACService();
        $rsaKeys = $keyManager->getSystemRSAKeys();

        //  Encrypt with RSA on creation
        $titleEnc = $rsa->encrypt($request->title, $rsaKeys['public_key']);
        $contentEnc = $rsa->encrypt($request->content, $rsaKeys['public_key']);

        //  Generate MAC
        $macTag = $hmac->generateForUser([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        Post::create([
            'user_id' => Auth::id(),
            'title_encrypted' => $titleEnc,
            'content_encrypted' => $contentEnc,
            'mac_tag' => $macTag,
        ]);

        return redirect()->route('posts.index')->with('success', 'Diary entry securely saved.');
    }

    public function edit(Post $post)
    {
        if ($post->user_id !== Auth::id()) abort(403);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $keyManager = new KeyManager();
        $ecc = new ECCCrypto();
        $hmac = new HMACService();
        $eccKeys = $keyManager->getSystemECCKeys();

        //  Re-encrypt with ECC on update (Algorithm Rotation!)
        $titleEnc = $ecc->encrypt($request->title, $eccKeys['public_key']);
        $contentEnc = $ecc->encrypt($request->content, $eccKeys['public_key']);

        $macTag = $hmac->generateForUser([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $post->update([
            'title_encrypted' => json_encode($titleEnc),
            'content_encrypted' => json_encode($contentEnc),
            'mac_tag' => $macTag,
        ]);

        return redirect()->route('posts.index')->with('success', 'Diary entry updated with ECC encryption.');
    }
}
