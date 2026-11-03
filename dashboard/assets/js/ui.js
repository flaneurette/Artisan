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

    tableClass = '';
    tableId = '';
    inputId = '';
    dragged = null;

    init = () => {
        const rows = document.getElementsByClassName(this.tableClass);
        for (const row of rows) {
            row.setAttribute('draggable', true);

            row.addEventListener('dragstart', (e) => {
                this.dragged = row;
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', ''); // required for Firefox
            });

            row.addEventListener('dragover', (e) => {
                e.preventDefault(); // marks this as a valid drop target
                if (!this.dragged || row === this.dragged) return;
                if (row.parentNode !== this.dragged.parentNode) return;

                const rect = row.getBoundingClientRect();
                const after = e.clientY > rect.top + rect.height / 2;
                row.parentNode.insertBefore(this.dragged, after ? row.nextSibling : row);
            });

            row.addEventListener('drop', (e) => {
                e.preventDefault();
                this.end();
            });

            row.addEventListener('dragend', () => {
                this.dragged = null;
                this.end();
            });
        }
    }

    end = () => {
        const rows = document.getElementsByClassName(this.tableClass);
        for (let i = 0; i < rows.length; i++) {
            const original = rows[i].id.replace(this.tableId, '');
            document.getElementById(this.inputId + (i + 1)).value = original + ':' + i;
        }
    }
}
