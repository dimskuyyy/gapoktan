<?php

namespace App\Controllers\Front;

use App\Models\MMedia;
use App\Models\MSetting;
use CodeIgniter\HTTP\Response;

class MediaAccess extends BaseController
{
    protected $mediaModel;
    protected $settingModel;

    public function __construct()
    {
        $this->mediaModel = new MMedia();
    }

    public function viewMedia($slug)
    {
        $data = $this->mediaModel->where('media_slug', $slug)->first();
        // jika media tidak ada (null)
        if ($data == null) {
            // gunakan image default
            $path = WRITEPATH . 'uploads/default/' . 'default.png';
            $mimeType = mime_content_type($path);
        } else {
            $path = WRITEPATH . 'uploads/' . $data['media_path'];
            // cek apakah ada file didalam folder upload
            if (!file_exists($path) && !is_file($path)) {
                $path = WRITEPATH . 'uploads/default/' . 'default.png';
            }
            $mimeType = mime_content_type($path);
        }
        $this->response->setHeader('Content-Type', $mimeType);

        readfile($path);
    }
    public function viewLogo()
    {
        $data = $this->settingModel->where('set_status', 1)->where('set_name', 'logo')->first();
        // jika media tidak ada (null)
        if ($data == null) {
            // gunakan image default
            $path = WRITEPATH . 'uploads/default/' . 'logo.png';
            $mimeType = mime_content_type($path);
        } else {
            $path = WRITEPATH . 'uploads/' . $data['set_value'];
            // cek apakah ada file didalam folder upload
            if (!file_exists($path) && !is_file($path)) {
                $path = WRITEPATH . 'uploads/default/' . 'logo.png';
            }
            $mimeType = mime_content_type($path);
        }
        $this->response->setHeader('Content-Type', $mimeType);

        readfile($path);
    }
}
