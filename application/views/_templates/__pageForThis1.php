<style>
    .scrolling-wrapper > div {
        margin: 1em;
    }
</style>
<script>
    (() => {
            let i = 2;
            addLoadEvent(() => {
                        document.getElementById("add-<?= $sub_cls ?>-dependant-btn").onclick = (event) => {
                                console.log("catchMe");
                                const btn = document.getElementById("add-<?= $sub_cls ?>-dependant-btn");
                                btn.insertAdjacentHTML("beforebegin", `<div style="min-width:375px"><button class="remove-div-x-btn" onclick="this.parentElement.remove()"><i style="font-size:x-large" class="fa fa-close"></i></button>