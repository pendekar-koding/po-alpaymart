<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['username', 'password', 'full_name', 'shop_name', 'role', 'status', 'shop_status'];
    
    protected $beforeInsert = ['hashPassword', 'lowercaseUsername'];
    protected $beforeUpdate = ['hashPassword', 'lowercaseUsername'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';



    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    protected function lowercaseUsername(array $data)
    {
        if (isset($data['data']['username'])) {
            $data['data']['username'] = strtolower($data['data']['username']);
        }
        return $data;
    }

    public function authenticate($username, $password)
    {
        $user = $this->where('username', strtolower($username))
                     ->where('status', 'active')
                     ->first();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}