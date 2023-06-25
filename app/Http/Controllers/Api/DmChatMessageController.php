<?php

namespace App\Http\Controllers\Api;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\User;
use App\Models\Dmmessage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DmChatMessageController extends Controller
{
    private $u;
    private $d;
    public function __construct()
    {
        $this->u = new User;
        $this->d = new Dmmessage;
    }

    public function add(Request $request): JsonResponse
    {
        $myId = $request->myUserId;
        $friendId = $request->friendId;

        $myUser = $this->u->getUserLang($myId);
        $frdUser= $this->u->getUserLang($friendId);

        Log::info($myUser);

        $bodytext =  $request->myMessage.'&source_lang='.$myUser[0]->language.'&target_lang='.$frdUser[0]->language;
        Log::info('text='.$bodytext);

        $client = new Client();
        $res = $client->request(
            'POST',
            'https://api-free.deepl.com/v2/translate',
            [
                'headers'=>
                [
                    'Authorization' => 'DeepL-Auth-Key _DEEPLKEY',
                    'User-Agent' => 'YourApp/1.2.3',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'body' => 'text='.$bodytext,
            ],
        );

        $message = $request->myMessage;
        $transmessage =json_decode((string)($res->getBody()), true);

        $this->d->addMessage($myId, $friendId, $message, $transmessage["translations"][0]["text"]);


        return response()->json(['result' => $myId]);
    }

    public function read(Request $request): JsonResponse
    {
        $myId = $request->myUserId;
        $friendId = $request->friendId;

        $dm = (string)  $this->d->getReadAll($myId, $friendId);

        $f = $this->u->getUser($friendId);

        return response()->json($dm);
    }
}
