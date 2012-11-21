Asteroid
========

Yii Extension: Quickly Add Dynamic Content Without Writing JS Or Additional Actions!


**Example Usage**
```php
class TestController extends Controller
	{
		public function behaviors() {
			return array('EAsteroid' => array('class'=>'ext.Asteroid.behaviors.EAsteroid'));
		}

		public function actionTestUI()
		{
			$this->Asteroid('part1')->append('#myPlace', '_myPlace1',   function() { return array('dude' =>'yeah!' ); } )
					 ->Asteroid('part2')->prepend('#myPlace2', '_myPlace2', function() { return array('dude2'=>'yeah2!'); } )
					 ->Asteroid('part3')->replace('#myPlace3', '_myPlace3', function() { return array('dude3'=>'yeah3!'); } )
					 ->orbit();

			$this->render('test');
		}	
		
	}
```