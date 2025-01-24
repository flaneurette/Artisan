var plainui = { 

	post: function() {
		
		let c = document.getElementById('count').value;
		for(let i=0; i < c; i++) {
			this.proc('texteditor-' + i,'component_text_' + i);
		}
		
		document.getElementById('form').submit();
	},

	proc: function(from,to) {
	var text = document.getElementById(from).innerHTML;
		document.getElementById(to).value = text;
	},

	show: function(what) {
		if(what) {
			document.getElementById(what).style = 'display:block;'
		}
	},
	
	thumb: function(what,where) {
		if(what && where) {
			document.getElementById(where).src = what;
		}
	}
	
};

class dragndrop  {

	static htmlId = '';
	static html = '';
	
	drag = function(event,data) {
		this.htmlId = event.id;
		this.html = event.innerHTML;
	}

	allow = function(event) {
	  event.preventDefault();
	}

	drop = function(event) {
		let dragged = document.getElementById(this.htmlId);
		let dragto = document.getElementById(event.id);
		let copy = dragged.cloneNode();
		copy.innerHTML = this.html;
		dragto.appendChild(copy);
		dragto.parentNode.insertBefore(copy, event);
		dragged.parentNode.removeChild(dragged);
		this.end();
	}
	
	end = function(e) {
		let elems = document.getElementsByClassName(this.tableClass);
		let j=1;
		for(var i=0;i<elems.length;i++) {
			let ordering = elems[i].getAttribute('id').split(this.tableId).toString();
			ordering = ordering.replace(',','').toString();
			document.getElementById(this.input + j).value = ordering +':' + j;
			j++;
		}
	}
}
