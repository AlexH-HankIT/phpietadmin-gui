<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Users</li>
    </ol>
</div>


<div class="container">
    <table id="addusertable" class="table table-striped searchabletable">
        <thead>
            <tr>
                <th>Username</th>
                <th>Password</th>
                <th>Generate password</th>
                <th><a href="#" id="adduserrowbutton"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></th>
            </tr>
        </thead>

        <tbody id="addusertablebody">

        </tbody>
    </table>
</div>

<script>
    require(['common'],function() {
        require(['pages/usertable']);
    });
</script>