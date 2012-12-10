<?php
	class EAsteroid extends CBehavior 
	{
		public $_AsteroidID; //The unique idenifier of a section of dynamically rendered content
		public $_AsteroidActionID; //Set When $_GET['AsteroidActionID'] is sent. Tells EAsteroid which commet to render. 
		public $_comets = array(); //List of all content sections to be rendered.
		public $_js = ""; //Stores arbitrary javascript to be executed.
		
		public $_AsteroidCometRender = 'renderPartial'; //Tells Yii how content should be rendered.
		public $_AsteroidCometRenderTemplate = 'ext.Asteroid.views.clean';

		public $_assetsUrl = null; //Path to Asteroid Assets.

		public $_listener; //Stores listener for comet;

		//Initilizes Asteroid and creates a comet for the $id passed
		//$id should be unique unless you intend to overwrite an existing comet.
		//Note: A `comet` is an object that contains a unique async event.
		public function Asteroid($id)
		{
			if(!isset($this->_AsteroidActionID) && isset($_GET['AsteroidActionID']))
				$this->_AsteroidActionID = $_GET['AsteroidActionID'];

			$this->_AsteroidID = $id;
			$this->_listener = array('event'=>'load', 'selector'=>'body'); //re sets the default listener to body.onLoad
			$this->_comets[$id] = array();

			return $this;
		}
		
		//This method will render all JS and CSS dependencies.
		//You must Call `orbit()` as the very last step after all commets have been initialized with Asteroid('id');
		public function orbit()
		{
			if(!isset($_GET['AsteroidActionID']) && !empty($this->_comets))
			{
				$comets = array();
				foreach($this->_comets as $id=>$config)
				{
					if(isset($config->renderType)) //We do this check to in case your comet simply executes arbitrary js onEvent...
						$comets[] = array('id'=>$id, 'renderType'=>$config->renderType, 'element'=>$config->element, 'listen'=>$config->listen);
				}
				$cs = Yii::app()->clientScript;
				$cs->registerCoreScript('jquery');
				$cs->registerCss('asteroidCSS', ".asteroidLoader{background-image:url('".$this->getAssetsUrl().'/images/loading.gif'. "'); background-position:center center; background-repeat:no-repeat; }" , CClientScript::POS_HEAD);
				$cs->registerScript('script', 'var asteroidConfig = ' . CJSON::encode($comets), CClientScript::POS_HEAD);
				if(!empty($this->_js)) $cs->registerScript('script', $this->_js);
				$cs->registerScriptFile($this->getAssetsUrl().'/js/Asteroid.js');
			}
		}
		
		//Setter for _AsteroidCometRender the sets the Yii render type for your comet
		//renderPartial is the default. Generaly you need to use pass 'render' if you are using Yii widgets like Grid View.
		//Optional: You may pass a view template path (only applies to a render method type of `render`). By default this path is `ext.Asteroid.views.clean`
		//Passing render will make sure that all scripts that are registered to POS_HEAD are included.
		public function renderMethod($type='renderPartial', $viewTemplate=null)
		{
			if(!is_null($viewTemplate)) $this->$_AsteroidCometRenderTemplate = $viewTemplate;
			$this->_AsteroidCometRender = $type;
			return $this;
		}
		
		//Set a custom event listener
		//Example: onEvent('click', 'h1');
		public function onEvent($event, $selector)
		{
			$this->_listener = array('event'=>$event, 'selector'=>$selector);
			return $this;
		}

		//Tells JS to append the content to the dom element :$selector 
		//using the Yii view: $view
		//with the data: $data. $data must be a closure that returns an associative array.
		//String $elment, String $view, Closure $data
		public function append($selector, $view, closure $data)
		{
			$this->setComet($this->_AsteroidID, array('renderType'=>'append', 'element'=>$selector, 'template'=>$view, 'data'=>$data));
			return $this;
		}

		//Tells JS to prepend the content to the dom element :$selector 
		//using the Yii view: $view
		//with the data: $data. $data must be a closure the returns an array.
		//String $elment, String $view, Closure $data
		public function prepend($selector, $view, closure $data)
		{
			$this->setComet($this->_AsteroidID, array('renderType'=>'prepend', 'element'=>$selector, 'template'=>$view, 'data'=>$data));
			return $this;
		}
		
		//Tells JS to replace the content to the dom element :$selector 
		//using the Yii view: $view
		//with the data: $data. $data must be a closure the returns an array.
		//String $elment, String $view, Closure $data
		public function replace($selector, $view, closure $data)
		{
			$this->setComet($this->_AsteroidID, array('renderType'=>'replace', 'element'=>$selector, 'template'=>$view, 'data'=>$data));
			return $this;
		}

		//This method is called internally by EAsteroid.
		//Sets the _comets var so that it contains a list of all comets.
		//When `_AsteroidActionID` is set execComet will be called.
		public function setComet($id, $config = array())
		{
			$config['listen'] = $this->_listener;
			$this->_comets[$id] = (object) $config;
			if($this->_AsteroidActionID && $this->_AsteroidActionID == $id)
				$this->execComet($id);
			return true;
		}

		//Call this method to add arbitrary JavaScript
		//Takes a string $js of valid JavaScript
		public function execJS($js)
		{
			if($this->_listener == array('event'=>'load', 'selector'=>'body'))
				$js = $this->owner->renderPartial('ext.Asteroid.views._JS_onLoad', array('js'=>$js), true);
			else
				$js = $this->owner->renderPartial('ext.Asteroid.views._JS_onEvent', array('listener'=>$this->_listener, 'js'=>$js), true);
			
			$this->_js .= "\n" . $js;
			return $this;
		}
		
		//Internal comet render method.
		//Takes the unique comet $id.
		//Renders comet with $id.
		//Interrupts action and exits after render of the comet.
		public function execComet($id)
		{
			$comet = $this->_comets[$id];
			$data = $comet->data;
			if($this->_AsteroidCometRender == 'render')
			{
				$this->owner->layout = $this->_AsteroidCometRenderTemplate;
			}
			
			$this->owner->{$this->_AsteroidCometRender}($comet->template, $data());
			Yii::app()->end();
		}

		//Pulishes assets and set $this->_assetsUrl to the location of all registered assets
		public function getAssetsUrl()
    {
			if ($this->_assetsUrl !== null)
				return $this->_assetsUrl;
			else
			{
					$assetsPath = Yii::getPathOfAlias('ext.Asteroid.assets');
					if (YII_DEBUG)
							$assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
					else
							$assetsUrl = Yii::app()->assetManager->publish($assetsPath);
					return $this->_assetsUrl = $assetsUrl;
			}
    }
	
	}
