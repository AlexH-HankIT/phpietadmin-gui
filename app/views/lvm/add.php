<div class="workspacedirect">
    <!-- Inserted via ajax -->

    <!-- Hidden fields to store some data -->
    <span id="volumegroup" hidden><?php echo htmlspecialchars($_POST['vg']); ?></span>
    <span id="freesize" hidden><?php echo htmlspecialchars($data); ?></span>

    <div class = "container">
        <div class = "jumbotron" id="lvmadd">
            <table class = "table borderless">
                <tr>
                    <p>
                        <td>Name:</td>
                        <td><input type="text" id="nameinput" class="form-control"/></td>
                    </p>
                </tr>
                <tr>
                    <p>
                        <td>Size in GB:</td>
                        <td>
                            <input id="sizefield" type="text" value="1" class="form-control"/>
                        </td>
                        <td>
                            <!-- Insert max value using jquery -->
                            <input id="rangeinput" type="range" min="1" max="" value="1" step="1" class="form-control"/>
                        </td>
                    </p>
                </tr>
                <tr>
                    <td>
                        <!-- Insert max value using jquery -->
                        (max <span id="maxvalue"></span>G)
                    </td>
                </tr>
            </table>
            <br />
            <button id="createvolumebutton" class="btn btn-primary" type='submit'>Create</button>
        <br>
        </div>
    </div>
</div>