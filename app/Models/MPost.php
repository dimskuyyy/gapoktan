<?php

namespace App\Models;

use CodeIgniter\Model;

class MPost extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'post';
    protected $primaryKey       = 'post_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['post_slug', 'post_kategori_id', 'post_judul', 'post_konten', 'post_jenis', 'post_media_id', 'post_status', 'post_user_id', 'post_view', 'post_approve', 'post_created_at', 'post_created_by', 'post_published_at', 'post_published_by', 'post_updated_at', 'post_updated_by', 'post_deleted_at', 'post_deleted_by'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'post_created_at';
    protected $updatedField  = 'post_updated_at';
    protected $deletedField  = 'post_deleted_at';

    // Validation
    protected $validationRules = [
        'post_judul' => [
            'label' => 'Nama',
            'rules' => 'required|string'
        ],
        'post_konten' => [
            'label' => 'Konten',
            'rules' => 'required|string'
        ],
        'post_jenis' => [
            'label' => 'Jenis',
            'rules' => 'required|integer|in_list[1,2]'
        ],
        'post_media_id' => [
            'label' => 'Media',
            'rules' => 'required|integer'
        ],
        'post_status' => [
            'label' => 'Status',
            'rules' => 'required|integer|in_list[1,2]'
        ],
        'post_slug' => [
            'label' => 'Slug',
            'rules' => 'required|alpha_dash'
        ],
        'post_approve' => [
            'label' => 'Status Komentar',
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

    // overidding method
    public function multiData()
    {
        return $this->builder()
            ->join('kategori', 'kat_id = post_kategori_id', 'left')
            ->join('media', 'media_id = post_media_id')
            ->join('user', 'user_id = post_user_id')
            ->where('post_deleted_at', null)
            ->where('kat_deleted_at', null);
    }

    public function getBerita(string $keyword = null, string $idKategori = null)
    {
        $builder = $this->table('post');
        $builder->select('*');
        $builder->join('kategori', 'kat_id = post_kategori_id');
        $builder->join('media', 'media_id = post_media_id');
        $builder->join('user', 'user_id = post_user_id');
        $builder->where('post_jenis', 1);
        $builder->where('post_status', 2);
        $builder->orderBy('post_published_at', 'DESC');
        $builder->where('post_deleted_at', null);

        if ($keyword != null) {
            $builder->groupStart();
            $builder->like('post_judul', $keyword);
            $builder->orLike('post_konten', $keyword);
            $builder->groupEnd();
        }

        if ($idKategori != null) {
            $builder->where('post_kategori_id', $idKategori);
        }

        $subquery = $this->db->table('post_komen')
            ->select('COUNT(*)')
            ->where('komen_post_id = post.post_id')
            ->where('komen_status = 2')
            ->getCompiledSelect();

        $builder->select("($subquery) as jumlah_komentar", false);

        return $builder;
    }
    public function findBerita($slug)
    {
        $builder = $this->table('post');
        $builder->select('*');
        $builder->join('kategori', 'kat_id = post_kategori_id');
        $builder->join('media', 'media_id = post_media_id');
        $builder->join('user', 'user_id = post_user_id');
        $builder->where('post_slug', $slug);
        $builder->where('post_jenis', 1);
        $builder->where('post_status', 2);
        $builder->where('post_deleted_at', null);
        $query = $builder->get();
        return $query->getFirstRow('array');
    }

    public function getHalaman($slug)
    {
        $builder = $this->table('post');
        $builder->select('*');
        $builder->join('media', 'media_id = post_media_id');
        $builder->where('post_slug', $slug);
        $builder->where('post_jenis', 2);
        $builder->where('post_status', 2);
        $builder->where('post_deleted_at', null);
        $query = $builder->get();
        return $query->getFirstRow('array');
    }

    public function postByTopKategori(int $id)
    {
        $builder = $this->table('post');
        $builder->select('*');
        $builder->join('kategori', 'kat_id = post_kategori_id');
        $builder->join('media', 'media_id = post_media_id');
        $builder->join('user', 'user_id = post_user_id');
        $builder->where('post_kategori_id', $id);
        $builder->where('post_deleted_at', null);
        $builder->orderby('post_view DESC');
        $builder->limit(4);
        $subquery = $this->db->table('post_komen')
            ->select('COUNT(*)')
            ->where('komen_post_id = post.post_id')
            ->where('komen_status = 2')
            ->getCompiledSelect();

        $builder->select("($subquery) as jumlah_komentar", false);
        return $builder->get()->getResultArray();
    }

    public function getPopularPosts(int $limit = 0)
    {
        $builder = $this->table('post');
        $builder->select('*');
        $builder->join('kategori', 'kat_id = post_kategori_id');
        $builder->join('media', 'media_id = post_media_id');
        $builder->join('user', 'user_id = post_user_id');
        $builder->where('post_jenis', 1);
        $builder->where('post_status', 2);
        $builder->where('post_deleted_at', null);
        $builder->orderBy('post_view', 'DESC');
        $builder->limit($limit);
        return $builder->get()->getResultArray();
    }
    public function getRecentPosts(int $limit = 0)
    {
        $builder = $this->table('post');
        $builder->select('*');
        $builder->join('kategori', 'kat_id = post_kategori_id');
        $builder->join('media', 'media_id = post_media_id');
        $builder->join('user', 'user_id = post_user_id');
        $builder->where('post_jenis', 1);
        $builder->where('post_status', 2);
        $builder->where('post_deleted_at', null);
        $builder->orderBy('post_published_at', 'DESC');
        $builder->limit($limit);

        $subquery = $this->db->table('post_komen')
            ->select('COUNT(*)')
            ->where('komen_post_id = post.post_id')
            ->where('komen_status = 2')
            ->getCompiledSelect();

        $builder->select("($subquery) as jumlah_komentar", false);

        return $builder->get()->getResultArray();
    }

    public function getPreviousPost($publishedAt)
    {
        return $this->where('post_published_at <', $publishedAt)
            ->orderBy('post_published_at', 'DESC')
            ->first();
    }

    public function getNextPost($publishedAt)
    {
        return $this->where('post_published_at >', $publishedAt)
            ->orderBy('post_published_at', 'ASC')
            ->first();
    }

    public function incrementViewCount($postId)
    {
        $builder = $this->builder();
        $builder->set('post_view', 'post_view + 1', false);
        $builder->where('post_id', $postId);
        $builder->update();

        return $this->affectedRows() > 0;
    }

    public function beforeInsert($data)
    {
        $data['data']['post_user_id'] = AuthUser()->id;
        $data['data']['post_created_at'] = date('Y-m-d H:i:s');
        $data['data']['post_created_by'] = AuthUser()->id;
        return $data;
    }

    public function beforeUpdate($data)
    {
        $data['data']['post_updated_at'] = date('Y-m-d H:i:s');
        $data['data']['post_updated_by'] = AuthUser()->id;
        return $data;
    }

    public function afterDelete($data)
    {
        $id = $data['id'][0];
        $db = \Config\Database::connect();
        $builder = $db->table('post');
        $builder->set('post_deleted_by', AuthUser()->id);
        $builder->where('post_id', $id);
        $builder->update();
    }

    public function search($keyword)
    {
        // return
        $this->builder()
            ->like('post_judul', $keyword)
            ->orLike('post_konten', $keyword)
            ->where('post_deleted_at', null)
            ->where('post_jenis', 1)
            ->where('post_status', 2)
            ->orderBy('post_judul', 'ASC');

        return $this->findAll(8);
    }
}
