<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Add logical volumes</li>
    </ol>

    <div class = "jumbotron">
        <form method="post">
            <table class = "table borderless">
                <tr>
                    <p>
                    <td>Name:</td>
                    <td><input type="text" name="name" id="name" class="form-control"/></td>
                    </p>
                </tr>
                <tr>
                    <p>
                    <td>Size in GB:</td>
                    <td>
                        <input id="sizefield" type="text" name="sizefield" value="1" oninput="validatelvinput(this.value, <?php echo $data ?>)" class="form-control"/>
                    </td>
                    <td>
                        <input id="rangeinput" type="range" min="1" max="<?php echo $data ?>" value="1" name="size" step="1" oninput="validatelvinput(this.value, <?php echo $data ?>); updateTextInput(this.value)" class="form-control"/>
                        <span id="range">1</span>
                    </td>
                    </p>
                </tr>
                <tr>
                    <td>
                        (max <?php echo $data ?>G)
                    </td>
                </tr>
            </table>
            <br />
            <input class="btn btn-primary" type='submit' value='Create' onclick="return validateinputnotempty('name')">
        </form>
    <br>
    </div>
</div>