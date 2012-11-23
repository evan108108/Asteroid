Asteroid
========

Yii Extension: Quickly Add Dynamic Content Without Writing JS Or Additional Actions!


**Example Usage**
```php
class AsteroidController extends Controller
	{
		public function behaviors() {
			return array('EAsteroid' => array('class'=>'ext.Asteroid.behaviors.EAsteroid'));
		}

		public function actionTestUI()
		{
			$this->Asteroid('part1')->renderMethod('render')->append('#myPlace', '_myPlace1',   function() { return array('model' =>new Work() ); } )
					 ->Asteroid('part2')->prepend('#myPlace2', '_myPlace2', function() { return array('dude2'=>'yeah2!'); } )
					 ->Asteroid('part3')->replace('#myPlace3', '_myPlace3', function() { return array('dude3'=>'yeah3!'); } )
					 ->orbit();

			$this->render('test');
		}	
		
	}
```

```php
/** 
 * When I click DOM element with the ID #SomeDomElementID 
 * then replace then content of #myPlace1 with the partial _mPlace1 and 
 * apply the prams returned from the closure.
 */
$this->Asteroid('part1')->onEvent('click', '#SomeDomElementID')	
	->replace('#myPlace1', '_myPlace1', function() { 
		return array('dude2'=>'yeah2!'); 
	})->orbit();
```
					