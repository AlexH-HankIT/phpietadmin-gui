<!-- Do this with ajax later!-->
<div class = "container">
    <h1>Volume groups</h1>
    <div class = "jumbotron">
        <form method="post">
            <select name="vg_post" id="vg" multiple class="form-control">
                    <?php for ($i = 0; $i < count($data); $i++) { echo '<option value=' . ($i+1) . '>' . $data[$i] . '</option>'; } ?>
            </select>
            <br />
            <input class="btn btn-primary" type='submit' value='Show' onclick="return validatevginput()">
        </form>
    </div>
</div>