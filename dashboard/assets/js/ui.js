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

class dragndrop {

    static htmlId = '';
    static html = '';

    init = function() {
        let elems = document.getElementsByClassName(this.tableClass);
        var j = 1;
        for (var i = 0; i <= elems.length; i++) {
            if (elems[i]) {
                let att = document.getElementById(this.tableId + j);
                if (att) {
                    elems[i].setAttribute("draggable", true);
                    elems[i].addEventListener('drag', (event) => {
                        this.drag(event);
                    });
                }
            }
            j++;
        }
    }
	
    drag = function(event) {
	let xy = document.elementFromPoint(event.clientX, event.clientY).parentNode;
	if (event.target.parentNode == xy.parentNode) {
		if(xy == event.target.nextSibling) {
			xy = xy.nextSibling;
		}
		event.target.parentNode.insertBefore(event.target, xy);
	}
	this.end();
    }
	
    end = function(e) {
        let elems = document.getElementsByClassName(this.tableClass);
        let j = 1;
        for (var i = 0; i < elems.length; i++) {
            let ordering = elems[i].getAttribute('id').split(this.tableId).toString();
            ordering = ordering.replace(',', '').toString();
            document.getElementById(this.inputId + j).value = ordering + ':' + i;
            j++;
        }
    }
}
