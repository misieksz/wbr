
   window.onload = function() {
           var sub = document.querySelectorAll('.sub');
		
		for(var i=0; i<sub.length; i++)
		{
		var index = sub[i].textContent.match(/[A-Za-z]+\./).index;
		
		var subArt = sub[i].textContent.substr(0, index+4);
		
		sub[i].innerHTML = "";
		
		sub[i].textContent = subArt;
		}
   }
