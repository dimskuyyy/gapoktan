<?php

namespace App\Controllers\Back;

use App\Models\MKomentar;
use App\Libraries\Datatable;

class Komentar extends BaseController
{
    protected $helpers = ['text'];
    protected $komentarModel;

    public function __construct()
    {
        $this->komentarModel = new MKomentar();
    }
    public function index()
    {
        return view('dashboard/komentar/index');
    }

    public function list()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $columns = [
                ['dt' => 'id', 'cond' => 'komen_id', 'select' => 'komen_id'],
                ['dt' => 'nama', 'cond' => 'komen_nama', 'select' => 'komen_nama'],
                ['dt' => 'status', 'cond' => 'komen_status', 'select' => 'komen_status'],
                ['dt' => 'komentar', 'cond' => 'komen_komentar', 'select' =>
                'komen_komentar', 'formatter' => function ($d) {
                    return ellipsize($d, 100);
                }],
                [
                    'dt' => 'tgl_simpan', 'cond' => 'komen_created_at',
                    'select' => 'komen_created_at',
                    'formatter' => function ($d) {
                        return $d != null ? date('d-m-Y H:i', strtotime($d)) : '';
                    }
                ],
                [
                    'dt' => 'tgl_update', 'cond' => 'komen_updated_at',
                    'select' => 'komen_updated_at',
                    'formatter' => function ($d) {
                        return $d != null ? date('d-m-Y H:i', strtotime($d)) : '';
                    }
                ],
            ];

            $model1 = $this->komentarModel;
            $model2 = new MKomentar();
            $model1 = $model1->where('komen_deleted_at', null);
            $model2 = $model2->where('komen_deleted_at', null);
            $result = (new Datatable())->run($model1, $model2, $req->getVar('datatables'), $columns);
            return $this->response->setJSON($result);
        }
    }

    public function form()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $id = $_POST['id'] ?? null;
            if ($id != null) {
                $data = $this->komentarModel->findData($id);
                if (empty($data['komen_id'])) {
                    $result = jsonFormat(false, 'Komentar tidak ditemukan');
                    return $this->response->setJSON($result);
                }
            }

            $tmp = [];

            if ($id != null) {
                $tmp['data'] = $data;
            }

            return view('dashboard/komentar/form', $tmp);
        }
    }

    public function save()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $id = $_POST['id'] ?? null;
            if ($id != null) {
                $find = $this->komentarModel->find($id);
                if (empty($find['komen_id'])) {
                    // Data tidak ditemukan, kirim respons error
                    $result = jsonFormat(false, 'Komentar tidak ditemukan');
                    return $this->response->setJSON($result);
                }
            }
            $data = [
                'komen_status' => $req->getVar('status'),
            ];
            if ($this->komentarModel->update($id, $data)) {
                $result = jsonFormat(true, 'Komentar berhasil diupdate');
            } else {
                $result = jsonFormat(false, $this->komentarModel->errors());
            }
            return $this->response->setJSON($result);
        }
    }

    public function delete()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $id = $req->getVar('id');

            if (empty($id)) {
                // ID kosong, kirim respons error
                $result = jsonFormat(false, 'Komentar tidak ditemukan');
                return $this->response->setJSON($result);
            }

            $find = $this->komentarModel->find($id);
            if (empty($find['komen_id'])) {
                // Data tidak ditemukan, kirim respons error
                $result = jsonFormat(false, 'Komentar tidak ditemukan');
                return $this->response->setJSON($result);
            }

            // menghapus komentar
            $data = $this->komentarModel->where('komen_reply_id', $id)->findAll();
            if ($this->komentarModel->delete($id)) {
                foreach ($data as $row) {
                    $this->komentarModel->delete($row['komen_id']);
                }
                $result = jsonFormat(true, 'Komentar berhasil dihapus');
            } else {
                $result = jsonFormat(false, 'Komentar gagal dihapus');
            }
            return $this->response->setJSON($result);
        }
    }
}
