<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

class ActivityLogs extends BaseController
{
    protected $activityLogModel;

    public function __construct()
    {
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        if (!session()->get('user_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/admin/login')->with('error', 'Akses ditolak');
        }

        $search = $this->request->getGet('search');
        
        $builder = $this->activityLogModel->orderBy('created_at', 'DESC');
        
        if ($search) {
            $builder->groupStart()
                   ->like('username', $search)
                   ->orLike('activity', $search)
                   ->orLike('description', $search)
                   ->groupEnd();
        }
        
        $logs = $builder->paginate(50);
        $pager = $this->activityLogModel->pager;

        $data = [
            'logs' => $logs,
            'pager' => $pager,
            'search' => $search,
            'title' => 'Activity Logs'
        ];

        return view('admin/activity_logs/index', $data);
    }
}