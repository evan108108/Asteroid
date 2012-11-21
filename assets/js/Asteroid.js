var Asteroid = function(asteroidConfig) {
	this.config = asteroidConfig;
	this.run();
};

Asteroid.prototype.run = function() {
	for(i=0;i<this.config.length;i++)
	{
		var myConfig = this.config[i];
		this.updateUI(myConfig);
	}
};

Asteroid.prototype.updateUI = function(myConfig)
{	
	var url =  this.createURL(myConfig.id);
	var element = myConfig.element;
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
		});
}

Asteroid.prototype.createURL = function(id) {
	url = document.URL;
	if(url.indexOf('?') > -1) return url + '&AsteroidActionID=' + id;
	return url + '?' + 'AsteroidActionID=' + id;
};

$(document).ready(function() {
	if(asteroidConfig != undefined)
		var myAsteroid = new Asteroid(asteroidConfig);
});




