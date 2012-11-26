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
###Public Methods
| Method  | Description  |
|---------------|----------------|
| [Asteroid](#methodAsteroid)    |   Initilizes Asteroid for the $id passed. $id should be unique unless you intend to overwrite an existing Asteroid. Example: `Asteroid('block1')`.  |
| [onEvent](#methodOnEvent)    |   Sets a custom event listener. Example: `onEvent('click', '#someDomID')`; The default is 'load', 'body'. |
| [renderMethod](#methodRenderMethod)  | Sets the Yii render type for your Asteroid. `renderPartial` is the default. Generally you only need to use pass 'render' if you are using Yii widgets like Grid View. |
| [append](#methodAppend)  | Tells JS to dynamically load the specified view and append it to the specified DOM element. |
| [prepend](#methodPrepend)  | Same as `append` but prepends to DOM element content.  |
| [replace](#methodReplace)  | Same as `append` but replaces DOM element content.  |
| [execJS](#methodExecJS)  | Call this method to add arbitrary JavaScript. Takes String $js of valid JavaScript. `execJS('alert("Yeah!");')`  |
| [orbit](#methodOrbit)  | Renders all JS and CSS dependencies. You must Call `orbit()` as the very last step after all comets have been initialized with Asteroid('id');  |

###Method Details

#####<a name="methodAsteroid"/> Asteroid</a>

| public object Asteroid(string $id)   |
|---------------|
|Initilizes Asteroid for the $id passed. $id should be unique unless you intend to overwrite an existing Asteroid. **All Asteroids must start with this method**|


|  Param |  Param Type | Desc |
|---------------|----------------|----------------|
| $id  | String  | Unique Identifier for this Asteroid event |


#####<a name="methodOnEvent"/> onEvent</a>
| public object onEvent(string $event, string $selector)   |
|---------------|
|Sets a custom event listener. The default is 'load', 'body' and you only need to call this method if you intend to do somthing other then the default.|


|  Param |  Param Type | Desc |
|---------------|----------------|----------------|
| $event  | String  | Tells Asteroid which JQuery event to listen for. List of possible $event values:<br/> "click", "blur", "focus", "focusin", "focusout", "load", "resize", "scroll", "unload", "dblclick", "mousedown", "mouseup", "mousemove", "mouseover", "mouseout", "mouseenter", "mouseleave", "change", "select", "submit", "keydown", "keypress", "keyup", "error" |
| $selector | String  | Tells Asteroid which DOM object(s) to attach the listener to. |

####<a name="methodRenderMethod"/> renderMethod</a>
| public object renderMethod(string $type='renderPartial', string $viewTemplate=null)   |
|---------------|
| Sets the Yii render type for your Asteroid. `renderPartial` is the default. Only call this method if you need an Yii render type of 'render'. |

|  Param |  Param Type | Desc |
|---------------|----------------|----------------|
| $type  | String  | Tells Asteroid which Yii render type to use. Default is 'renderPartial'. Should be set to 'render' when using widgets that register scripts or style sheets to POS_HEAD. |
| $viewTemplate  | String  | Tells Asteroid which view template to use for render. Optional and should only be used when $renderType is set to 'render'. The template used for type render is ext.Asteroid.views.clean |


####<a name="methodAppend"/> append</a>
| public object append(string $selector, string $view, closure $data)|
|---------------|
|Tells your Asteroid to dynamically load the Yii view $view ($this->renderPartial($view, $data()) and append it to the Dom Element(s) specified by $selector.|

|  Param |  Param Type | Desc |
|---------------|----------------|----------------|
| $selector  | String  | JQuery Dom selector ie '#someDomID' |
| $view | String | Yii view file ie "_someView" |
| $data | Closure | Closure must return an associative array. This array is passed to the $view like so: ($this->renderPartial($view, $data())|


####<a name="methodPrepend"/> prepend</a>
| public object prepend(string $selector, string $view, closure $data)|
|---------------|
|Tells your Asteroid to dynamically load the Yii view $view ($this->renderPartial($view, $data()) and prepend it to the Dom Element(s) specified by $selector.|

|  Param |  Param Type | Desc |
|---------------|----------------|----------------|
| $selector  | String  | JQuery Dom selector ie '#someDomID' |
| $view | String | Yii view file ie "_someView" |
| $data | Closure | Closure must return an associative array. This array is passed to the $view like so: ($this->renderPartial($view, $data())|

####<a name="methodReplace"/> replace</a>
| public object replace(string $selector, string $view, closure $data)|
|---------------|
|Tells your Asteroid to dynamically load the Yii view $view ($this->renderPartial($view, $data()) and replace the content of the Dom Element(s) specified by $selector.|

|  Param |  Param Type | Desc |
|---------------|----------------|----------------|
| $selector  | String  | JQuery Dom selector ie '#someDomID' |
| $view | String | Yii view file ie "_someView" |
| $data | Closure | Closure must return an associative array. This array is passed to the $view like so: ($this->renderPartial($view, $data())|


####<a name="methodExecJS"/> execJS</a>
| public object execJS(string $js)|
|---------------|
| Call this method to add arbitrary JavaScript. Takes String $js of valid JavaScript. execJS('alert("Yeah!");'). You should only calls this method if you need to execute addtional JS not provided by Asteroid |

|  Param |  Param Type | Desc |
|---------------|----------------|----------------|
| $js  | String  | Valid Javascript |

####<a name="methodOrbit"/> orbit</a>
| public orbit() |
|---------------|
| Renders all JS and CSS dependencies. You must Call orbit() as the very last step after all comets have been initialized with Asteroid('id'); |


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
					