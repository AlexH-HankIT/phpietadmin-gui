<div class="container">
        <table class="table white-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Repeat</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>admin</td>
                    <td><input class="password1" disabled type="password" value="           "><span class="label label-success bestaetigung">Success</span></td>
                    <td><input class="password2" disabled type="password" value="           "><span class="label label-success bestaetigung">Success</span></td>
                    <td><a href='#'><span id="editpassword" style="font-size: 15px" class="glyphicon glyphicon-pencil"></a></td>
                    <td><a id="savepassworda" hidden href='#'></span><span id="savepassword" style="font-size: 15px" class="glyphicon glyphicon-ok"></span></a></td>
                </tr>
            </tbody>
        </table>
</div>

<script>
    require(['common'],function() {
        require(['pages/configuser']);
    });
</script>