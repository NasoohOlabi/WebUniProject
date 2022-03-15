<style>
    .scrolling-wrapper div {
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
                                btn.insertAdjacentHTML("beforebegin", `