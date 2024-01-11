<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table = "user";
    protected $primaryKey = "user_id";
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_nama', 'user_username', 'user_password', 'user_level', 'user_status', 'user_created_at', 'user_updated_at', 'user_updated_by', 'user_deleted_at', 'user_deleted_by'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'user_created_at';
    protected $updatedField  = 'user_updated_at';
    protected $deletedField  = 'user_deleted_at';

    // Validation
    // Aturan validasi untuk create
    protected $validationRulesCreate = [
        'user_nama' => [
            'label' => 'Nama',
            'rules' => 'required|string|max_length[255]',
        ],
        'user_username' => [
            'label' => 'Username',
            'rules' => 'required|string|max_length[255]|is_unique[user.user_username]',
        ],
        'user_password' => [
            'label' => 'Password',
            'rules' => 'required|string|max_length[255]',
        ],
        'confirm_password' => [
            'label' => 'Confirm Password',
            'rules' => 'required|string|max_length[255]|matches[user_password]',
        ],
        'user_level' => [
            'label' => 'Level',
            'rules' => 'required|integer|in_list[1,2]',
        ],
        'user_status' => [
            'label' => 'Username',
            'rules' => 'required|integer|in_list[1,2]',
        ]
    ];
    // Aturan validasi untuk update
    protected $validationRulesUpdate = [];
    public function setValidationRulesUpdate($userId)
    {
        $this->validationRulesUpdate = [
            'user_nama' => [
                'label' => 'Nama',
                'rules' => 'required|string|max_length[255]',
            ],
            'user_username' => [
                'label' => 'Username',
                'rules' => "required|string|max_length[255]|is_unique[user.user_username,user_id,{$userId}]",
            ],
            'user_level' => [
                'label' => 'Level',
                'rules' => 'required|integer|in_list[1,2]',
            ],
            'user_status' => [
                'label' => 'Status',
                'rules' => 'required|integer|in_list[1,2]',
            ]
        ];
    }

    // Aturan validasi untuk reset password
    protected $validationRulesResetPassword = [
        'user_password' => [
            'label' => 'Password',
            'rules' => 'required|string|max_length[255]',
        ],
        'confirm_password' => [
            'label' => 'Confirm Password',
            'rules' => 'required|string|max_length[255]|matches[user_password]',
        ],
    ];

    // Fungsi untuk validasi create data
    public function validateCreate($data)
    {
        return $this->setValidationRules($this->validationRulesCreate)->save($data);
    }

    // Fungsi untuk validasi update data
    public function validateUpdate($data)
    {
        return $this->setValidationRules($this->validationRulesUpdate)->save($data);
    }

    // Fungsi untuk validasi reset password
    public function validateResetPassword($data)
    {
        return $this->setValidationRules($this->validationRulesResetPassword)->save($data);
    }

    // protected $validationRules = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['beforeInsert'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['beforeUpdate'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = ['afterDelete'];

    public function beforeInsert($data)
    {
        $data['data']['user_password'] = password_hash($data['data']['user_password'], PASSWORD_BCRYPT);
        $data['data']['user_created_at'] = date('Y-m-d H:i:s');
        $data['data']['user_created_by'] = AuthUser()->id;
        return $data;
    }
    public function beforeUpdate($data)
    {
        if (isset($data['data']['user_password'])) {
            $data['data']['user_password'] = password_hash($data['data']['user_password'], PASSWORD_BCRYPT);
        }
        $data['data']['user_updated_at'] = date('Y-m-d H:i:s');
        $data['data']['user_updated_by'] = AuthUser()->id;
        return $data;
    }
    public function afterDelete($data)
    {
        $id = $data['id'][0];
        $builder = $this->table('user');
        $builder->set('user_deleted_by', AuthUser()->id);
        $builder->where('user_id', $id);
        $builder->update();
    }
}
