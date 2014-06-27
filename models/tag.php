<?php

class tag
{
	private $BLOG = null;
	public $id = null;
	public $bean = null;
	
	public function __construct ($blog, $id='')
	{
		$this->BLOG = $blog;
		
		if (!$idINT = intval($id))
		{
			$tag = R::dispense('tag');
			$tag->name = $id;
			$tag->position = count(R::findAll('tag'));
			$this->id = R::store($tag);
		}
		else
			$this->id = $idINT;
		
		$this->bean = R::load('tag', $this->id);
	}
	
	public function editPosition($position)
	{
		$this->bean->position = $position;
		R::store($this->bean);
	}
		
	public function attachTo ($post, $order='')
	{
		if ($order)
			$post->tagOrder[$this->id] = $order;
		R::store($post);
		R::associate($post, $this->bean);
	}
	
	public function removeFrom ($post)
	{
		if ($post->tagOrder)
			unset($post->tagOrder[$this->id]);
		R::store($post);
		R::unassociate($post, $this->bean);
	}
	
	public function delete ()
	{		
		$posts = R::related($this->bean, 'post');
		
		foreach ($posts as $p) 
		{
			if ($p->tagOrder)
				unset($p->tagOrder[$id]);
			R::store($p);
		}
		
		R::clearRelations($this->bean, 'post');
		R::trash($this->bean);
		unset($this);
	}
	
	public static function exists($name)
	{
		if ($c = R::findOne('tag', ' name = :catName', array('catName' => $name)))
			return $c->id;
	}
	
	public static function getAttachmentsFrom ($post)
	{
		return R::related($post, 'tag');
	}
	
	public static function getAttachmentsOrderFrom ($post)
	{
		return $post->tagOrder;
	}
	
	public static function explore ($post=null)
	{
		if (!is_null($post))
		{
			return R::unrelated($post, 'tag');
		}
		else
			return R::findAll('tag', ' ORDER BY position ASC');
	}

}