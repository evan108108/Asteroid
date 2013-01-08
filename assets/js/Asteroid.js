var Asteroid = function(asteroidConfig) {
	this.config = asteroidConfig;
	this.run();
};

Asteroid.prototype.run = function() {
	for(i=0;i<this.config.length;i++)
	{
		var myConfig = this.config[i];
		myConfig.index = i;
		myConfig.vars = "";
		if(myConfig.listen.event == 'load' && myConfig.listen.selector == 'body')
			this.updateUI(myConfig);
		else
			this.attachListener(myConfig);
	}
};

Asteroid.prototype.updateUI = function(myConfig)
{	
	var url =  this.createURL(myConfig);
	var element = myConfig.element;
	var config = this.config;
	$(element).addClass('asteroidLoader');
	var xhr = $.get(url, function(data) {;
			config[myConfig.index].vars = xhr.getResponseHeader('x-asteroid-vars');
			switch(myConfig.renderType)
			{
				case 'append':
					$(element).append(data);
					break;
				case 'prepend':
					$(element).prepend(data);
					break;
				default:
					$(element).html(data);
			}
			$(element).removeClass('asteroidLoader');
	});
}

Asteroid.prototype.createURL = function(myConfig) {
	url = document.URL;
	if(url.indexOf('#') > -1) url = url.split('#')[0];
	if(url.indexOf('?') > -1) url += '&';
	else url += '?';
	return url + 'AsteroidActionID=' + myConfig.id + '&' + 'asteroid_vars=' + this.config[myConfig.index].vars;
};

Asteroid.prototype.attachListener = function(myConfig) {
	//alert(myConfig.listen.selector);
	var myConfig = myConfig;
	var me = this;
	$('body').delegate(myConfig.listen.selector, myConfig.listen.event, function() {
		me.updateUI(myConfig);
	});
};

$(document).ready(function() {
	if(asteroidConfig != undefined)
		var myAsteroid = new Asteroid(asteroidConfig);
		window.myAsteroid = myAsteroid;
});





