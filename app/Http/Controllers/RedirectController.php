<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
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
