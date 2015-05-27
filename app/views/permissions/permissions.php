<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Permissions</li>
    </ol>

    <div id="selection">
        <div class="jumbotron">
            <select name="permission_type" multiple class="form-control">
                    <option value="incoming_user">Incoming User</option>
                    <option value="outcoming_user">Outcoming User</option>
                    <option value="ip">IP</option>
                    <option value="initiator">Initiator</option>
            </select>
        </div>
    </div>

    <div id="data">
        <!-- Data will be inserted here with jquery and ajax based on the selection -->
    </div>
</div>