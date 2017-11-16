window.onload = function(){
var engRead = JSON.parse(sessionStorage.getItem('engRead'));
var parentElem = document.getElementsByClassName('reading');
var textAreas = document.getElementsByTagName('TEXTAREA');
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
if(engRead){
	setSizeInTextarea(engRead.currentSize);
}
box.addEventListener("click", changeFontSize, false);
} else{
	if(box){
	box.removeEventListener("click", changeFontSize)
	}
}

function changeFontSize(evt){
	evt.preventDefault();
	
	var currentSize;
	if(evt.target == btnSmall){
		currentSize = '14px';	
	}
	if(evt.target == btnMedium){
		currentSize = '18px';	
	}
	if(evt.target == btnBig){
		currentSize = '21px';	
	}
	setSizeInTextarea(currentSize);
	var setSize = {
		currentSize: currentSize
	}
	sessionStorage.setItem('engRead', JSON.stringify(setSize));

}
function setSizeInTextarea(size){
	for (var i = 0; i < textAreas.length; i++) {
		textAreas[i].style.fontSize = size;
		}
}

}