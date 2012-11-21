<?php
	class EAsteroid extends CBehavior 
	{
		public $AsteroidActionID;
		public $comets = array();
		public $AsteroidID;
		public $AsteroidCometRender = 'partial';
		public $_assetsUrl = null;

		public function Asteroid($id)
		{
			if(!isset($this->AsteroidActionID) && isset($_GET['AsteroidActionID']))
				$this->AsteroidActionID = $_GET['AsteroidActionID'];

			$this->AsteroidID = $id;
			$this->comets[$id] = array();
			
			//Register Scripts If we are not rendering a comet
			if(!isset($_GET['AsteroidActionID']))
			{
				//Script AsteroidJS & JQUERY
				$cs=Yii::app()->clientScript;
				$cs->registerCoreScript('jquery');
				$cs->registerScriptFile($this->getAssetsUrl().'/js/Asteroid.js');
			}

			return $this;
		}

		public function orbit()
		{
			if(!isset($_GET['AsteroidActionID']) && !empty($this->comets))
			{
				$comets = array();
				foreach($this->comets as $id=>$config)
					$comets[] = array('id'=>$id, 'renderType'=>$config->renderType, 'element'=>$config->element);
		
				Yii::app()->clientScript->registerScript('script', 'var asteroidConfig = ' . CJSON::encode($comets), CClientScript::POS_HEAD);
			}
		}
		

		public function renderAs($type='partial')
		{
			$this->AsteroidCometRender = 'render';
			return $this;
		}

		public function append($element, $template, $data)
		{
			$this->setComet($this->AsteroidID, array('renderType'=>'append', 'element'=>$element, 'template'=>$template, 'data'=>$data));
			return $this;
		}

		public function prepend($element, $template, $data)
		{
			$this->setComet($this->AsteroidID, array('renderType'=>'prepend', 'element'=>$element, 'template'=>$template, 'data'=>$data));
			return $this;
		}

		public function replace($element, $template, $data)
		{
			$this->setComet($this->AsteroidID, array('renderType'=>'replace', 'element'=>$element, 'template'=>$template, 'data'=>$data));
			return $this;
		}

		public function setComet($id, $config = array())
		{
			$this->comets[$id] = (object) $config;
			if($this->AsteroidActionID && $this->AsteroidActionID == $id)
				$this->execComet($id);
			return true;
		}

		public function execComet($id)
		{
			$comet = $this->comets[$id];
			$data = $comet->data;
			$this->owner->renderPartial($comet->template, $data());
			Yii::app()->end();
		}

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
