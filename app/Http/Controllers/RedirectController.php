<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use App\Models\RedirectLog;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use App\Http\Requests\StoreValidateRequest;
use App\Http\Requests\updateValidateRequest;

class RedirectController extends Controller
{
    protected $redirectModel;

    public function __construct(Redirect $redirectModel)
    {
        $this->redirectModel = $redirectModel;
    }

    public function redirect(Request $request, $code)
    {
        $redirect_info = $this->redirectModel->getByCode($code);

        $ip = $request->ip();
        $user_agent = $request->userAgent();
        $referer = $request->header('referer');
        $query_params  = $request->getQueryString();

        $data_insert =
            [
                'user_ip' =>  $ip,
                'redirect_id' => $redirect_info['id'],
                'user_agent' => $user_agent,
                'header_refer' =>  $referer,
                'query_params' => $query_params
            ];
        $redirectLog = new RedirectLog();
        $redirectLog->saveLog($data_insert);

        return redirect($redirect_info["url_destino"]);
    }
    public function index()
    {
        return $this->redirectModel->listRedirect();
    }

    public function store(StoreValidateRequest $request)
    {
        $data = $request->all();
        $validated = $request->safe()->only(['url_destino']);
        return $this->redirectModel->createRedirect($validated);
    }

    public function update(updateValidateRequest $request, $code)
    {
        $data = $request->all();
        $validated = $request->safe()->only(['status', 'url_destino']);
        return $this->redirectModel->updateRedirect($code, $validated);
    }

    public function destroy($code)
    {
        return $this->redirectModel->deleteRedirect($code);
    }


}
