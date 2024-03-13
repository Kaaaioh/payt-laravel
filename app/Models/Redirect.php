<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hashids\Hashids;

class Redirect extends Model
{
    use HasFactory;
    protected $table = 'redirects';
    protected $fillable = ['code', 'status', 'url_destino', 'last_access'];

    public function list()
    {
        return "list";
        return Redirect::select('code', 'status', 'url_destino', 'last_access')->get();
    }

    public function createOrUpdateRedirect($data)
    {
        return "createOrUpdateRedirect";
        if (isset($data['code'])) {
            $redirect = Redirect::where('code', $code)->firstOrFail();
            $redirect->update($data);
        } else {
            $redirect = Redirect::create($data);
        }

        return $redirect;
    }

    public function deleteRedirect($code)
    {
        $redirect = Redirect::where('code', $code)->firstOrFail();
        $redirect->delete();
        return $redirect;
    }



}
