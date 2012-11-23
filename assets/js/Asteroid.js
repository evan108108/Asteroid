var Asteroid = function(asteroidConfig) {
	this.config = asteroidConfig;
	this.run();
};

Asteroid.prototype.run = function() {
	for(i=0;i<this.config.length;i++)
	{
		var myConfig = this.config[i];
		if(myConfig.listen.event == 'load' && myConfig.listen.selector == 'body')
			this.updateUI(myConfig);
		else
			this.attachListener(myConfig);
	}
};

Asteroid.prototype.updateUI = function(myConfig)
{	
	var url =  this.createURL(myConfig.id);
	var element = myConfig.element;
	$(element).addClass('asteroidLoader');
	$.get(url, function(data) {;
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

Asteroid.prototype.createURL = function(id) {
	url = document.URL;
	if(url.indexOf('?') > -1) return url + '&AsteroidActionID=' + id;
	return url + '?' + 'AsteroidActionID=' + id;
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
});




