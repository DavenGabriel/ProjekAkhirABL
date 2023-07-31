<?php
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


class Barang extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Barang_model', 'barang');

        $this->methods['index_get']['limit'] = 10;
    }

    public function index_get()
    {
        $kode = $this->get('Kode');
        if ( $kode === null)
        {
            $barang = $this->barang->getBarang();
        }else{
            $barang = $this->barang->getBarang($kode);
        }
        
        if ($barang) 
        {
            $this->response([ 
                'status' => true,
                'data' => $barang
            ], REST_Controller::HTTP_OK); 
        }else{
            $this->response([ 
                'status' => true,
                'message' => 'Kode NOT FOUND'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_delete()
    {
        $kode = $this-> delete('Kode');

        if ($kode === null)
        {
            $this->response([ 
                'status' => true,
                'message' => 'provide a kode'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }else{
            if ($this->barang->deleteBarang($kode) > 0)
            {
                //berhasil
                $this->response([ 
                    'status' => true,
                    'Kode' => $kode,
                    'message' => 'KODE BARANG BERHASIL DIHAPUS!!!.'
                ], REST_Controller::HTTP_OK);   
            }else{
                //kode barang tidak ada
                $this->response([ 
                    'status' => false,
                    'message' => 'KODE NOT FOUND !!!'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }


    public function index_post()
    {
        $data = [
            'Nama_Barang' => $this->post('Nama_Barang'),
            'Jenis' => $this->post('Jenis'),
            'Harga' => $this->post('Harga'),
            'Stok' => $this->post('Stok')
        ];

        if ($this-> barang->createBarang($data) > 0)
        {
            $this->response([ 
                'status' => true,
                'message' => 'BARANG BARU TELAH DITAMBAHKAN!!!.'
            ], REST_Controller::HTTP_CREATED);
        }else{
            $this->response([ 
                'status' => false,
                'message' => 'GAGAL MENAMBAHKAN BARANG !!!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function index_put()
    {
        $kode = $this-> put('Kode');
        $data = [
            'Nama_Barang' => $this->put('Nama_Barang'),
            'Jenis' => $this->put('Jenis'),
            'Harga' => $this->put('Harga'),
            'Stok' => $this->put('Stok')
        ];

        if ($this->barang->updateBarang($data, $kode) > 0)
        {
            $this->response([ 
                'status' => true,
                'message' => 'SUCCESS TO UPDATE !!!.'
            ], REST_Controller::HTTP_OK);
        }else{
            $this->response([ 
                'status' => false,
                'message' => 'FAILED TO UPDATE !!!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }



}