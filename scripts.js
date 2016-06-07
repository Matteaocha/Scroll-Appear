window.$ = jQuery;

(function (ScrollAppear) {

	var appearClasses = [
		{
			before: 'appear-from-left',
			after: 'left-appeared'
		},
		{
			before: 'appear-from-right',
			after: 'right-appeared'
		},
		{
			before: 'appear-from-top',
			after: 'top-appeared'
		},
		{
			before: 'appear-from-bottom',
			after: 'bottom-appeared'
		},
		{
			before: 'appear-from-nothing',
			after: 'nothing-appeared'
		}
	]

	$(document).ready(function () {

		var getActionableElementsMethod = (ScrollAppear.dynamicElements ? findActionableElements : reuseActionableElements(appearClasses))
		var appearItemsMethod = appearItems(appearClasses, getActionableElementsMethod)

		appearItemsMethod()
		$(window).on("scroll", appearItemsMethod)

	})

	//-------------------------------------------------------

	function reuseActionableElements(appearClasses) {

		var actionableElements = []
		for(var i=0; i < appearClasses.length; i++) {
			actionableElements.push($('.' + appearClasses[i].before))
		}

		return function (appearClass) {
			var index = indexOfAppearClass(appearClasses, appearClass)

			return actionableElements[index].filter( function (elem) {
				return !$(elem).hasClass(appearClass.after)
			})
		}
	}

	function findActionableElements (appearClass) {

		return $('.' + appearClass.before).filter( function (elem) {
			return !$(elem).hasClass(appearClass.after)
		})
	}

	//-------------------------------------------------------

	function appearItems (appearClasses, getActionableElementsMethod) {

		return function () {
			appearClasses.forEach(function (appearClass, index) {
				var appearItems = getActionableElementsMethod(appearClass)

				for(var i=0; i < appearItems.length; i++) {
					if(itemShouldAppear(appearItems[i])) {
						appearItem(appearItems[i], appearClass)
					}				
				}
			})
		}
	}

	function itemShouldAppear (elem) {
		if($(elem).offset().top < (window.innerHeight + $(window).scrollTop() - 100) ) return true
		else return false
	}

	function appearItem (elem, appearClass) {

		var appearDelay = getAppearDelay(elem)
		window.setTimeout(function () {
			$(elem).addClass(appearClass.after)
		}, appearDelay)
	}

	function getAppearDelay(elem) {
		var elemClasses = elem.className.split(/\s+/)

		for(var i=0; i < elemClasses.length; i++) {
			var match = elemClasses[i].match(/appear-delay-([0-9]+)/)
			if(match) return parseInt(match[1])
		}
		return 1
	}

	//---------------------------------------------

	function indexOfAppearClass(appearClasses, appearClass) {
		for(var i in appearClasses) {
			if(appearClasses[i].before === appearClass.before) return i
		}
		return -1
	}

})(window.ScrollAppear ? window.ScrollAppear : window.ScrollAppear = {})