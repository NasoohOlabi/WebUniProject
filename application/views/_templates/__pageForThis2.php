</div>`.replace(`<div class="form-block">
    <input name="ProfileImg" class="file-input" type="file" id="formFile" accept="image/png, image/jpeg" />
</div>`,''))
for (let btn of document.querySelectorAll(`form .form-block button`)){
btn.style.display = `none`;
}
let pChildren = event.target.parentElement.children
pChildren[pChildren.length -2].querySelector('input').focus();
}//close event for btn
})//close addLoadEvent
})()
</script>
<div class="form-block"><button onclick="
            document.getElementById(`<?= $sub_cls ?>-title`).style.display =
             (document.getElementById(`<?= $sub_cls ?>-title`).style.display == `none`)?`block`:`none`;
            
             document.getElementById(`<?= $sub_cls ?>-container`).style.display = 
            (document.getElementById(`<?= $sub_cls ?>-container`).style.display == `none`)?`flex`:`none`;
            
            document.querySelector(`.form-block button`).style.display = 
            (document.getElementById(`<?= $sub_cls ?>-container`).style.display == `none`)?`block`:`none`
            this.children[this.children.length-1].className = (this.children[this.children.length-1].className === 'fas fa-angle-down')?'fas fa-angle-up':'fas fa-angle-down'">Add <?= $sub_cls ?> For This <?= get_class($cls) ?><i class="fas fa-angle-down" style="margin-left:50px"></i></button></div>
<h2 style="display:none;padding-left:10%;text-decoration:underline" id="<?= $sub_cls ?>-title"><?= $sub_cls ?>s:</h2>
<div id="<?= $sub_cls ?>-container" class="scrolling-wrapper" style="display:none;">
    <button id="add-<?= $sub_cls ?>-dependant-btn" class="add-dependant-btn">+</button>
</div>