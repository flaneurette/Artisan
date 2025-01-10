	/*
	app.http('dashboard/API.php?id=5', 'callback', mycall);
	
	function mycall(dataset) {
		let data = dataset.replaceAll('&gt;','>').replaceAll('&lt;','<');
		app.load({
			data: {
				content: JSON.parse(data)
			}
		});
	}
	*/
	app.load({
		data: {
			webshop: 'My Webshop',
			copyright: 'All rights reserved. © example.com',
			navigation: [
				{name: '', link: '<a href=""  :active="index:active-link" target="_self">Link1</a>'},
				{name: '', link: '<a href="/" :active="index:active-link" target="_self">Link2</a>'},
				{name: '', link: '<a href="/" :active="index:active-link" target="_self">Link3</a>'},
				{name: '', link: '<a href="/" :active="index:active-link" target="_self">Link4</a>'},
				{name: '', link: '<a href="/" :active="index:active-link" target="_self">Link5</a> '},
				{name: '', link: '<a href="/" :active="index:active-link" target="_self">Contact</a>'},
			]
		}
	});