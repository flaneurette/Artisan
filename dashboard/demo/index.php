<!DOCTYPE html>
<html>
<head>
<title>Artisan UX.js demo</title>
<style>

.component {
	display:block; 
	clear:left; 
	padding: 20px;
}

img {
	float:left; 
	padding-right: 20px;
}

</style>
<script src="UX.js"></script>
</head>
<body>

<div id="app">
<h1>{{Title}}</h1>
  <div>
    <div :loop="Content">
	<div class="component">
		 <p><img src="../resources/content/{{component_image}}" width="300" :image="../resources/content/{{component_image}}"/></p>
		  <h1>{{component_title}}</h1>
		 <p>{{component_text}}</p>
	 </div>
  </div>
</div>
	
	<script>

	let app = new UX();
	
	app.http('../API.php?filetype=json', 'callback', mycall);
	
	function mycall(dataset) {
		let data = dataset.replaceAll('&gt;','>').replaceAll('&lt;','<');
		app.load({
			data: {
				Title: 'Artisan UX.js demo',
				Content: JSON.parse(data)
			}
		});
	}
	
	</script>
</body>
</html>