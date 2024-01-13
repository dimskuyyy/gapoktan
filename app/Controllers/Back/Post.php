<?php

namespace App\Controllers\Back;

use App\Models\MPost;
use App\Libraries\Datatable;
use App\Models\MKategori;
use App\Models\MMedia;
use CodeIgniter\I18n\Time;

class Post extends BaseController
{
    protected $postModel;
    protected $mediaModel;

    public function __construct()
    {
        $this->postModel = new MPost();
        $this->mediaModel = new MMedia();
    }

    public function index()
    {
        return view('dashboard/post/index');
    }

    public function getDatatable()
    {
        return view('dashboard/post/data_list');
    }

    public function list()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $columns = [
                ['dt' => 'id', 'cond' => 'post_id', 'select' => 'post_id'],
                ['dt' => 'slug', 'cond' => 'post_slug', 'select' => 'post_slug'],
                ['dt' => 'judul', 'cond' => 'post_judul', 'select' => 'post_judul'],
                ['dt' => 'jenis', 'cond' => 'post_jenis', 'select' => 'post_jenis'],
                ['dt' => 'penulis', 'cond' => 'post_user_id', 'select' => 'post_user_id'],
                ['dt' => 'status', 'cond' => 'post_status', 'select' => 'post_status'],
                ['dt' => 'kategori', 'cond' => 'kat_nama', 'select' => 'kat_nama', 'formatter' => function ($d) {
                    return $d !== null ? $d : 'Tidak Ada';
                }],
                [
                    'dt' => 'tgl_simpan', 'cond' => 'post_created_at',
                    'select' => 'post_created_at', 'formatter' => function ($d) {
                        return $d != null ? date('d-m-Y H:i', strtotime($d)) : '';
                    }
                ],
                [
                    'dt' => 'tgl_publish', 'cond' => 'post_published_at',
                    'select' => 'post_published_at', 'formatter' => function ($d) {
                        return $d != null ? date('d-m-Y H:i', strtotime($d)) : '';
                    }
                ],
                [
                    'dt' => 'tgl_update', 'cond' => 'post_updated_at',
                    'select' => 'post_updated_at', 'formatter' => function ($d) {
                        return $d != null ? date('d-m-Y H:i', strtotime($d)) : '';
                    }
                ],
            ];
            $model1 = $this->postModel;
            $model2 = new MPost();
            $result = (new Datatable())->run($model1->multiData(), $model2->multiData(), $req->getVar('datatables'), $columns);
            return $this->response->setJSON($result);
        }
    }

    public function form()
    {
        $id = $this->request->getVar('id');
        if ($id != null) {
            $data = $this->postModel->find($id);
            if (empty($data)) {
                $result = jsonFormat(false, 'Post tidak temukan');
                return $this->response->setJSON($result);
            }
        }
        $tmp = [];
        $tmp['kategori'] = (new MKategori())->where('kat_status', 2)->findAll();
        if ($id != null) {
            $tmp['data'] = $data;
            $tmp['media'] = $this->mediaModel->where('media_id', $data['post_media_id'])->findAll();
        }

        return view('dashboard/post/form', $tmp);
    }


    public function getMedia()
    {
        $req = $this->request;
        if ($req->isAJAX()) {

            $tmp['key'] = $req->getVar('key');

            return view('dashboard/post/media_form', $tmp);
        }
    }

    public function getDetailMedia()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $id = $req->getVar('id');
            $data = $this->mediaModel->find($id);
            $tmp = [];
            $tmp['data'] = $data;
            return $this->response->setJSON(['message' => 'test', 'data' => $data]);
        }
    }

    public function save()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $id = $req->getVar('id') ?? null;
            $status = (AuthUser()->level != 1) ? 1 : $req->getVar('status');
            $slug = $this->generateSlug($req->getVar('jenis'), $req->getVar('judul'));
            $data = [
                'post_judul' => $req->getVar('judul'),
                'post_konten' => $this->sanitizeContent($req->getVar('konten')),
                'post_jenis' => $req->getVar('jenis'),
                'post_media_id' => $req->getVar('media'),
                'post_status' => $status,
                'post_kategori_id' => $req->getVar('kategori') ?? null,
                'post_slug' => $slug,
                'post_approve' => $req->getVar('komentar')
            ];

            if ($id != null) {
                $dataCheck = $this->postModel->find($id);
                if (empty($dataCheck['post_id'])) {
                    // Data tidak ditemukan, kirim respons error
                    $result = jsonFormat(false, 'Post tidak ditemukan');
                    return $this->response->setJSON($result);
                }
            }
            // cek apakah pernah dipublish
            if ($status == 2 && ($id == null ||
                ($id != null && empty($dataCheck['post_published_at'])
                    && empty($dataCheck['post_published_by'])))) {
                $data['post_published_at'] = date('Y-m-d H:i:s');
                $data['post_published_by'] = AuthUser()->id;
            }

            if ($id != null ? $this->postModel->update($id, $data) : $this->postModel->insert($data)) {
                $result = jsonFormat(true, 'Post berhasil disimpan');
            } else {
                $result = jsonFormat(false, $this->postModel->errors());
            }
            return $this->response->setJSON($result);
        }
    }

    private function sanitizeContent($content)
    {
        // membersihkan dari paragraf kosong
        return preg_replace("/<p>(&nbsp;|<br\s*\/?>)*<\/p>/", '', $content);
    }

    private function generateSlug($jenis, $judul)
    {
        $baseSlug = ($jenis == 1) ? url_title(Time::now()->format('Y-m-d H:i:s') . '-' . $judul, '-', true) : url_title($judul, '-', true);
        $slug = $baseSlug;
        $i = 1;

        $find = $this->postModel->where('post_slug', $slug)->findAll();
        $find = count($find) > 1 ? $find : null;
        while (!empty($find)) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        return $slug;
    }

    public function delete()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $id = $req->getVar('id');

            if (empty($id)) {
                // ID kosong, kirim respons error
                $result = jsonFormat(false, 'Post tidak ditemukan');
                return $this->response->setJSON($result);
            }

            $find = $this->postModel->find($id);
            if (empty($find['post_id'])) {
                // Data tidak ditemukan, kirim respons error
                $result = jsonFormat(false, 'Post tidak ditemukan');
                return $this->response->setJSON($result);
            }

            // Cek izin pengguna untuk menghapus posting
            if (($find['post_user_id'] == AuthUser()->id) || (AuthUser()->level == 1)) {
                $result = $this->postModel->delete($id);
                if ($result) {
                    $result = jsonFormat(true, 'Post berhasil dihapus');
                } else {
                    $result = jsonFormat(false, 'Post gagal dihapus');
                }
            } else {
                $result = jsonFormat(false, 'Anda tidak memiliki izin untuk menghapus posting ini');
            }

            return $this->response->setJSON($result);
        }
    }
}
