<div class="workspacedirect">
    <!-- Inserted via ajax -->

    <!-- Hidden fields to store some data -->
    <span id="volumegroup" hidden><?php echo htmlspecialchars($_POST['vg']); ?></span>
    <span id="freesize" hidden><?php echo htmlspecialchars($data); ?></span>

    <div class = "container">
        <div class = "jumbotron" id="lvmadd">
            <div class="table-responsive">
                <table class = "table borderless">
                    <tr>
                        <td>Name:</td>
                        <td><input type="text" id="nameinput" class="form-control"/></td>
                    </tr>
                    <tr>
                        <td>Size in GB:</td>
                        <td><input id="sizefield" type="text" value="1" class="form-control"/></td>
                        <td><input id="rangeinput" type="range" min="1" max="" value="1" step="1" class="form-control"/></td>
                    </tr>
                    <tr>
                        <td>(max <span id="maxvalue"></span>G)</td>
                    </tr>
                </table>
            </div>
            <button id="createvolumebutton" class="btn btn-primary" type='submit'>Create</button>
        </div>
    </div>
</div>