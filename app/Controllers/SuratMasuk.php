<?php 
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
class SuratMasuk extends ResourceController
{
    protected $modelName = 'App\Models\SuratMasukModel';
    protected $format = 'json';

    public function index(){
        return $this->respond([
            'statusCode' => 200,
            'message'    => 'OK',
            'data'       => $this->model->orderBy('id_surat_masuk', 'DESC')->findAll()
        ], 200);
    }
 
    public function show($id = null)
    {
        return $this->respond([
            'statusCode' => 200,
            'message'    => 'OK',
            'data'       => $this->model->find($id)
        ], 200);
    }

    public function create()
    {
        if ($this->request)
        {
            //get request from Vue Js
            if($this->request->getJSON()) {

                $json = $this->request->getJSON();

                $date = $json->tanggal_surat;
                $date = date('Y-m-d', strtotime($date));

                $post = $this->model->insert([
                    'id_surat_masuk'    => uniqid(),
                    'nomor_surat'       => $json->nomor_surat,
                    'asal_surat'        => $json->asal_surat,
                    'isi_surat'         => $json->isi_surat,
                    'tanggal_surat'     => $date,
                    'user'              => 'admin'
                ]);

            } else {

                //get request from PostMan and more
                $date = $this->request->getPost('tanggal_surat');
                $date = date('Y-m-d', strtotime($date));

                $post = $this->model->insert([
                    'id_surat_masuk'    => uniqid(),
                    'nomor_surat'       => $this->request->getPost('nomor_surat'),
                    'asal_surat'        => $this->request->getPost('asal_surat'),
                    'isi_surat'         => $this->request->getPost('isi_surat'),
                    'tanggal_surat'     => $date,
                    'user'              => 'admin'
                ]);

            }

            return $this->respond([
                'statusCode' => 201,
                'message'    => 'Data Berhasil Disimpan!'
            ], 201);
        }
    }

    public function update($id = null)
    {
        //model
        $post = $this->model;

        if ($this->request)
        {
            //get request from Vue Js
            if($this->request->getJSON()) {
            
                $json = $this->request->getJSON();
                
                $post->update($json->id, [
                    'title'     => $json->title,
                    'content'   => $json->content
                ]);

            } else {

                //get request from PostMan and more
                $data = $this->request->getRawInput();
                $post->update($id, $data);

            }

            return $this->respond([
                'statusCode' => 200,
                'message'    => 'Data Berhasil Diupdate!',
            ], 200);
        }
    }

    public function delete($id = null)
    {
        $post = $this->model->find($id);

        if($post) {

            $this->model->delete($id);

            return $this->respond([
                'statusCode' => 200,
                'message'    => 'OK'
            ], 200);

        }
    }

}
