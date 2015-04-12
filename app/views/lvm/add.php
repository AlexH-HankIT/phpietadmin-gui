<div id="leftDiv">
    <form method="post">
        <table border="0">
            <tr>
                <p>
                <td>Name:</td>
                <td><input type="text" name="name" id="name"/></td>
                </p>
            </tr>
            <tr>
                <p>
                <td>Size in GB:</td>
                <td>
                    <input id="sizefield" type="text" name="sizefield" value="1" oninput="validatelvinput(this.value, <?php echo $data[1] ?>)"/>
                </td>
                <td>
                    <input id="rangeinput" type="range" min="1" max="<?php echo $data[1] ?>" value="1" name="size" step="1" oninput="validatelvinput(this.value, <?php echo $data[1] ?>); updateTextInput(this.value)"/>
                    <span id="range">1</span>
                </td>
                </p>
            </tr>
            <tr>
                <td>
                    (max <?php echo $data[1] ?>G)
                </td>
            </tr>
        </table>
        <br />
        <input type='submit' value='Create' onclick="return validateinputnotempty('name')">
    </form>
    <br>
</div>