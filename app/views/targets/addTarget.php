<div class='container'>
    <div class='panel panel-default'>
        <ol class='panel-heading breadcrumb'>
            <li><a href='#'>Targets</a></li>
            <li class='active'>Add</a></li>
        </ol>
        <div class='panel-body'>
            <div class='row'>
                <div class='col-md-12'>
                    <div class='input-group'>
                        <span class='input-group-addon'><?php echo htmlspecialchars($data); ?></span>
                        <input class='form-control' type='text' id='iqninput' required autofocus>
                    </div>
                </div>
            </div>
        </div>
        <div class='panel-footer'>
            <div class='row'>
                <div class='col-md-12'>
                    <button id='addtargetbutton' class='btn btn-success'><span class='glyphicon glyphicon-plus'></span>
                        Add
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    require(['common'], function () {
        require(['pages/target/addTarget', 'domReady'], function (methods, domReady) {
            domReady(function () {
                methods.disable_special_chars();
                methods.focus_input();
                methods.remove_error();
                methods.add_event_handler_addtargetbutton();
            });
        });
    });
</script>