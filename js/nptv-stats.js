jQuery(document).ready(function() {
	console.log("nptv-stats")
	var checkExist = setInterval(function() {
	if (jQuery('td.views').length) {
	      console.log("Exists!");
	      var view = jQuery('td.views');
			var count = 0;
			while(count < view.length){
				//console.log(view[count].innerHTML);
				var old = parseInt(view[count].innerHTML)
				var new_value = old * 5;
				view[count].innerHTML = new_value;
				count++;
			}
	      clearInterval(checkExist);
	   }
	}, 100); // check every 100ms
	// setTimeout(function(){
	// 	var view = jQuery('td.views');
	// 	var count = 0;
	// 	while(count < view.length){
	// 		//console.log(view[count].innerHTML);
	// 		var old = parseInt(view[count].innerHTML)
	// 		var new_value = old * 5;
	// 		view[count].innerHTML = new_value;
	// 		count++;
	// 	}	
	// }, 2200);
	
});