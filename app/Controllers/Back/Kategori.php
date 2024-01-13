<?php

namespace App\Controllers\Back;

use App\Models\MKategori;
use App\Libraries\Datatable;
use CodeIgniter\I18n\Time;

class Kategori extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new MKategori();
    }

    public function index()
    {
        return view('dashboard/kategori/index');
    }

    public function list()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $columns = [
                ['dt' => 'id', 'cond' => 'kat_id', 'select' => 'kat_id'],
                ['dt' => 'slug', 'cond' => 'kat_slug', 'select' => 'kat_slug'],
                ['dt' => 'kategori', 'cond' => 'kat_nama', 'select' => 'kat_nama'],
                ['dt' => 'status', 'cond' => 'kat_status', 'select' => 'kat_status'],
                [
                    'dt' => 'tgl_simpan', 'cond' => 'kat_created_at',
                    'select' => 'kat_created_at',
                    'formatter' => function ($d) {
                        return $d != null ? date('d-m-Y H:i', strtotime($d)) : '';
                    }
                ],
                [
                    'dt' => 'tgl_update', 'cond' => 'kat_updated_at',
                    'select' => 'kat_updated_at',
                    'formatter' => function ($d) {
                        return $d != null ? date('d-m-Y H:i', strtotime($d)) : '';
                    }
                ],
            ];

            $model1 = $this->kategoriModel;
            $model2 = new MKategori();
            $model1 = $model1->where('kat_deleted_at', null);
            $model2 = $model2->where('kat_deleted_at', null);
            $result = (new Datatable())->run($model1, $model2, $req->getVar('datatables'), $columns);
            return $this->response->setJSON($result);
        }
    }
    public function form()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $id = $req->getVar('id') ?? null;
            if ($id != null) {
                $data = $this->kategoriModel->find($id);
                if (empty($data['kat_id'])) {
                    $result = jsonFormat(false, 'Kategori tidak ditemukan');
                    return $this->response->setJSON($result);
                }
            }

            $tmp = [];

            if ($id != null) {
                $tmp['data'] = $data;
            }

            return view('dashboard/kategori/form', $tmp);
        }
    }
    public function save()
    {
        $req = $this->request;
        if ($req->isAJAX()) {
            $id = $req->getVar('id') ?? null;
            if ($id != null) {
                $find = $this->kategoriModel->find($id);
                if (empty($find['kat_id'])) {
                    // Data tidak ditemukan, kirim respons error
                    $result = jsonFormat(false, 'Kategori tidak ditemukan');
                    return $this->response->setJSON($result);
                }
            }
            $data = [
                'kat_nama' => $req->getVar('kategori'),
                'kat_status' => $req->getVar('status'),
                'kat_slug' => url_title(Time::now()
                    ->format('Y-m-d') . ' ' . time() .
                    rand(0, 100) . ' ' . $req->getVar('kategori'), '-', true)
            ];
            if ($id != null ? $this->kategoriModel->update($id, $data) : $this->kategoriModel->insert($data)) {
                $result = jsonFormat(true, 'Kategori berhasil disimpan');
            } else {
                $result = jsonFormat(false, $this->kategoriModel->errors());
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
                $result = jsonFormat(false, 'Kategori tidak ditemukan');
                return $this->response->setJSON($result);
            }

            $find = $this->kategoriModel->find($id);
            if (empty($find['kat_id'])) {
                // Data tidak ditemukan, kirim respons error
                $result = jsonFormat(false, 'Kategori tidak ditemukan');
                return $this->response->setJSON($result);
            }

            // menghapus media
            if ($this->kategoriModel->delete($id)) {
                $result = jsonFormat(true, 'Kategori berhasil dihapus');
            } else {
                $result = jsonFormat(false, 'Kategori gagal dihapus');
            }
            return $this->response->setJSON($result);
        }
    }
}
