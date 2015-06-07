define(['jquery', 'mylibs', 'sweetalert'], function($, mylibs, swal) {
    $(function() {
        $(document).on('click', '#adduserrowbutton', function() {
            $('#addusertablebody').append(
                '<tr  class="newrow">' +
                '<td>' +
                '<input class="username" type="text" name="value" placeholder="Username">' +
                '<span class="label label-success bestaetigung">Success</span>' +
                '</td>' +
                '<td>' +
                '<input class="password" type="text" name="value" placeholder="Password">' +
                '</td>' +
                '<td>' +
                '<input type="checkbox" name="zutat" value="salami" id="check1">' +
                '</td>' +
                '<td><a href="#" class="deleteuserrow"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>' +
                '<td><a href="#" class="saveuserrow"><span class="glyphicon glyphicon-save" aria-hidden="true"></span></a></td>' +
                '</tr>'
            );
        });
    });
});