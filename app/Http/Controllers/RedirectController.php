<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use App\Http\Requests\StoreValidateRequest;

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
        return $this->redirectModel->createRedirect($data);
    }

    public function update(Request $request, $code)
    {
        $data = $request->all();
        return $this->redirectModel->updateRedirect($code, $data);
    }

    public function destroy($code)
    {


        return $this->redirectModel->deleteRedirect($code);
    }
}
