<?php

namespace App\Models;

use CodeIgniter\Model;

class MKategori extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'kategori';
    protected $primaryKey       = 'kat_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['kat_nama', 'kat_slug', 'kat_status', 'kat_created_at', 'kat_created_by', 'kat_updated_at', 'kat_updated_by', 'kat_deleted_at', 'kat_deleted_by'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'kat_created_at';
    protected $updatedField  = 'kat_updated_at';
    protected $deletedField  = 'kat_deleted_at';

    // Validation
    protected $validationRules = [
        'kat_nama' => [
            'label' => 'Kategori',
            'rules' => 'required|string|max_length[255]'
        ],
        'kat_status' => [
            'label' => 'Status',
            'rules' => 'required|integer|in_list[1,2]'
        ],
    ];
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

    public function getKategori()
    {
        $builder = $this->table('kategori');
        $builder->select('*');
        $builder->where('kat_status', 2);
        $builder->where('kat_deleted_at', null);

        // Use table aliases in the subquery
        $subquery = $this->db->table('post')
            ->select('COUNT(*)')
            ->where('post_kategori_id = kategori.kat_id')
            ->where('post_status = 2')
            ->where('post_jenis = 1')
            ->getCompiledSelect();

        $builder->select("($subquery) as jumlah_post", false);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function incrementViewCount($kategoriId)
    {
        $builder = $this->builder();
        $builder->set('kat_view', 'kat_view + 1', false);
        $builder->where('kat_id', $kategoriId);
        $builder->update();

        return $this->affectedRows() > 0;
    }

    function beforeInsert($data)
    {
        $data['data']['kat_created_at'] = date('Y-m-d H:i:s');
        $data['data']['kat_created_by'] = AuthUser()->id;
        return $data;
    }
    function beforeUpdate($data)
    {
        $data['data']['kat_updated_at'] = date('Y-m-d H:i:s');
        $data['data']['kat_updated_by'] = AuthUser()->id;
        return $data;
    }
    function afterDelete($data)
    {
        $id = $data['id'][0];
        $db = \Config\Database::connect();
        $builder = $db->table('kategori');
        $builder->set('kat_deleted_by', AuthUser()->id);
        $builder->where('kat_id', $id);
        $builder->update();
    }
}
