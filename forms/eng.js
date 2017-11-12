//var parentElem = document.getElementById('readingForm');
(function(){

var parentElem = document.getElementsByClassName('reading');
if(parentElem[0]){
	var box = document.createElement("Div");
	box.setAttribute('id','boxBtn');
	parentElem[0].appendChild(box);
	var btnSmall = document.createElement('Button');
	btnSmall.setAttribute('id', 'btnSmall');
	btnSmall.textContent = 'A';
	btnSmall.style.fontSize = '12px';
	box.appendChild(btnSmall);
	var btnMedium = document.createElement('Button');
	btnMedium.setAttribute('id', 'btnMedium');
	btnMedium.textContent = 'A';
	btnMedium.style.fontSize = '16px';
	box.appendChild(btnMedium);
	var btnBig = document.createElement('Button');
	btnBig.setAttribute('id', 'btnBig');
	btnBig.textContent = 'A';
	btnBig.style.fontSize = '22px';
	box.appendChild(btnBig);

box.addEventListener("click", changeFontSize, false);
function changeFontSize(evt){
	evt.preventDefault();
	var textArea = document.getElementById('text_area');
	if(evt.target == btnSmall){
		textArea.style.fontSize = '14px';	
	}
	if(evt.target == btnMedium){
		textArea.style.fontSize = '18px';	
	}
	if(evt.target == btnBig){
		textArea.style.fontSize = '21px';	
	}
}
}

})();