<?php
	class EAsteroid extends CBehavior 
	{
		public $_AsteroidID; //The unique idenifier of a section of dynamically rendered content
		public $_AsteroidActionID; //Set When $_GET['AsteroidActionID'] is sent. Tells EAsteroid which commet to render. 
		public $_comets = array(); //List of all content sections to be rendered.
		
		public $_AsteroidCometRender = 'renderPartial'; //Tells Yii how content should be rendered.
		public $_assetsUrl = null; //Path to Asteroid Assets.

		//Initilize Asteroid and create a comet for the $id passed
		//$id should be unique unless you intend to overwrite an existing comet.
		public function Asteroid($id)
		{
			if(!isset($this->_AsteroidActionID) && isset($_GET['AsteroidActionID']))
				$this->_AsteroidActionID = $_GET['AsteroidActionID'];

			$this->_AsteroidID = $id;
			$this->_comets[$id] = array();

			return $this;
		}
		
		//This method will render all JS and CSS dependencies
		//You must Call `orbit()` as the very last step after all commets have been initialized with Asteroid('id');
		public function orbit()
		{
			if(!isset($_GET['AsteroidActionID']) && !empty($this->_comets))
			{
				$comets = array();
				foreach($this->_comets as $id=>$config)
					$comets[] = array('id'=>$id, 'renderType'=>$config->renderType, 'element'=>$config->element);
				
				$cs = Yii::app()->clientScript;
				$cs->registerCoreScript('jquery');
				$cs->registerCss('asteroidCSS', ".asteroidLoader{background-image:url('".$this->getAssetsUrl().'/images/loading.gif'. "'); background-position:center center; background-repeat:no-repeat; }" , CClientScript::POS_HEAD);
				$cs->registerScript('script', 'var asteroidConfig = ' . CJSON::encode($comets), CClientScript::POS_HEAD);
				$cs->registerScriptFile($this->getAssetsUrl().'/js/Asteroid.js');
			}
		}
		
		//Setter for _AsteroidCometRender the sets the Yii render type for your comet
		//renderPartial is the default. Generaly you need to use pass 'render' if you are using Yii widgets like Grid View.
		//Passing render will make sure that all scripts that are registered to POS_HEAD are included.
		public function renderMethod($type='renderPartial')
		{
			$this->_AsteroidCometRender = 'render';
			return $this;
		}

		//Tells JS to append the content to the dom element :$element 
		//using the Yii template view: $template
		//with the data: $data. $data must be a closure that returns an associative array.
		//String $elment, String $template, Closure $data
		public function append($element, $template, closure $data)
		{
			$this->setComet($this->_AsteroidID, array('renderType'=>'append', 'element'=>$element, 'template'=>$template, 'data'=>$data));
			return $this;
		}

		//Tells JS to prepend the content to the dom element :$element 
		//using the Yii template view: $template
		//with the data: $data. $data must be a closure the returns an array.
		//String $elment, String $template, Closure $data
		public function prepend($element, $template, closure $data)
		{
			$this->setComet($this->_AsteroidID, array('renderType'=>'prepend', 'element'=>$element, 'template'=>$template, 'data'=>$data));
			return $this;
		}
		
		//Tells JS to replace the content to the dom element :$element 
		//using the Yii template view: $template
		//with the data: $data. $data must be a closure the returns an array.
		//String $elment, String $template, Closure $data
		public function replace($element, $template, closure $data)
		{
			$this->setComet($this->_AsteroidID, array('renderType'=>'replace', 'element'=>$element, 'template'=>$template, 'data'=>$data));
			return $this;
		}

		//This method is called internally by EAsteroid.
		//Sets the _comets var so that it contains a list of all comets.
		public function setComet($id, $config = array())
		{
			$this->_comets[$id] = (object) $config;
			if($this->_AsteroidActionID && $this->_AsteroidActionID == $id)
				$this->execComet($id);
			return true;
		}
		
		//Internal comet render method.
		//Renders comet with $id and exits Yii
		public function execComet($id)
		{
			$comet = $this->_comets[$id];
			$data = $comet->data;
			if($this->_AsteroidCometRender == 'render')
			{
				$this->owner->layout = 'ext.Asteroid.views.clean';
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
