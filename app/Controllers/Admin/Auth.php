<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if (session()->get('user_logged_in')) {
            return redirect()->to('/admin');
        }
        return view('admin/login');
    }

    public function authenticate()
    {
        // Clear old session files
        $this->clearOldSessions();
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->authenticate($username, $password);

        if ($user) {
            session()->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'full_name' => $user['full_name'],
                'role' => $user['role'],
                'user_logged_in' => true,
                'admin_logged_in' => true
            ]);
            return redirect()->to('/admin');
        }

        return redirect()->back()->with('error', 'Username atau password salah');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login');
    }

    private function clearOldSessions()
    {
        $sessionPath = WRITEPATH . 'session';
        if (is_dir($sessionPath)) {
            $files = glob($sessionPath . '/ci_session*');
            $now = time();
            foreach ($files as $file) {
                if (is_file($file) && ($now - filemtime($file)) > 3600) {
                    unlink($file);
                }
            }
        }
    }
}