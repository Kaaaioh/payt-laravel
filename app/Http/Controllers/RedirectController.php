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
use Illuminate\Support\Facades\DB;

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
        $redirectLog = new RedirectLog();
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

    public function logs(Request $request)
    {
        $redirectCode = $request->route('redirect');

        $redirectLogs = RedirectLog::select('*')
            ->join('redirects', 'redirects.id', 'redirect_logs.redirect_id')
            ->where('redirects.code', $redirectCode)
            ->get();
        return $redirectLogs;
    }


    public function stats(Request $request)
    {
        $redirectCode = $request->route('redirect');

        $total_accesses = RedirectLog::join('redirects', 'redirects.id', 'redirect_logs.redirect_id')
            ->where('redirects.code', $redirectCode)
            ->count();

        $uniques_accesses = RedirectLog::join('redirects', 'redirects.id', 'redirect_logs.redirect_id')
            ->where('redirects.code', $redirectCode)
            ->distinct('user_ip')
            ->count('user_ip');

        $top_referrers = RedirectLog::join('redirects', 'redirects.id', 'redirect_logs.redirect_id')
            ->where('redirects.code', $redirectCode)
            ->whereNotNull('header_refer')
            ->select('header_refer', DB::raw('COUNT(*) as count'))
            ->groupBy('header_refer')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $last_10_days_access = RedirectLog::join('redirects', 'redirects.id', 'redirect_logs.redirect_id')
            ->where('redirects.code', $redirectCode)
            ->whereDate('redirect_logs.created_at', '>=', now()->subDays(10))
            ->selectRaw('DATE(redirect_logs.created_at) as date, COUNT(*) as total, COUNT(DISTINCT redirect_logs.user_ip) as unique_ips')
            ->groupByRaw('DATE(redirect_logs.created_at)')
            ->orderBy('date')
            ->get();

        return [
            'total_accesses' => $total_accesses,
            'acessos_unicos' => $uniques_accesses,
            'top_referrers' => $top_referrers,
            'acessos_ultimos_10_dias' => $last_10_days_access
        ];
    }
}
