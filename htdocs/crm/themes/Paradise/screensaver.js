
SUGAR.themes.boat = function() {
	/* Boat animation delay timer handle */
	var timer;
	
	/* Boat animation handle */
	var animation;
	
	/* Time in seconds of no mouse or keyboard activity
	 * before the boat starts moving */
	var delay;
	
	/* Id of the 'boat' element to animate */
	var targetId;
	
	/* Initial position of the boat */
	var startXY;
	
	return {
		/* Initialize boat screensaver */
		init: function() {
			SUGAR.themes.boat.delay = 300000;
			SUGAR.themes.boat.timer = null;
			SUGAR.themes.boat.animation = null;
			SUGAR.themes.boat.startXY = [0, 24];
			SUGAR.themes.boat.targetId = 'screensaver_boat';
			if(document.getElementById(SUGAR.themes.boat.targetId)) {
				/* Start off with a dummy object */
				SUGAR.themes.boat.animation = {isAnimated: function(){ return false }};
				/* Set up interrupt handlers */
				YAHOO.util.Event.addListener(window, "mousemove", SUGAR.themes.boat.interrupt);
				YAHOO.util.Event.addListener(window, "keypress", SUGAR.themes.boat.interrupt);
				/* Go */
				SUGAR.themes.boat.resetTimer();
			}
		},
	
		/* On interrupt event handler */
		interrupt: function() {
			if(SUGAR.themes.boat.animation.isAnimated()) {
				SUGAR.themes.boat.animation.stop();
				document.getElementById(SUGAR.themes.boat.targetId).style.display = 'none';
			}
			SUGAR.themes.boat.resetTimer();
		},
		
		/* Reset the boat animation delay timer */
		resetTimer: function() {
			window.clearTimeout(SUGAR.themes.boat.timer);
			SUGAR.themes.boat.timer = window.setTimeout(SUGAR.themes.boat.animate, SUGAR.themes.boat.delay);
		},
		
		/* Animate the boat. We have to re-instantiate the Motion object
		 * each time, or else we get the terrible accelleration effect */
		animate: function() {
			document.getElementById(SUGAR.themes.boat.targetId).style.display = '';
			var attributes = {points: {to: SUGAR.themes.boat.getEndXY(), from: SUGAR.themes.boat.startXY}};
			var duration = SUGAR.themes.boat.getDuration();
			SUGAR.themes.boat.animation = new YAHOO.util.Motion(SUGAR.themes.boat.targetId, attributes, duration);
			SUGAR.themes.boat.animation.onComplete.subscribe(SUGAR.themes.boat.reAnimate);
			SUGAR.themes.boat.animation.animate();
		},
		
		/* restart the animation when the boat hits the end */
		reAnimate: function() {
			document.getElementById(SUGAR.themes.boat.targetId).style.display = 'none';
			window.clearTimeout(SUGAR.themes.boat.timer);
			SUGAR.themes.boat.timer = window.setTimeout(SUGAR.themes.boat.animate, 2000);
		},
		
		/* Determine animation end point */
		getEndXY: function() {
			return [YAHOO.util.Dom.getViewportWidth(), SUGAR.themes.boat.startXY[1]];
		},
		
		/* Adjust the time so the speed remains constant */
		getDuration: function() {
			return 25*YAHOO.util.Dom.getViewportWidth()/1280;
		}
	};
}();	
