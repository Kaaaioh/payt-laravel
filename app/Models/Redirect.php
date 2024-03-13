<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hashids\Hashids;

class Redirect extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'redirects';
    protected $fillable = ['code', 'status', 'url_destino', 'last_access, created_at, updated_at'];

    public function listRedirect()
    {
        return Redirect::select('code', 'status', 'url_destino', 'last_access', 'created_at', 'updated_at')
            ->whereNull('deleted_at')
            ->get();
    }

    public function createRedirect($data)
    {
        $hash = new Hashids('DBAMDASMDALVMÇREPGJOERGPÇSM');
        $redirect = Redirect::create($data);
        $code = $hash->encode($redirect->id);
        $redirect->update(['code' => $code]);

        return $redirect;
    }

    public function updateRedirect($code, $data)
    {
        $redirect = Redirect::where('code', $code)->firstOrFail();
        $redirect->update($data);
        return $redirect;
    }

    public function deleteRedirect($code)
    {
        $redirect = Redirect::where('code', $code)->firstOrFail();
        $redirect->delete();
        return $redirect;
    }

    public function getByCode($code)
    {
        $redirect = Redirect::whereNull('deleted_at')
            ->where('code', $code)->firstOrFail();
        return $redirect;
    }
}
