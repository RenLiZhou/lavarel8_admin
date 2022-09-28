<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SystemsController extends Controller
{
    public $v = 'admin.system.';

    public function adminLog(Request $request)
    {
        $admin_id = Auth::guard('admin')->id();

        $search = $request->input('search');
        $per_page = $request->input('per_page',10);

        $datas = AdminLog::query()
            ->when(!empty($search),function ($query) use ($search){
                $query->where('ip', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate($per_page);

        $search_data = [
            'search' => $search,
            'per_page' => $per_page
        ];
        return view($this->v . 'adminlog', compact('search_data', 'datas'));
    }
}
