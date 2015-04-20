$(document).ready(function() {
	setInterval( function() {
		$.getJSON("Notifications/reader/",function(data){
			// alert(data.nb);
			if(data.nb > 0) {
				var notif = document.getElementById("menu-Notifications");
				var minibox = document.getElementById('minibox-notif');
				if (typeof(minibox) != 'undefined' && minibox != null) {
					minibox.innerHTML = data.nb;
				} else {
					var minibox = document.createElement("span");
					minibox.id = "minibox-notif";
					minibox.innerHTML = data.nb;
					minibox.classList.add('minibox');
					notif.appendChild(minibox);
				}
			}
		});
		$.getJSON("Messages/reader/",function(data){
			// alert(data.nb);
			if(data.nb > 0) {
				var msg = document.getElementById("menu-Messages");
				var minibox = document.getElementById('minibox-msg');
				if (typeof(minibox) != 'undefined' && minibox != null) {
					minibox.innerHTML = data.nb;
				} else {
					var minibox = document.createElement("span");
					minibox.id = "minibox-msg";
					minibox.innerHTML = data.nb;
					minibox.classList.add('minibox');
					msg.appendChild(minibox);
				}
			}
		});
    }, 5000);
});