<?php
class EAsteroidBelt 
{
	protected $_Asteroid;

	function __construct(EAsteroid &$asteroid)
	{
		$this->_Asteroid = $asteroid;
	}

	protected function Asteroid($id)
	{
		return $this->_Asteroid->Asteroid($id);
	}
}
