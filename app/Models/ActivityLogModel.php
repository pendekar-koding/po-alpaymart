<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table = 'activity_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'username', 'role', 'activity', 'description', 'ip_address', 'user_agent', 'created_at'];
    protected $useTimestamps = false;

    public function logActivity($userId, $username, $role, $activity, $description = null)
    {
        $request = \Config\Services::request();
        
        $data = [
            'user_id' => $userId,
            'username' => $username,
            'role' => $role,
            'activity' => $activity,
            'description' => $description,
            'ip_address' => $request->getIPAddress(),
            'user_agent' => $request->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->insert($data);
    }
}