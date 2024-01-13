<?php

namespace App\Models;

use App\Controllers\Back\Auth;
use CodeIgniter\Model;
use Config\Services;

class MKomentar extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'post_komen';
    protected $primaryKey       = 'komen_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['komen_nama', 'komen_post_id', 'komen_email', 'komen_komentar', 'komen_status', 'komen_reply_id', 'komen_created_at', 'komen_updated_at', 'komen_updated_by', 'komen_deleted_at', 'komen_deleted_by'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'komen_created_at';
    protected $updatedField  = 'komen_updated_at';
    protected $deletedField  = 'komen_deleted_at';

    // Validation
    protected $validationRules = [
        'komen_nama' => [
            'label' => 'Nama',
            'rules' => 'required|max_length[255]|string|alpha_numeric_space'
        ],
        'komen_post_id' => [
            'label' => 'Post',
            'rules' => 'required|integer'
        ],
        'komen_email' => [
            'label' => 'Email',
            'rules' => 'required|valid_email|max_length[255]'
        ],
        'komen_komentar' => [
            'label' => 'Komentar',
            'rules' => 'required|string'
        ],
        'komen_status' => [
            'label' => 'Status',
            'rules' => 'required|integer|in_list[1,2]'
        ]
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

    public function getCommentsAndReplies(int $postId)
    {
        $encrypter = Services::encrypter();
        $builder = $this->table('post_komen');
        $builder->select('*');
        $builder->where('komen_post_id', $postId);
        $builder->where('komen_status', 2);
        $builder->where('komen_reply_id', null);
        $query = $builder->get();

        $comments = $query->getResult();

        foreach ($comments as &$comment) {
            $comment->replies = $this->getReplies($postId, $comment->komen_id);
            $komen_id = $encrypter->encrypt($comment->komen_id);
            $comment->komen_id = bin2hex($komen_id);
        }

        return $comments;
    }

    public function getReplies(int $postId, int $parentCommentId)
    {
        $builder = $this->table('post_komen');
        $builder->select('*');
        $builder->where('komen_status', 2);
        $builder->where('komen_post_id', $postId);
        $builder->where('komen_reply_id', $parentCommentId);
        $query = $builder->get();

        return $query->getResult();
    }

    public function countCommentsByPostId($postId)
    {
        return $this->where('komen_post_id', $postId)
            ->countAllResults();
    }

    public function beforeInsert($data)
    {
        $encrypter = Services::encrypter();
        if ($data['data']['komen_reply_id'] != null) {
            $data['data']['komen_reply_id'] = $encrypter->decrypt(hex2bin($data['data']['komen_reply_id']));
        }
        $data['data']['komen_created_at'] = date('Y-m-d H:i:s');
        return $data;
    }
    public function beforeUpdate($data)
    {
        $data['data']['komen_updated_at'] = date('Y-m-d H:i:s');
        $data['data']['komen_updated_by'] = AuthUser()->id;
        return $data;
    }
    public function afterDelete($data)
    {
        $id = $data['id'][0];
        $builder = $this->table('post_komen');
        $builder->set('komen_deleted_by', AuthUser()->id);
        $builder->where('komen_id', $id);
        $builder->update();
    }

    public function findData($id = null)
    {
        $builder = $this->db->table('post_komen')
            ->select('*')
            ->join('post', 'post_id = komen_post_id')
            ->where('komen_deleted_at', null);
        if ($id) {
            $builder->where('komen_id', $id);
        }
        return $builder->get()->getFirstRow('array');
    }

    public function search($keyword)
    {
        return $this->table('post_komen')->like('komen_nama', $keyword)->orlike('komen_komentar', $keyword);
    }
}
