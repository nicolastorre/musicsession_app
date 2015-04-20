$(document).ready(function() {
	var iframe_tab = document.getElementsByTagName('iframe');
	for (i = 0; i < iframe_tab.length; i++) {
		iframe_tab[i].style.display = "none";
	}

	var expand_tab = document.getElementsByClassName('expand');
	for(var i = 0; i < expand_tab.length; i++) {
		expand_tab[i].onclick = function(evt) {
			var score = document.getElementById('iframe_' + this.id);
			if(score.style.display == "none") {
				this.style.backgroundImage = "url('Ressources/public/images/app/unexpand.png')";
				score.style.display = "block";
			} else {
				this.style.backgroundImage = "url('Ressources/public/images/app/expand.png')";
				score.style.display = "none";
			}
		}
	}
});