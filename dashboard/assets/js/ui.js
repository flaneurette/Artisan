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