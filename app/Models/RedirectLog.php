<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedirectLog extends Model
{
    use HasFactory;

    protected $table = 'redirect_logs';
    protected $fillable = ['user_ip', 'user_agent', 'header_refer', 'query_params', 'redirect_id'];


    public function saveLog($data)
    {
        $redirect = RedirectLog::create($data);
        return $redirect;
    }
}
