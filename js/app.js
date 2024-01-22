var menu = false;
function toggleMenu(){
	var obj = document.querySelectorAll(".desktop");
	for (var i = obj.length - 1; i >= 0; i--) {
		obj[i].style.display = (menu) ? 'none' : 'block';
	}
	menu = !menu;
}