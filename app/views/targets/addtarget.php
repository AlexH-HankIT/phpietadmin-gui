<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Add target</li>
    </ol>

    <div class = "jumbotron">
        <form method="post" class="form-horizontal">
            <div class="form-group">
                <div class="col-sm-1">
                    <label for="name" class="control-label">IQN:</label>
                    <br />
                    <br />
                    <input class="btn btn-primary" type="submit" value="Add" onclick="return validate_addtarget('<?=$data?>')"/>
                </div>

                <div class="col-sm-11">
                    <input class="form-control" type="text" value=<?=$data?> name="name" id="name" required/>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Input label next to input field -->