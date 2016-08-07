<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CommentMng extends CI_Model {

	private $table_all_info = 'comment_all_info';
	private $table_comment = 'comment';
	private $table_like = 'comment_like';

	public function CommentMng()
	{
		parent::__construct();
		$this->load->model('DBOptMng', 'db_opt_mng');
	}

	public function add($article_id, $content, $parent_id = '')
	{
		$id = get_uuid();
		$arr_values = array(
			'id' => $id,
			'user_name' => get_user_name(),
			'article_id' => $article_id,
			'comment_time' => date('y-m-d H:i:s', time()),
			'parent_id' => $parent_id,
			'content' => $content);
		$ret = $this->db_opt_mng->insert($this->table_comment, $arr_values);
		if ($ret === false)
		{
			return false;
		}
		return $id;
	}

	public function remove($id)
	{
		$arr_where = array('comment_id' => $id, 'user_name' => get_user_name());
		$ret = $this->db_opt_mng->delete($this->table_like, $arr_where);
		if ($ret === false)
		{
			return false;
		}

		$arr_where = array('id' => $id);
		return $this->db_opt_mng->delete($this->table_comment, $arr_where);
	}

	public function like($comment_id)
	{
		$arr_values = array(
			'comment_id' => $comment_id,
			'user_name' => get_user_name(),
			'opt_type' => 1,
			'opt_time' => date('y-m-d H:i:s', time()));
		return $this->db_opt_mng->insert($this->table_like, $arr_values);
	}

	public function unlike($comment_id)
	{
		$arr_values = array(
			'comment_id' => $comment_id,
			'user_name' => get_user_name(),
			'opt_type' => 2,
			'opt_time' => date('y-m-d H:i:s', time()));
		return $this->db_opt_mng->insert($this->table_like, $arr_values);
	}

	public function get_article_comment($article_id, $check = true)
	{
		$arr_where = array('article_id' => $article_id);
		$ret = $this->db_opt_mng->select($this->table_all_info, $arr_where);
		if ($ret === false || $check === false)
		{
			return $ret;
		}

		foreach ($ret as $comment_info) 
		{
			$comment_info['like'] = $this->check_like($comment_info['id']) ? 1 : 0;
			$comment_info['unlike'] = $this->check_unlike($comment_info['id']) ? 1 : 0;
		}
		return $ret;
	}

	public function check_like($comment_id)
	{
		$arr_where = array(
			'comment_id' => $comment_id, 
			'user_name' => get_user_name(),
			'opt_type' => 1);
		$count = $this->db_opt_mng->get_count($this->table_like, $arr_where);
		return $count > 0 ? true : false;
	}

	public function check_unlike($comment_id)
	{
		$arr_where = array(
			'comment_id' => $comment_id, 
			'user_name' => get_user_name(),
			'opt_type' => 2);
		$count = $this->db_opt_mng->get_count($this->table_like, $arr_where);
		return $count > 0 ? true : false;
	}
}