<body>
	<div id="main-content" class="inlineBlock">
		<div id="noner" style="display:none;">
			<?php
			require_once 'application/views/_templates/form.php';
			TileForThis($cls, 'role_has_permission', $bm);
			?>
			<script>
				addLoadEvent(() => {
					document.querySelector(".form-block > button").onclick()
					document.querySelector("h2").remove()
					document.querySelector("#noner").style.display = "block"
					document.querySelector("#add-Permission-dependant-btn").onclick = (evt) => {
						const form = document.querySelector('form')

						const payload = {}

						form.querySelectorAll('input').forEach(elem => {
							payload[elem.name] = elem.value
							if (elem.type != 'submit')
								elem.value = '';
						})

						// TODO:
						// TODO:
						// TODO:
						// TODO:
						// TODO:	
						// TODO:
						//FIXME:
						//FIXME:	
						//FIXME:
						//FIXME: USE __tiles_for_this.php
						//FIXME:
						//FIXME:
						//FIXME:
						//FIXME:
						//FIXME:
						console.log(payload);

						// fetch('/api/role_has_permission', {
						// 	method: 'POST',
						// 	body: JSON.stringify(payload),
						// 	headers: {
						// 		'Content-Type': 'application/json'
						// 	}
						// }).then(res => res.json()).then(res => {
						// 	console.log(res)
						// 	if (res.success) {
						// 		alert("Success")
						// 		cleanInputs(form)
						// 	} else {
						// 		alert("Error")
						// 	}
						// })
					}
				})
			</script>
		</div>
	</div>
</body>