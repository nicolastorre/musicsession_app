	var category = document.getElementById('category');
	var addcategory = document.getElementById('other');
	addcategory.style.display = "none";
	addcategory.previousSibling.style.display = "none";
	category.onchange = function() {
		if (category.options[category.selectedIndex].value == "other") {
				var addcategory = document.getElementById('other');
				addcategory.style.display = "block";
				addcategory.previousSibling.style.display = "block";
		} else {
				var addcategory = document.getElementById('other');
				addcategory.style.display = "none";
				addcategory.previousSibling.style.display = "none";
		}
	}