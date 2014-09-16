<?php

namespace Core\BLL;
class Sitemap extends Base
{
	public function getAll()
	{
		return $this->application->db->web->sql2array('SELECT * FROM `sitemap`', array(), 'id');
	}

	public function getById($sitemapId)
	{
		return $this->application->db->web->selectRow('SELECT * FROM `sitemap` WHERE `id`=?', array($sitemapId));
	}

	public function getBlocks()
	{
		return $this->application->db->web->sql2array('SELECT * FROM `sitemap_blocks`', array());
	}

	public function add($parentId, $layout, $url, $title)
	{
		$this->application->db->web->query(
			'INSERT INTO `sitemap` (parent_id, layout, name, title)
			VALUES(?,?,?,?)',
			array($parentId,
				$layout,
				$url,
				$title)
		);
		return $this->application->db->web->lastInsertId();
	}

	public function update($id, array $data)
	{
		$this->application->db->web->query(
			'UPDATE `sitemap` SET parent_id=?, layout=?, name=?, title=? , template=? WHERE id=?',
			array(
				$data['parentId'],
				$data['layout'],
				$data['url'],
				$data['title'],
				$data['template'],
				$id
			)
		);
		return $this->application->db->web->lastInsertId();
	}
}