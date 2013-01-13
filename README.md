Asteroid
========

Yii Extension: Quickly Add Dynamic Content Without Writing JS Or Additional Actions! Quickly bind JQuery events and much more…
In other words Asteroid gives the ability to lazy load your partials either on load or bound to events like 'click' or 'mouseenter'.

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

## Examples
For purposes of the following examples we will assume we have the below controller and views:

####<a name="sampleController"/>SampleController.php</a>
```php
class sampleController extends Controller
{
	public function behaviors() {
		return array('EAsteroid' => array('class'=>'ext.Asteroid.behaviors.EAsteroid'));
	}
	…
		
	public function actionTestUI()
	{
		…
		$this->render('test');
	}
		
		…
}
```

####<a name="indexView"/>index.php</a>
```html
	<div id="clickMe">Click Here</div>
	
	<div id="myDiv1">
	
	</div>
	
	<div id="myDiv2">
	
	</div>
	
	<div id="myDiv3">
	
	</div>
```

####<a name="_p1"/>_p1.php</a>
```html
	<h1><?php echo $var1; ?></h1>
	<h2><?php echo $var2; ?></h1>
```

####<a name="_p2"/>_p2.php</a>
```html
	<h1><?php echo $var3; ?></h1>
	<h2><?php echo $var4; ?></h1>
```

####<a name="_p3GridView"/>_p3GridView.php</a>
```html
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'airport-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
    	'sample_code',
    	'name',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
```


###<a name="_example1"/>Example 1:</a> Using replace() append() And prepend()
Lets say we want to dynamically load the content of the partial view _p1.php into the div "#myDiv1" of test view.
```php
public function actionTestUI()
{
	$this->Asteroid('a1')
			->replace('#myDiv1', '_p1', function(){ return array('var1'=>'Yeah!', 'var2'=>'Thats right!'); })
	->orbit();
	
	$this->render('test');
}
```


Now lets say we have the same scenario but we would also like to append some content to the div "#myDiv2"
```php
public function actionTestUI()
{
	$this->Asteroid('a1')
			->replace('#myDiv1', '_p1', function(){ return array('var1'=>'Yeah!', 'var2'=>'Thats right!'); })
		 ->Asteroid('a2')
		 	->append('#myDive2', '_p2', function(){ return array('var3'=>'Im Here!', 'var4'=>'You know it!'); })
	->orbit();
	
	$this->render('test');
}
```

We could also have chosen to prepend 
```php
public function actionTestUI()
{
	$this->Asteroid('a1')
			->replace('#myDiv1', '_p1', function(){ return array('var1'=>'Yeah!', 'var2'=>'Thats right!'); })
		 ->Asteroid('a2')
		 	->prepend('#myDive2', '_p2', function(){ return array('var3'=>'Im Here!', 'var4'=>'You know it!'); })
	->orbit();
	
	$this->render('test');
}
```


###<a name="_example2"/>Example 2:</a> Dynamically Rendering An Yii Grid View
When your partial contains a widget like CGridView that registers scripts and or style sheets to POS_HEAD you should use this approach.
```php
public function actionTestUI()
{
	$this->Asteroid('a1')
			->renderMethod('render')
			->replace('#myDiv3', '_p3GridView',  function() { 
				$sample = new Sample(); 
				if(isset($_GET['Sample'])) $sample->attributes = $_GET['Sample']; 
				return array('model' => $sample); 
			  })
	->orbit();
	
	$this->render('test');
}
```


###<a name="_example2"/>Exmaple 3:</a> Using onEvent()
Lets say we want to replace the content of div "#myDiv1" with the partial _p1.php when I click the div "#clickMe" 

```php
public function actionTestUI()
{

	$this->Asteroid('a3')->onEvent('click', '#clickMe')	
			->replace('#myDiv1', '_p1', function() { return array('var1'=>'Yeah!', 'var2'=>'You clicked me…'); })
	->orbit();

	$this->render('test');
}
```

Now lets say we want to execute some additional JavaScript on our click event.
```php
public function actionTestUI()
{
	$this->Asteroid('a3')->onEvent('click', '#clickMe')	
			->execJs("alert('I know you would click');")
			->replace('#myDiv1', '_p1', function() { return array('var1'=>'Yeah!', 'var2'=>'You clicked me…'); })
	->orbit();

	$this->render('test');
}
```


###<a name="_example4"/>Exmaple 4:</a> Using sendVars()  
Lets say we would like to keep track of the number of clicks on a particular DOM element and update the view with the total. Every time a user clicks '#clickMe' we will replace the content of '#myDiv1' with the incremented count.

```php
	->Asteroid('counterExample')
		->onEvent('click', '#clickMe')
		->sendVars(function($vars) {
			if(empty($vars)) $vars = array('count'=>0);
			$vars['count']++;
			return $vars;
		})
		->replace('#myDiv1', '_myPlace3', function($vars) {
			return array('var1'=>'Cool I Clicked that', 'var2'=> . $vars['count'] . 'X');
		})
	->orbit
```

###<a name="_example5"/>Exmaple 5:</a> Using useBelt() 
The 'useBelt' method provides a way to keep things DRY, allowing you to aply groups of Asteroid actions you have defined.  

To use 'useBelt' you will need to create at least one instance of EAsteroidBelt. Lets start there and create a folder in 'protected'
called 'asteroidBelts' ('webapp/protected/asteroidBelts'). We will use this folder to store our Belts. Now we will create a new class called 'UiHelperAB' and save it to a file called 'UiHelperAB.php' in the 'asteroidBelts' directory we just created. Your class will look something like this:

```php
class UiHelperAB extends EAsteroidBelt
{

	public function a1a2($myvar)
	{
		$this->Asteroid('a1')
        	->replace('#myDiv1', '_p1', function() use $myvar { return array('var1'=>'Yeah!', 'var2'=>$myvar); })
         ->Asteroid('a2')
            ->append('#myDive2', '_p2', function(){ return array('var3'=>'Im Here!', 'var4'=>'You know it!'); })
	}

	public function grid()
	{
		$this->Asteroid('grid')
            ->renderMethod('render')
            ->replace('#myDiv3', '_p3GridView',  function() { 
				$sample = new Sample(); 
                if(isset($_GET['Sample'])) $sample->attributes = $_GET['Sample']; 
                return array('model' => $sample); 
			})
	}

}
```

Cool we have an Asteroid Belt, now lets use it in our controller. Lets say we want to use a1a2:

```php
public function actionTestUI()
{
	$myvar = "I did this with an Asteroid Belt!";
	$this->Asteroid('UIHelper')->useBelt(
		'application.asteroidBelts.UiHelperAB', 'a1a3', array($myvar)
	)
	->Asteroid('someID')->...
	->orbit();		
	...
}
```

Great, but what if we want to use 'a1a2' and 'grid' in our controller action. We could just repeat the above line again and swap out the method name but there is another way. Lets take a look:  

```php
public function actionTestUI()
{
	$myvar = "I did this with an Asteroid Belt!";
	$this->Asteroid('UIHelper')->useBelt(
		'application.asteroidBelts.UiHelperAB', 
	 	array(
			array('a1a3', array($myvar)),
			array('grid', array()),
		)
	)
	->Asteroid('someID')->...
	->orbit();	
	...
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
| [sendVars](#methodSendVars)  | Attach variables to your request. |
| [useBelt](#methodUseBelt)  | Attach groups of predefined Asteroid Actions |
| [orbit](#methodOrbit)  | Renders all JS and CSS dependencies. You must Call `orbit()` as the very last step after all comets have been initialized with Asteroid('id');  |

###Method Details

#####<a name="methodAsteroid"/> Asteroid</a>

|Method Info|
|---------------|
| ```public object Asteroid(string $id)```   |
|Initilizes Asteroid for the $id passed. $id should be unique unless you intend to overwrite an existing Asteroid. **All Asteroids must start with this method**|


|  Param |  Type | Description |
|---------------|----------------|----------------|
| $id  | String  | Unique Identifier for this Asteroid event |


#####<a name="methodOnEvent"/> onEvent</a>
|Method Info|
|---------------|
| ```public object onEvent(string $event, string $selector)```   |
|Sets a custom event listener. The default is 'load', 'body' and you only need to call this method if you intend to do somthing other then the default.|


|  Param |  Type | Description |
|---------------|----------------|----------------|
| $event  | String  | Tells Asteroid which JQuery event to listen for. List of possible $event values:<br/> "click", "blur", "focus", "focusin", "focusout", "load", "resize", "scroll", "unload", "dblclick", "mousedown", "mouseup", "mousemove", "mouseover", "mouseout", "mouseenter", "mouseleave", "change", "select", "submit", "keydown", "keypress", "keyup", "error" |
| $selector | String  | Tells Asteroid which DOM object(s) to attach the listener to. |

####<a name="methodRenderMethod"/> renderMethod</a>
|Method Info|
|---------------|
| ```public object renderMethod(string $type='renderPartial', string $viewTemplate=null)```   |
| Sets the Yii render type for your Asteroid. `renderPartial` is the default. Only call this method if you need an Yii render type of 'render'. |

|  Param |  Type | Description |
|---------------|----------------|----------------|
| $type  | String  | Tells Asteroid which Yii render type to use. Default is 'renderPartial'. Should be set to 'render' when using widgets that register scripts or style sheets to POS_HEAD. |
| $viewTemplate  | String  | Tells Asteroid which view template to use for render. Optional and should only be used when $renderType is set to 'render'. The template used for type render is ext.Asteroid.views.clean |


####<a name="methodAppend"/> append</a>
|Method Info|
|---------------|
| ```public object append(string $selector, string $view, closure $data)``` |
|Tells your Asteroid to dynamically load the Yii view $view ($this->renderPartial($view, $data()) and append it to the Dom Element(s) specified by $selector.|

|  Param |  Type | Description |
|---------------|----------------|----------------|
| $selector  | String  | JQuery Dom selector ie '#someDomID' |
| $view | String | Yii view file ie "_someView" |
| $data | Closure | Closure must return an associative array. This array is passed to the $view like so: ($this->renderPartial($view, $data())|


####<a name="methodPrepend"/> prepend</a>
|Method Info|
|---------------|
| ```public object prepend(string $selector, string $view, closure $data)``` |
|Tells your Asteroid to dynamically load the Yii view $view ($this->renderPartial($view, $data()) and prepend it to the Dom Element(s) specified by $selector.|

|  Param |  Type | Description |
|---------------|----------------|----------------|
| $selector  | String  | JQuery Dom selector ie '#someDomID' |
| $view | String | Yii view file ie "_someView" |
| $data | Closure | Closure must return an associative array. This array is passed to the $view like so: ($this->renderPartial($view, $data())|

####<a name="methodReplace"/> replace</a>
|Method Info|
|---------------|
| ```public object replace(string $selector, string $view, closure $data)``` |
|Tells your Asteroid to dynamically load the Yii view $view ($this->renderPartial($view, $data()) and replace the content of the Dom Element(s) specified by $selector.|

|  Param |  Type | Description |
|---------------|----------------|----------------|
| $selector  | String  | JQuery Dom selector ie '#someDomID' |
| $view | String | Yii view file ie "_someView" |
| $data | Closure | Closure must return an associative array. This array is passed to the $view like so: ($this->renderPartial($view, $data())|


####<a name="methodExecJS"/> execJS</a>
|Method Info|
|---------------|
| ```public object execJS(string $js)``` |
| Call this method to add arbitrary JavaScript. Takes String $js of valid JavaScript. execJS('alert("Yeah!");'). You should only calls this method if you need to execute addtional JS not provided by Asteroid |

|  Param |  Type | Description |
|---------------|----------------|----------------|
| $js  | String  | Valid Javascript |


####<a name="methodSendVars"/> sendVars</a>
|Method Info|
|---------------|
| ```public object sendVars(Closure $myVars)``` |
|Tells your Asteroid to attach variables returned by the closure $myVars to a request|

|  Param |  Type | Description |
|---------------|----------------|----------------|
| $myVars | Closure | Closure must return an associative array.|


####<a name="methodUseBelt"/> useBelt</a>
|Method Info|
|---------------|
| ```public object useBelt(String $beltPath, Mixed $methods,  Array $method_vars = array())``` |
|Stay DRY! Attach groups of predefined Asteroid Actions|

|  Param |  Type | Description |
|---------------|----------------|----------------|
| $beltPath | String | Path to belt class |
| $methods | Mixed | Contains the method(s) to call in your belt. Can also include the method_vars when passing multiple methods ( see [Example5](#_example5) )|
| $method_vars | Array | Vars to be sent to $method (optional) |


####<a name="methodOrbit"/> orbit</a>
|Method Info|
|---------------|
| ```public orbit()``` |
| Renders all JS and CSS dependencies. You must Call orbit() as the very last step after all comets have been initialized with Asteroid('id'); |

					