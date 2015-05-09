<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Volume groups</li>
    </ol>
    <div id = 'vginput' class = "jumbotron">
        <form method="post">
            <select name="vg_post" multiple class="form-control" onchange="showlvinput(this.value)">
                <?php for ($i = 0; $i < count($data); $i++) { ?>
                    <option value="<?=$data[$i]?>"> <?=$data[$i]?> </option>
                <?php } ?>
            </select>
            <br />
        </form>
    </div>
</div>
<div id="lv"></div>