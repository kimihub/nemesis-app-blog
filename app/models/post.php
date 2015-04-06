<?php

class post 
{
	private $BLOG = null;
	public $id = null;
	public $bean = null;
	
	public function __construct ($blog, $id='')
	{
		$this->id = $id;
		$this->BLOG = $blog;
		$this->bean = R::load('post', $id);
	}
		
	public function edit ($values=array())
	{
		extract($values);
		
		$this->bean->type = (isset($draft) && !isset($save) && $this->bean->type != 'post') ? 'draft':'post';
			
		$this->bean->title = $title;
		$this->bean->caption = $caption;
		$this->bean->mediaType = (isset($mediaType)? $mediaType:'thumbnails');
		$this->bean->publishDate = (is_date($publishDate) == 'DATE')? strtotime($publishDate):time();
		$this->bean->postDate = (is_date($this->bean->postDate) == 'TIMESTAMP')? $this->bean->postDate:time();	
		$this->id = R::store($this->bean);
		
		
		// begin add metas
		R::clearRelations($this->bean, 'media');
		if (isset($values['medias']))
		{
			foreach ($values['medias'] as $m)
			{
				$media = new media($this->BLOG, $m);
				$media->attachTo($this->bean);
			}
		}
		
		
		R::clearRelations($this->bean, 'category');
		
		$cats = explode(',', trim(trim($cats), ','));
		foreach ($cats as $c)
		{
			if ($cid = category::exists($c))
			{
				$cat = new category($this->BLOG, $cid);
				$cat->attachTo($this->bean);
			}
			else
			{
				$cat = new category($this->BLOG, $c);
				$cat->attachTo($this->bean);
			}
		}
		
		R::clearRelations($this->bean, 'tag');
		
		$tags = explode(',', trim(trim($tags), ','));
		foreach ($tags as $t)
		{
			if ($cid = tag::exists($t))
			{
				$tag = new tag($this->BLOG, $cid);
				$tag->attachTo($this->bean);
			}
			else
			{
				$tag = new tag($this->BLOG, $t);
				$tag->attachTo($this->bean);
			}
		}
				
		// end add metas
		
		R::store($this->bean);
	}
	
	public function delete ()
	{		
		R::clearRelations($this->bean, 'media');
		R::clearRelations($this->bean, 'category');
		R::clearRelations($this->bean, 'tag');
		R::trash($this->bean);
		unset($this);
	}
	
	public static function getList ($page=0, $limit=0)
	{
		return R::find('post',' publishDate <= "'.time().'" AND type = "post" ORDER BY publishDate, postDate DESC LIMIT '.strval($page). ','.(($limit>0)? strval($limit) : strval(POSTS_NUMBER)),array());
	}
	
	public static function getListCount ()
	{
		return ceil(R::count('post',' publishDate <= "'.time().'" AND type = "post"',array()) / (intval(POSTS_NUMBER)));
	}
	
	public static function getAll ($page=0)
	{
		return R::findAll('post',' ORDER BY publishDate, postDate DESC LIMIT '.strval($page).','.strval(POSTS_NUMBER), array());
	}
	
	public static function getAllCount ()
	{
		return ceil(R::count('post') / (intval(POSTS_NUMBER)));
	}
	
	public static function getListWithCat ($cat, $page=0)
	{
		return R::related($cat, 'post', ' publishDate <= "'.time().'" AND type = "post" ORDER BY publishDate DESC LIMIT '.strval($page).','.strval(POSTS_NUMBER), array());
	}
	
	public static function getListWithCatCount ($cat)
	{
		return ceil(count(R::related($cat, 'post', ' publishDate <= "'.time().'" AND type = "post"', array())) / (intval(POSTS_NUMBER)));
	}
	
	public static function getListWithTag ($tag, $page=0)
	{
		return R::related($tag, 'post', ' publishDate <= "'.time().'" AND type = "post" ORDER BY publishDate DESC LIMIT '.strval($page).','.strval(POSTS_NUMBER), array());
	}
	
	public static function getListWithTagCount ($tag)
	{
		return ceil(count(R::related($tag, 'post', ' publishDate <= "'.time().'" AND type = "post"', array())) / (intval(POSTS_NUMBER)));
	}

}