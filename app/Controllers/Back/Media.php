<?php

namespace App\Controllers\Back;

use App\Models\MMedia;
use CodeIgniter\I18n\Time;

class Media extends BaseController
{
    protected $helpers = ['url'];
    protected $mediaModel;

    public function __construct()
    {
        $this->mediaModel = new MMedia();
    }

    public function index()
    {
        return view('dashboard/media/index');
    }

    public function list()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $type = $req->getVar('type') ?? 1;
            $keyword = $req->getVar('keyword') ?? null;
            $page = $req->getVar('page') ?? 1;
            $perPage = $req->getVar('perPage') ?? 8;
            if ($keyword != null) {
                $data = [
                    'media' => $this->mediaModel->search($keyword)->paginate($perPage, 'default', $page),
                    'pager' => $this->mediaModel->pager
                ];
            } else {
                $data = [
                    'media' => $this->mediaModel->orderBy('media_id', 'DESC')->paginate($perPage, 'default', $page),
                    'pager' => $this->mediaModel->pager
                ];
            }
            if ($type == 1) {
                return view('dashboard/media/list', $data);
            } else {
                return view('dashboard/media/list2', $data);
            }
        }
    }
    public function form()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $id = $req->getVar('id') ?? null;
            if ($id != null) {
                $data = $this->mediaModel->find($id);
                if (empty($data['media_id'])) {
                    $result = jsonFormat(false, 'Media tidak ditemukan');
                    return $this->response->setJSON($result);
                }
            }
            $tmp = [];
            if ($id != null) {
                $tmp['data'] = $data;
            }
            return view('dashboard/media/form', $tmp);
        }
    }

    private function validationRuleMedia($id)
    {
        $validationRules = [
            'nama' => 'required',
            'path' => [
                'is_image[path]',
                'mime_in[path,image/jpg,image/jpeg,image/png]',
                'max_size[path,1024]'
            ]
        ];
        // jika add data tambahkan validation uploaded
        if ($id == null) {
            $validationRules['path'][] = 'uploaded[path]';
        }
        return $validationRules;
    }

    private function generateSlug($req, $id)
    {
        if ($id != null) {
            $slug = $req->getVar('oldSlug');
        } else {
            $slug = url_title(Time::now()
                ->format('Y-m-d') . ' ' . time() . rand(0, 100) . ' ' .
                $req->getVar('nama'), '-', true);
        }

        return $slug;
    }

    private function mediaUpload($req, $id)
    {
        if (!$req->getFile('path')->isValid()) {
            $filePath = $req->getVar('oldMedia');
        } else {
            // pindahkan file
            $filePath = $req->getFile('path')->store('media');
            // hapus file lama
            if ($id != null) {
                if (file_exists(WRITEPATH . 'uploads/' . $req->getVar('oldMedia'))) {
                    unlink(WRITEPATH . 'uploads/' . $req->getVar('oldMedia'));
                }
            }
        }
        return $filePath;
    }

    public function save()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $id = $req->getVar('id') ?? null;
            // Buat aturan validasi untuk file
            $validationRules = $this->validationRuleMedia($id);
            if ($id != null) {
                $find = $this->mediaModel->find($id);
                if (empty($find['media_id'])) {
                    // Data tidak ditemukan, kirim respons error
                    $result = jsonFormat(false, 'Media tidak ditemukan');
                    return $this->response->setJSON(['message' => $result, 'data' => null, 'id' => null]);
                }
                if (($find['media_created_by'] != AuthUser()->id) && (AuthUser()->level != 1)) {
                    $result = jsonFormat(false, 'Anda tidak memiliki izin untuk mengedit media ini');
                    return
                        $this->response->setJSON(['message' => $result, 'data' => null, 'id' => null]);
                }
            }
            // Jalankan validasi
            if (!$this->validate($validationRules)) {
                $data = null;
                $insertedID =  null;
                $result = jsonFormat(false, $this->validator->getErrors());
            } else {
                // lakukan proses upload media
                $filePath = $this->mediaUpload($req, $id);
                // cek apakah data update
                $mediaName = $req->getVar('nama');
                $slug = $this->generateSlug($req, $id);
                $data = [
                    'media_nama' => $mediaName,
                    'media_path' => $filePath,
                    'media_slug' => $slug,
                ];
                // cek apakah data update / create, save data
                if ($id != null ? $this->mediaModel->update($id, $data) : $this->mediaModel->insert($data)) {
                    // Mendapatkan ID dari data yang baru saja dimasukkan
                    $insertedID = $this->mediaModel->insertID();
                    $result = jsonFormat(true, 'Media berhasil disimpan');
                } else {
                    $result = jsonFormat(false, $this->mediaModel->errors());
                }
            }
            return $this->response->setJSON(['message' => $result, 'data' => $data, 'id' => $insertedID]);
        }
    }

    public function delete()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $id = $req->getVar('id');

            if (empty($id)) {
                // ID kosong, kirim respons error
                $result = jsonFormat(false, 'Media tidak ditemukan');
                return $this->response->setJSON($result);
            }

            $find = $this->mediaModel->find($id);
            if (empty($find['media_id'])) {
                // Data tidak ditemukan, kirim respons error
                $result = jsonFormat(false, 'Media tidak ditemukan');
                return $this->response->setJSON($result);
            }

            // Cek izin pengguna untuk menghapus media
            if (($find['media_created_by'] == AuthUser()->id) || (AuthUser()->level == 1)) {
                $result = $this->mediaModel->delete($id);
                if ($result) {
                    $result = jsonFormat(true, 'Media berhasil dihapus');
                } else {
                    $result = jsonFormat(false, 'Media gagal dihapus');
                }
            } else {
                $result = jsonFormat(false, 'Anda tidak memiliki izin untuk menghapus media ini');
            }

            return $this->response->setJSON($result);
        }
    }
}
