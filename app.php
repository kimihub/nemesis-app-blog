<?php

class blog extends App
{
	private $session_time = 0;
	
	public function setup()
	{
		// perms
		if (($perm=getperms($dir=$this->path.'uploads/')) != '0777') 
		{
			error_log('MVC.App.Blog.uploads : you must set 0777 perms to '.$dir.' ('.$perm.')');
		}
		
		// perms
		if (($perm=getperms($dir=$this->path.DB_FILEPATH)) != '0777') 
		{
			error_log('MVC.App.Blog.uploads : you must set 0777 perms to '.$dir.' ('.$perm.')');
		}
		
		// 1 week session time
		$this->session_time = intval(7 * 24 * 60 * 60);
	
		if (defined('APP_VERSION'))
			$this->version = APP_VERSION;
		
		if (defined('USE_CACHE') && USE_CACHE)
		{
			$this->NEMESIS->plugin('CSSMin');
		}
		
		include_once('lib/rb.php');
		
		if (defined('DB_TYPE') && (DB_TYPE == 'SQLITE'))
			R::setup('sqlite:'.$this->path.DB_FILEPATH);
		else
			R::setup('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
		
		include_once($this->getModel('post'));
		include_once($this->getModel('media'));
		include_once($this->getModel('category'));
		include_once($this->getModel('tag'));
		
		// is not HttpRequest load all header and constants / categories sidebar
		if (!URL::isHttpRequest())
		{
			$this->injectVar('head', array(
				'meta_title' => META_TITLE,
				'meta_description' => META_DESCRIPTION,
				'meta_keywords' => META_KEYWORDS,
				'meta_author' => META_AUTHOR,
				'meta_content_type' => META_CONTENT_TYPE,
				'html5shim' => HTML5SHIM,
				'jquery' => JQUERY,
				'favicon' => '',
				'font' => $this->resources_url.FONT_NAME,
			));
			
			$this->loadCSS('css/normalize.min.css');
			$this->loadCSS('css/main.css');
			$this->loadCSS('loader/loader.css');
			$this->loadJS('loader/loader.js');
			$this->loadJS('jquery.plugins/jquery.lazyload.js');
			
			$this->injectVar('html', array(
				'h1_title' => META_TITLE,
				'aside_title' => 'thèmatiques'
			));
			
			$this->defineConstants(array(
				'BLOG_URL' => NEMESIS_URL.$this->url,
				'BLOG_ROOT' => str_replace('//', '/', '/'.$this->url.'/'),
				'BLOG_RESOURCES_URL' => $this->resources_url,
			));
		
			$this->injectCol('html', 'categories', category::explore());
		}
		
		if ($this->isLogged())
		{
			$this->injectCol('html', 'admin', 1);
			$this->loadJS('js/admin.js');
		}
	}
	
	public function images($arguments)
	{
		
		if (!$arguments || !isset($arguments[0])) {
			$this->error404();
			return false;
		} 
		
		$size = $arguments[0];
		$name = isset($arguments[1])? $arguments[1]:'';
		
		if (intval($name))
		{
			if ($m = R::load('media', $name))
				$name = $m->name;
		}
		
		// shortcuts
		if ($size == 'pixel')
			$filePath = $this->path.'resources/pixel.gif';
		
		if ($size == 'ajax-loader')
			$filePath = $this->path.'resources/ajax-loader.gif';
	
		// small / medium / large / full
		else if ($size == 'full')
			$filePath = $this->path.'uploads/'.$name;
		else
			$filePath = CACHE.filename($name).'.'.$size.'.'.extension($name);

		if (!file_exists($filePath))
		{
			$this->error404();
			return false;
		}
		
		$images = $this->NEMESIS->plugin('Images');
		
		echo $images->display($filePath);
		exit();
	}

	public function index()
	{
	
		if (!URL::isHttpRequest())
		{
			$this->defineConstants(array('POSTS_NUMBER' => POSTS_NUMBER, 'POSTS_ROOT' => str_replace('//', '/', '/'.$this->url.'/'), 'POSTS_COUNT' => ($this->isLogged())? (post::getAllCount()):(post::getListCount())));
			$this->loadJS('jquery.plugins/jquery.infinite-scroll-helper.js');
			$this->loadJS('jquery.plugins/html5lightbox.js');
			$this->loadJS('js/index.js');		
		}
		
		$this->loadJS('js/galleries.js');
		
		$page = ((isset($_POST['page']) && intval($_POST['page']))? $_POST['page']:0);
		$posts = ($this->isLogged())? (post::getAll($page)):(post::getList($page));
		
		if ($posts)
		{
			$this->injectCol('html', 'content', 'list');
			$this->injectCol('list', 'admin', ($this->isLogged()? 1:0));
			$this->injectCol('list', 'posts', $posts);
		}
		
		$this->addToBuffer($this->getView('html'));
	}
	
	public function isLogged()
	{
		
		session_set_cookie_params($this->session_time);
		ini_set('session.gc_maxlifetime', $this->session_time);
		
		if (!isset($_SESSION))
			session_start();
			
		if (isset($_SESSION['connect']))
		{
			$session = R::load('session', $_SESSION['connect']);
			
			if ($session->session_id == session_id()
				&& $session->session_browser == $_SERVER['HTTP_USER_AGENT']
				&& $session->session_time >= time()
				&& $session->session_ip == $_SERVER['REMOTE_ADDR'])
			{
				if ($session->session_time >= time())
					return true;
				else
					R::trash($session);
			}

			unset($_SESSION['connect']);
			session_destroy();
			
		}	
			
		return false;
	}
	
	public function logout()
	{
		if(URL::isHttpRequest())
		{
			if (!$this->isLogged())
				exit();
				
			$session = R::load('session', $_SESSION['connect']);
			R::trash($session);
			
			unset($_SESSION['connect']);
			session_destroy();
			exit();
		}
		
		$this->error404();
	}
	
	public function login()
	{
		if (!$this->isLogged())
		{
			if (isset($_POST['user']) && isset($_POST['pwd']) && URL::isHttpRequest())
			{
				if ($_POST['user'] != ADMIN_USER || $_POST['pwd'] != ADMIN_PWD)
				{
					$this->addMessage('error', 'Mauvaise combinaison utilisateur/mot de passe');
					$this->displayMessages();
					exit();
				}
			
				$session = R::dispense('session');
				$session->session_id = session_id();
				$session->session_time = time() + $this->session_time;
				$session->session_browser = $_SERVER['HTTP_USER_AGENT'];
				$session->session_ip = $_SERVER['REMOTE_ADDR'];
				$_SESSION['connect'] = R::store($session);
				
				$this->addMessage('logged', 1);
				$this->displayMessages();
				exit();
			}
			
			$this->loadCSS('css/login.css');
			$this->loadJS('js/login.js');
			$this->injectCol('html', 'content', 'login');
			$this->addToBuffer($this->getView('html'));
		}
		else
		{
			if (!URL::isHttpRequest())
				URL::redirect('', 1);
			else
			{
				$this->addMessage('info', 'Déjà connecté!');
				$this->displayMessages();
				exit();
			}
		}
	}
	
	public function post($arguments=array())
	{
		if (!URL::isHttpRequest())
		{
			$this->injectCol('html', 'categories', category::explore());
			$this->loadJS('jquery.plugins/html5lightbox.js');	
		}
		
		if (!isset($arguments[0]))
		{
			$this->error404();
		}
		else if ($arguments[0] == 'add')
		{
			if (isset($arguments[1]))
				$this->post_add($arguments[1]);
			else
				$this->post_add();
		}
		else if ($arguments[0] == 'delete')
		{
			if (isset($arguments[1]))
				$this->post_delete($arguments[1]);
			die;
		}
		else
		{
			$arguments[0] = intval($arguments[0]);
			
			if ($post = R::load('post', $arguments[0]))
			{
				if ($post->id 
					&& ($post->type == 'post' || $post->type == 'draft' && $this->isLogged())
					&& ($post->publishDate <= time() || $post->publishDate && $this->isLogged())
				)
				{
					
					$this->injectCol('html', 'content', 'list');
					
					$this->loadJS('js/galleries.js');

					$tags = tag::getAttachmentsFrom($post);
					
					$tagsArray = array();
					foreach ($tags as $t) 
					{
						$tagsArray[] = $t->name;
					}
					
					$this->injectVar('head', array('meta_title' => $post->title, 'meta_description' => excerpt($post->caption), 'meta_keywords' => implode(',', $tagsArray)));
					
					$this->injectCol('list', 'posts', array($post));
					$this->injectCol('list', 'single', 1);
					$this->injectCol('list', 'admin', ($this->isLogged()? 1:0));
					
					$this->injectVar('disqus_load', array('disqus_shortname' => DISQUS_SHORTNAME));
					$this->injectVar('disqus_comments', array('disqus_shortname' => DISQUS_SHORTNAME));
					
					$this->addToBuffer($this->getView('html'));
					return false;
				}
			}
			
			$this->error404();
		}
	}
	
	public function post_delete($id) 
	{
		if ($this->isLogged())
		{
			$post = new post($this, $id);
			$post->delete();
			echo 'Article supprimé';
			die;
		}
		else
			$this->error404();
	}
	
	public function post_add($id="")
	{
		if ($this->isLogged())
		{
			
			if (isset($_POST['save']) || isset($_POST['draft']))
			{
				$post = new post($this, $_POST['id']);
				$post->edit($_POST);
				
				if (URL::isHttpRequest())
					echo $post->id;
				else
					URL::redirect('post/'.$post->id, 1);
					
				exit();
			}
			
			$this->loadJS('wysiwym/wysiwym.js');	
			$this->loadJS('wysiwym/showdown.js');	
			$this->loadJS('wysiwym/toMarkdown.js');	
			$this->loadCSS('wysiwym/wysiwym.css');
			$this->loadJS('jquery.plugins/jquery.autogrow.js');
			
			$this->loadCSS('dropbox/dropbox.css');
			$this->loadJS('jquery.plugins/jquery.filedrop.js');
			$this->loadJS('dropbox/dropbox.js');
	
			// $this->loadCSS('jquery.plugins/jquery.tagInput.css');	
			// $this->loadJS('jquery.plugins/jquery.timers.js');	
			// $this->loadJS('jquery.plugins/jquery.tagInput.js');	
			
			$this->loadJS('js/post.js');
			$this->loadCSS('css/post.css');
			
			$post = R::load('post', $id);
						
			$this->injectCol('html', 'content', 'post_add');
			
			$this->injectCol('post_add', 'post', $post);
			$this->injectCol('post_add', 'cats', category::getAttachmentsFrom($post));
			$this->injectCol('post_add', 'medias', media::explore());
			$this->injectCol('post_add', 'tags', tag::getAttachmentsFrom($post));
			$this->injectCol('post_add', 'HTML', $this->NEMESIS->plugin('HTMLhelpers'));
			$this->injectCol('post_add', 'imagesURL', NEMESIS_URL.str_replace('//', '/', $this->url.'images/'));
			
			$catsO = category::explore();
			$cats = array();
			
			foreach ($catsO as $c)
			{
				if ($c->name)
					$cats[] = $c->name;
			}
			
			$tagsO = tag::explore();
			$tags = array();
			
			foreach ($tagsO as $t)
			{
				if ($t->name)
					$tags[] = $t->name;
			}
			
			$this->injectVar('post_add', array('cats_suggestions' => json_encode($cats)));
			$this->injectVar('post_add', array('tags_suggestions' => json_encode($tags)));
			
			$this->addToBuffer($this->getView('html'));
		}
		else
			$this->error404();
	}
	
	public function cat($arguments=array())
	{
		
		if ($arguments[0] == 'manage')
			$this->cat_manage();
		else 
		{
			$cat = R::load('category', $arguments[0]);
			if ($cat)
			{
				
				
				if (!URL::isHttpRequest())
				{
					$this->defineConstants(array('POSTS_NUMBER' => POSTS_NUMBER, 'POSTS_ROOT' => str_replace('//', '/', '/'.$this->url.'/').'cat/'.$arguments[0], 'POSTS_COUNT' => post::getListWithCatCount($cat)));
					$this->loadJS('jquery.plugins/jquery.infinite-scroll-helper.js');
					$this->loadJS('jquery.plugins/html5lightbox.js');
					$this->loadJS('js/index.js');		
					$this->injectCol('html', 'categories', category::explore());
					$this->injectCol('html', 'category', $arguments[0]);
				}
				
				$this->loadJS('js/galleries.js');
				
				$page = ((isset($_POST['page']) && intval($_POST['page']))? $_POST['page']:0);
				$posts = post::getListWithCat($cat, $page);
				
				if ($posts)
				{
					$this->injectCol('html', 'content', 'list');
					$this->injectCol('list', 'admin', ($this->isLogged()? 1:0));
					$this->injectCol('list', 'posts', $posts);
				}
				
				$this->injectVar('head', array('meta_title' => $cat->name, 'meta_description' => '', 'meta_keywords' => ''));
				$this->addToBuffer($this->getView('html'));
				return false;
			
			}
			
			$this->error404();
		}
	}
	
	public function cat_manage()
	{
		if ($this->isLogged())
		{
			
			if (isset($_POST['delete']))
			{
				(int) $_POST['delete'] = $_POST['delete'];
				$cat = new category($this, $_POST['delete']);
				$cat->delete();
			}
			
			if (isset($_POST['position']))
			{
				foreach($_POST['position'] as $k => $v)
				{
					$cat = new category($this, $v);
					$cat->editPosition($k);
				}
				$this->addMessage('info', 'Nouvel ordre des thèmatiques !');
				$this->displayMessages();
				exit();
			}
			
			if (isset($_POST['category']) && !empty($_POST['category']))
			{
				if (!category::exists($_POST['category']))
				{
					$cat = new category($this, $_POST['category']);
					$this->addMessage('category_id', $cat->id);
				}
				else
					$this->addMessage('alert', $_POST['category'].' existe déjà');
				
				$this->displayMessages();
				exit();
			}
				
			$this->loadCSS('jquery.plugins/jquery.sortable.css');
			$this->loadCSS('css/categories.css');
			$this->loadJS('jquery.plugins/jquery.sortable.js');
			$this->loadJS('js/categories.js');
			$this->injectCol('html', 'content', 'category_manage');
			$this->injectVar('category_manage', array('categories_edit_title' => 'Edition des thèmatiques'));
			$this->injectCol('category_manage', 'HTML', $this->NEMESIS->plugin('HTMLhelpers'));
			$this->injectCol('category_manage', 'categories', category::explore());
			$this->addToBuffer($this->getView('html'));
		}
		else
			$this->error404();
	}
	
	public function dropbox($arguments=array()) 
	{
		if ($this->isLogged())
		{						
			if (isset($arguments[0]))
			{
				if ($arguments[0] == 'upload')
				{
					if (isset($_POST['url']))
						$medias = media::upload(array(urldecode($_POST['url'])), $this->path.'uploads/');
					else
						$medias = media::upload('pic', $this->path.'uploads/');
					
					if (is_array($medias))
					{
						$mediaIds = array();
						foreach ($medias as $m) 
						{
							$mediaIds[] = $m;
							$media = new media($this, $m);
							$media->createThumbnails();
						}
						echo (sizeof($mediaIds) > 1)? json_encode($mediaIds):(!empty($mediaIds)? $mediaIds[0]:'');
					}
					else
					{
						$this->addMessage('error', $medias);
						$this->displayMessages();
					} 
					exit();
				}
				else if ($arguments[0] == 'delete' && isset($arguments[1]))
				{
					$arguments[1] = intval($arguments[1]);
					$media = new media($this, $arguments[1]);
					$name = $media->bean->name;
					$media->delete();
					echo $name;
					exit();
				}
			}
			
			$this->loadCSS('css/post.css');
			$this->loadCSS('dropbox/dropbox.css');
			$this->loadJS('dropbox/dropbox.deleteItems.js');
			
			$this->injectCol('html', 'content', 'dropbox');
			$this->injectCol('dropbox', 'medias', media::explore());
			$this->injectCol('dropbox', 'HTML', $this->NEMESIS->plugin('HTMLhelpers'));
			$this->addToBuffer($this->getView('html'));
			
		}
		else
			$this->error404();
	}
	
	public function settings($arguments=array()) 
	{
		if ($this->isLogged())
		{			
			if (isset($arguments[0]))
			{
				switch($arguments[0])
				{
					case 'dropdb':
						R::nuke();
						echo 'La base de donnée vient d\'être réinitialisé';
						die;
					break;
					
					case 'purgecacheimages':
						$media = new media($this, intval($_POST['id']));
						$media->createThumbnails();
						echo 'Les miniatures ont été réinitialisées pour l\'image '.$_POST['id'];
						die;
					break;
				}
			}
			
			$this->loadJS('js/settings.js');
			$this->injectCol('html', 'categories', category::explore());
			$this->injectCol('html', 'content', 'settings');
			
			$medias = media::explore();
			$medias_id = array();
			foreach ($medias as $m)
			{
				$medias_id[] = $m->id;
			}
			
			$this->defineConstants(array('MEDIAS_ID' => json_encode($medias_id)));
			$this->addTobuffer($this->getView('html'));
			return false;
		}
		
		$this->error404();

	}
	
}
