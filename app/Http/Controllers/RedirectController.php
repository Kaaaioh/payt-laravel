<?php

namespace App\Http\Controllers;

use App\Models\Redirect;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    protected $redirectModel;

    public function __construct(Redirect $redirectModel)
    {
        $this->redirectModel = $redirectModel;
    }

    public function index()
    {
        return $this->redirectModel->list();
    }

    public function store(Request $request)
    {
        $data = $request->all();
        return $this->redirectModel->createRedirect($data);
    }

    public function update(Request $request, $code)
    {
        $data = $request->all();
        return $this->redirectModel->createOrUpdateRedirect($code, $data);
    }

    public function destroy($code)
    {
        return $this->redirectModel->deleteRedirect($code);
    }
}
