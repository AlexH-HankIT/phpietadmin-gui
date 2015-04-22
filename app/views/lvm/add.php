<div class = "container">
    <div class = "jumbotron" id="lvmadd">
        <form method="post">
            <table class = "table borderless">
                <input type="hidden" name="vg" value=<?=$_POST['vg']?>>
                <tr>
                    <p>
                    <td>Name:</td>
                    <td><input type="text" name="name" id="name" class="form-control" required/></td>
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
        <button class="btn btn-primary" type='submit' value=<?=$_POST['vg']?> onclick="">Create</button>
        </form>
    <br>
    </div>
</div>