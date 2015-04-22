$(document).ready(function() {
	for (var i=0; i < friend.length; i++) {
		if (nbminiboxnotif > 0) {
			var notif = document.getElementById("menu-Notifications");
			var miniboxnotif = document.createElement("span");
			miniboxnotif.id = "minibox-menu-Notifications";
			miniboxnotif.innerHTML = nbminiboxnotif;
			miniboxnotif.classList.add('minibox');
			notif.appendChild(miniboxnotif);
			miniboxnotif.style.left = "137px";
		}
	}

	// setInterval( function() {
	// 	$.getJSON("Notifications/reader/",function(data){
	// 		// alert(data.nb);
	// 		if(data.nb > 0) {
	// 			var notif = document.getElementById("menu-Notifications");
	// 			var minibox = document.getElementById('minibox-menu-Notifications');
	// 			if (typeof(minibox) != 'undefined' && minibox != null) {
	// 				minibox.innerHTML = miniboxmessages;
	// 			} else {
	// 				var minibox = document.createElement("span");
	// 				minibox.id = "minibox-menu-Notifications";
	// 				minibox.innerHTML = data.nb;
	// 				minibox.classList.add('minibox');
	// 				notif.appendChild(minibox);
	// 				minibox.style.left = "137px";
	// 			}
	// 		} else {
	// 			var minibox = document.getElementById('minibox-menu-Notifications');
	// 			if (typeof(minibox) != 'undefined' && minibox != null) {
	// 				minibox.parentElement.removeChild(minibox);
	// 			}
	// 		}
	// 	});
 //    }, 5000);
});