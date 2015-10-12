<div id='configure_target_body'>
    <div class="container">
        <?php if (isset($data['session'])) { ?>
            <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Warning - Target has sessions!</h3></div>
        <?php } ?>

        <?php if (isset($data['lun'])) { ?>
            <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Warning - Target has luns!</h3></div>
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
            require(['pages/target/deletetarget', 'domReady'], function (methods, domReady) {
                domReady(function () {
                    methods.add_event_handler_deletetargetbutton();
                });
            });
        });
    </script>
</div>