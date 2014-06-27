<?php

class media 
{
	private $BLOG = null;
	public $id = null;
	public $bean = null;
	
	public function __construct ($blog, $id)
	{
		$this->id = $id;
		$this->BLOG = $blog;
		$this->bean = R::load('media', $id);
	}
		
	public function attachTo ($post, $order='')
	{
		if ($order)
			$post->mediaOrder[$this->id] = $order;
		R::store($post);
		R::associate($post, $this->bean);
	}
	
	public function removeFrom ($post)
	{
		if ($post->mediaOrder)
			unset($post->mediaOrder[$this->id]);
		R::store($post);
		R::unassociate($post, $this->bean);
	}
	
	public function createThumbnails()
	{
			
		$images = $this->BLOG->NEMESIS->plugin('Images', $this->BLOG->path . 'uploads/');
	
		// Small
		$small = $images->create(array(
			'source'=> $this->BLOG->path . 'uploads/' . $this->bean->name,
			'destination'=> CACHE . filename($this->bean->name) . '.small.' . extension($this->bean->name),
			'width'=> SMALL_WIDTH,
			'height'=> SMALL_HEIGHT,
		));
				
		// Medium
		$medium = $images->create(array(
			'source'=> $this->BLOG->path . 'uploads/' .  $this->bean->name,
			'destination'=> CACHE . filename($this->bean->name) . '.medium.' . extension($this->bean->name),
			'width'=> MEDIUM_WIDTH,
			'height'=> MEDIUM_HEIGHT,
		));
				
		// Large
		$large = $images->create(array(
			'source'=> $this->BLOG->path . 'uploads/' . $this->bean->name,
			'destination'=> CACHE . filename($this->bean->name) . '.large.' . extension($this->bean->name),
			'width'=> LARGE_WIDTH,
			'height'=> LARGE_HEIGHT,
		));
	}
	
	public function delete ()
	{		
		@unlink($this->BLOG->path . 'uploads/' .$this->bean->name);
		@unlink(CACHE . filename($this->bean->name) . '.small.' . extension($this->bean->name));
		@unlink(CACHE . filename($this->bean->name) . '.medium.' . extension($this->bean->name));
		@unlink(CACHE . filename($this->bean->name) . '.large.' . extension($this->bean->name));
		
		$posts = R::related($this->bean, 'post');
		
		foreach ($posts as $p) 
		{
			if ($p->mediaOrder)
				unset($p->mediaOrder[$id]);
			R::store($p);
		}
		
		R::clearRelations($this->bean, 'post');
		R::trash($this->bean);
		unset($this);
	}
	
	public static function getAttachmentsFrom ($post)
	{
		return R::related($post, 'media');
	}
	
	public static function getAttachmentsOrderFrom ($post)
	{
		return $post->mediaOrder;
	}
	
	public static function upload($nameUploadOrLinks, $uploadPath, $extensions=array('jpg', 'jpeg', 'png', 'gif', 'tif'))
	{
		// if links
		if (is_array($nameUploadOrLinks))
		{
			$upload = array();

			foreach($nameUploadOrLinks as $link)
			{
				if (!in_array(end(explode(".",$link)), $extensions) || !parse_url($link))
					return false;
				
				$targetPathCurrent = $uploadPath . basename( $link );
				
				$i = 1;
				while (file_exists($targetPathCurrent))
				{
					$targetPathCurrent = $uploadPath . filename( $link ) . '_'.$i.'.'.extension( $link );
					$i++;
				}
				
				$content = @file_get_contents($link);
				
				if ($content !== FALSE && @file_put_contents ($targetPathCurrent, $content))
					$upload[] = $targetPathCurrent;
				
			}
		}
		// if local
		else
			$upload = upload ($nameUploadOrLinks, $uploadPath, $extensions);
	
		
		$medias = array();
		
		foreach ($upload as $u)
		{
			$media = R::dispense('media');
			$media->name = basename($u);
			$medias[] = R::store($media);
		}
		
		return (isset($error)? $error:$medias);
	}
	
	public static function explore ($post=null)
	{
		if (!is_null($post) && $post->id)
		{
			return R::unrelated($post, 'media');
		}
		else
			return R::findAll('media');
	}
}