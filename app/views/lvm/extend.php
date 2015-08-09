<div class="workspacedirect">
    <div id="leftDiv">
        <br>
        <form method="post">
            <table border="0">
                <tr>
                    <td>New size:</td>
                    <td><input type="range" min="<?php echo htmlspecialchars($data[1]); ?>" max="<?php echo htmlspecialchars($data[0]); ?>" value="1" name="size" step="1" onchange="showValue(this.value + 'G')"  oninput="showValue(this.value + 'G')">
                        <span id="range"><?php echo htmlspecialchars($data[1]); ?>G</span>
                        <script type="text/javascript">
                            function showValue(newValue) {
                                document.getElementById("range").innerHTML=newValue;
                            }
                        </script>
                    <td><input type='submit' value='Extend'></td>
                </tr>
            </table>
        </form>
    </div>
</div>