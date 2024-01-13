<?php

namespace App\Models;

use CodeIgniter\Model;

class MMedia extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'media';
    protected $primaryKey       = 'media_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['media_nama', 'media_path', 'media_slug'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'media_created_at';
    protected $updatedField  = 'media_updated_at';
    protected $deletedField  = 'media_deleted_at';

    // Validation
    protected $validationRules = [];

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

    function beforeInsert($data)
    {
        $data['data']['media_created_at'] = date('Y-m-d H:i:s');
        $data['data']['media_created_by'] = AuthUser()->id;
        return $data;
    }
    function beforeUpdate($data)
    {
        $data['data']['media_updated_at'] = date('Y-m-d H:i:s');
        $data['data']['media_updated_by'] = AuthUser()->id;
        return $data;
    }
    function afterDelete($data)
    {
        $id = $data['id'][0];
        $db = \Config\Database::connect();
        $builder = $db->table('media');
        $builder->set('media_deleted_by', AuthUser()->id);
        $builder->where('media_id', $id);
        $builder->update();
    }

    public function search($keyword)
    {
        return $this->table('media')->like('media_nama', $keyword)->orderBy('media_id','DESC');
    }
}
