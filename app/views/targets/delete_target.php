<div id='configure_target_body'>
    <div class="container">
        <?php if (isset($data['session'])) { ?>
            <div align="center" class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Warning - Target has open sessions!</h3></div>
        <?php } ?>
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li><a href='#'>Configure</a></li>
                <li class='active'>Delete target</li>
            </ol>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>
                            <input type="radio" name="lundeletion" checked value="0"> Detach LUN(s)
                        </label> (No data will be deleted)
                    </div>
                </div>
                <div class="row top-buffer">
                    <div class="col-md-12">
                        <label>
                            <input type="radio" name="lundeletion" value="1"> Delete attached LUN(s)
                        </label> (LVM only, data will be deleted!)
                    </div>
                </div>
                <div class="row top-buffer">
                    <div class="col-md-12">
                        <label>
                            <input id="deleteacl" type="checkbox" checked <?php if (isset($data['session'])) echo 'disabled' ?>> Delete acl
                        </label> from initiator allow and target allow
                    </div>
                </div>
                <div class="row top-buffer">
                    <div class="col-md-12">
                        <label>
                            <input id="force" type="checkbox" <?php if (isset($data['session'])) echo 'checked disabled' ?>> Force
                        </label> (Delete even if in use, requires 'Delete acl')
                    </div>
                </div>
                <div class="row top-buffer">
                    <div class="col-md-12">
                        <button id="deletetargetbutton" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        require(['common'], function () {
            require(['pages/target/deletetarget'], function (methods) {
                methods.add_event_handler_deletetargetbutton();
            });
        });
    </script>
</div>