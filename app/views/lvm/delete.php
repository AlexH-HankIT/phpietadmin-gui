<div class = "container">
    <div class = "jumbotron">
        <p>
        <form method="post">
            LV:
            <select name="volumes" id="volumes" class="form-control">
                <option id="default">Select...</option>
                <?php for ($i = 0; $i < count($data); $i++) { echo '<option value=' . ($i+1) . '>' . $data[$i] . '</option>'; } ?>
            </select>
            <br />
            <input class="btn btn-primary" type='submit' value='Delete' onclick="return validatelogicalvolumedelete()">
        </form>
        </p>
    </div>
    <p>Hint: Only volumes which are not used by the iscsitarget daemon are displayed</p>
</div>