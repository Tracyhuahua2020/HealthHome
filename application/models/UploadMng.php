<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UploadMng extends CI_Model {
	public function UploadMng()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$config['upload_path']      = './uploads/';
        $config['allowed_types']    = 'gif|jpg|png';
        $config['max_size']     	= 2048;
        $config['max_width']        = 1024;
        $config['max_height']       = 1024;
        $this->load->library('upload', $config);
	}

	public function upload_cover_img()
	{	
		if (!$this->upload->do_upload('coverimg'))
		{
			return false;
		}

		$data = $this->upload->data();
		$coverimg_name = 'cover_'.date('YmdHis', time()).strval(mt_rand(100000, 999999)).$data['file_ext'];
		$coverimg_path = $data['file_path'].'../imgs/cover/';
		$ret = rename($data['full_path'], $coverimg_path.$coverimg_name);
		if ($ret === false)
		{
			return false;
		}

		return '/imgs/cover/'.$coverimg_name;
	}

	public function upload_head_img()
	{
		if (!$this->upload->do_upload('headimg'))
		{
			return false;
		}

		$data = $this->upload->data();
		$headimg_name = 'head_'.date('YmdHis', time()).strval(mt_rand(100000, 999999)).$data['file_ext'];
		$headimg_path = $data['file_path'].'../imgs/head/';
		$ret = rename($data['full_path'], $headimg_path.$headimg_name);
		if ($ret === false)
		{
			return false;
		}

		return '/imgs/head/'.$headimg_name;
	}

	public function upload_article_img()
	{
		if (!$this->upload->do_upload('articleimg'))
		{
			return false;
		}

		$data = $this->upload->data();
		$articleimg_name = 'article_'.date('YmdHis', time()).strval(mt_rand(100000, 999999)).$data['file_ext'];
		$articleimg_path = $data['file_path'].'../imgs/article/';
		$ret = rename($data['full_path'], $articleimg_path.$articleimg_name);
		if ($ret === false)
		{
			return false;
		}

		return '/imgs/article/'.$articleimg_name;
	}
}