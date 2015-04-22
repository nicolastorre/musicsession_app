$(document).ready(function() {
	var item = document.getElementsByClassName('item-discu');
	for(var i=0; i < friend.length; i++) {
		if (friend[i] > 0) {
			var minibox = document.createElement("span");
			minibox.id = 'minibox-discu-'+i;
			minibox.innerHTML = friend[i];
			minibox.classList.add('miniboxdiscu');
			item[i].appendChild(minibox);
		}
	}

	setInterval( function() {
		$.getJSON("Messages/readerfd/",function(data){
			// console.log(JSON.stringify(data));
			var item = document.getElementsByClassName('item-discu');
			for(var i=0; i < friend.length; i++) {
				if(data[i]['pseudo'] != pseudo && data[i]['nb'] > 0) {
					var minibox = document.getElementById('minibox-discu-'+i);
					if (typeof(minibox) != 'undefined' && minibox != null) {
						minibox.innerHTML = data[i]['nb'];
					} else {
						var minibox = document.createElement("span");
						minibox.id = 'minibox-discu-'+i;
						minibox.innerHTML = data[i]['nb'];
						minibox.classList.add('miniboxdiscu');
						item[i].appendChild(minibox);
					}
				} else {
					var minibox = document.getElementById('minibox-discu-'+i);
					if (typeof(minibox) != 'undefined' && minibox != null) {
						minibox.parentElement.removeChild(minibox);
					}
				}
			}
		});
	}, 5000);
});