<style>
    .none {
        display: none;
    }
</style>
<form method="post">
    <p>Text:
        <input type="text" name="name">
    </p>
    <p>Number Of Choices:
        <input type="text" id="number_of_choices" name="number_of_choices">
    </p>
    <p id="choice1">1. Choice:
        <input type="text" name="choice1">
    </p>
    <p id="choice2" class="none">2. Choice:
        <input type="text" name="choice2">
    </p>
    <p id="choice3" class="none">3. Choice:
        <input type="text" name="choice3">
    </p>
    <p id="choice4" class="none">4. Choice:
        <input type="text" name="choice4">
    </p>
    <p id="choice5" class="none">5. Choice:
        <input type="text" name="choice5">
    </p>
    <p id="choice6" class="none">6. Choice:
        <input type="text" name="choice6">
    </p>
    <p id="choice7" class="none">7. Choice:
        <input type="text" name="choice7">
    </p>
    <p>Topic:
        <select>
            <?php
            foreach ($topics as $topic) {
                echo "<option value=\"$topic->id\">$topic->name</option>";
            }
            ?>
        </select>
    </p>
    <p><input type="submit" value="Add New" />
        <a href="index.php">Cancel</a>
    </p>
</form>
<script>
    function main() {
        document.getElementById('number_of_choices').addEventListener("input", () => {
            const v = document.getElementById('number_of_choices').value
            let a = [1, 2, 3, 4, 5, 6, 7]
            a = a.map(i => document.getElementById("choice" + i))
            a.forEach((node, index) => {
                if (index >= v) {
                    node.style.display = "none";
                } else {
                    node.style.display = "block";
                }
            })
        })
    }
    addLoadEvent(main);
</script>