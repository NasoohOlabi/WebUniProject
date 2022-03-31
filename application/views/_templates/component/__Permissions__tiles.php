<div id="<?= $sub_cls ?>-container" class="scrolling-wrapper">
	<div style="width:100%;height:100%">
		<div class="login-container">
			<div class="form-block">
				<h1 style="display:flex"><span style="display:flex;align-items:center">Permissions</span> <button id="permission-grant-submit-btn">Save ✔</button>
				</h1>
			</div>
			<form>

				<?php
				if (in_array('date', $sub_cls::SQL_Columns())) {
					date_input('date');
				}
				datalist_input("Permission", $SELECT_OPTIONS);
				?>

				<input type="submit" value="Add" id="add-Permission-dependant-btn" class="add-tile-dependant-btn">

				</input>
			</form>
		</div>
		<div style="display: flexbox;"></div>
	</div>
</div>
<script>
	let i = 2;

	var Model = []
	var submissions = []

	function cleanInputs(form) {
		form.querySelectorAll("input").forEach(elem => {
			if (elem.type != 'submit')
				elem.value = '';
		})
		form.querySelectorAll("select").forEach(elem => {
			elem.value = '';
		})
	}

	function readInputs(form) {
		/**
		 * @type {{[index:string]:string}}
		 */
		const dic = {};
		form.querySelectorAll("input").forEach(elem => {
			dic[elem.name] = elem.value.trim();
		})
		form.querySelectorAll("select").forEach(elem => {
			dic[elem.name] = elem.value.trim();
		})
		return dic;
	}
	addLoadEvent(() => {
			const func = (event, id = null, name = null) => {
				event && event.preventDefault();
				const form_element = document.getElementById("add-Permission-dependant-btn").parentElement;
				const form_obj = readInputs(form_element)
				const datalist = form_element.querySelector('datalist')
				const NAME = name || form_obj['Permission']
				const input_text = NAME
				if (![].slice.call(datalist.options).map(option => option.value).includes(input_text)) return;
				let selected_option;
				for (const option of datalist.options) {
					if (option.value == input_text) {
						option.disabled = true;
						selected_option = option;
						break;
					}
				}
				form_element.querySelectorAll('.text-input').forEach(elem => {
					if (elem.type != 'submit') elem.value = ''
				})

				const ID = id || selected_option.label

				const newKid = document.createElement('div')
				newKid.style.margin = "1%"
				newKid.innerHTML = input_text
				newKid.className = "login-container mini"
				newKid.value = ID
				if (Model['<?= $sub_cls ?>'] == undefined)
					Model['<?= $sub_cls ?>'] = []

				Model.push({
					'Permission_id': selected_option.label,
					name: input_text
				})

				submissions.push(selected_option.label)

				const del_X = document.createElement('i')
				del_X.className = "fa fa-close"
				del_X.style = "float:right;font-size:1.2em"
				del_X.onclick = () => {
					selected_option.disabled = false
					del_X.parentElement.remove()
					submissions = submissions.filter(id => id != ID)
				}
				newKid.appendChild(del_X);
				form_element.parentElement.parentElement.children[form_element.parentElement.parentElement.children.length - 1].appendChild(newKid)
			} //close event for btn
			<?= json_encode($CONTEXT_TILES_IDs) ?>.forEach(id_name => {
				func(null, id_name.id, id_name.name)
			})
			document.getElementById("add-Permission-dependant-btn").addEventListener('click', func)
			document.getElementById("permission-grant-submit-btn").addEventListener('click', () => {
				fetch(window.location.href, {
					method: "POST",
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						permission_ids: submissions
					})
				})
				const s = window.location.href
				window.location = s.substring(0, s.indexOf('update'))
			})
		} //close addLoadEvent
	)
</script>