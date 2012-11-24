Asteroid
========

Yii Extension: Quickly Add Dynamic Content Without Writing JS Or Additional Actions!

## Requirements
1. Yii 1.8 or above
2. PHP 5.3 or above

## Installation
1. Place the Asteroid directory in protected/extensions
2. In your desired Controller Class add the Asteroid behavior like so:

```php
class MyController extends Controller
{
	public function behaviors() {
		return array('EAsteroid' => array('class'=>'ext.Asteroid.behaviors.EAsteroid'));
	}
…
}
```

##API
| Method  | Description  |
|---------------|----------------|
| Asteroid    |   Initilizes Asteroid and creates a comet for the $id passed. $id should be unique unless you intend to overwrite an existing comet. Note: A `comet` is an object that contains a unique async event. Example: `Asteroid('block1')`. Returns $this.  |
| onEvent    |   Sets a custom event listener. Example: `onEvent('click', '#someDomID')`; The default is 'load', 'body'. Returns $this. |
| renderMethod  | Setter for _AsteroidCometRender. Sets the Yii render type for your comet. `renderPartial` is the default. Generally you only need to use pass 'render' if you are using Yii widgets like Grid View. Optional: You may pass a view template path (only applies to a render method type of `render`). By default this path is `ext.Asteroid.views.clean`. Passing render will make sure that all scripts that are registered to `POS_HEAD` are included. Example: `renderMethod('render')`. Returns $this. |
| append  | Tells JS to append the content to the DOM element :$element using the Yii template view: $template. with the data: $data. $data must be a closure that returns an associative array. String $element, String $template, Closure $data. Example: `append('#myPlace', '_myPlace1',   function() { return array('model' =>new Work() ); })`. Returns $this; |
| prepend  | Same as `append` but prepends to DOM element content.  |
| replace  | Same as `append` but replaces DOM element content.  |
| orbit  | Renders all JS and CSS dependencies. You must Call `orbit()` as the very last step after all comets have been initialized with Asteroid('id');  |
| execJS  | Call this method to add arbitrary JavaScript. Takes String $js of valid JavaScript. `execJS('alert("Yeah!");')` returns $this.  |

## Example Usage
```php
class AsteroidController extends Controller
	{
		public function behaviors() {
			return array('EAsteroid' => array('class'=>'ext.Asteroid.behaviors.EAsteroid'));
		}
		
		/**
		 * The following Asteroids will be rendered Async after your page has loaded.
		 */
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
					