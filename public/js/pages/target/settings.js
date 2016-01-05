define(['jquery', 'mylibs', 'sweetalert', 'once', 'touchspin'], function ($, mylibs, swal, once, touchspin) {
    return {
        info: function() {
            $('.btn.btn-info').popover({
                trigger : 'hover',
                placement : 'right',
                html: true
            }).on('show.bs.popover', function() {
                var $button = $(this);

                switch($button.closest('tr').attr('id')) {
                    case 'HeaderDigest':
                        $button.attr('data-content',
                            'Optional. If set to "CRC32C" and  the  initiator  is  configured ' +
                            'accordingly,  the  integrity  of  an iSCSI PDU\'s header segments ' +
                            'will be protected by a CRC32C checksum. The default  is  "None". ' +
                            'Note  that  header  digests  are  not supported during discovery sessions.');
                        break;
                    case 'DataDigest':
                        $button.attr('data-content',
                            'Optional. If set to "CRC32C" and  the  initiator  is  configured ' +
                            'accordingly,  the  integrity of an iSCSI PDU\'s data segment will ' +
                            'be protected by a CRC32C checksum. The default is  "None". ' +
                            'Note that data digests are not supported during discovery sessions.');
                        break;
                    case 'MaxConnections':
                        $button.attr('data-content',
                            'Optional.  The number of connections within a session. Has to be ' +
                            'set to "1" (in words: one), which is also the default since MC/S ' +
                            'is not supported.');
                        break;
                    case 'InitialR2T':
                        $button.attr('data-content',
                            'Optional.  If  set to "Yes" (default), the initiator has to wait ' +
                            'for the target to solicit SCSI data before sending  it.  Setting ' +
                            'it   to   "No"   allows   the  initiator  to  send  a  burst  of ' +
                            'FirstBurstLength bytes unsolicited right after and/or (depending' +
                            'on  the  setting  of  ImmediateData ) together with the command. ' +
                            'Thus setting it to "No" may improve performance.');
                        break;
                    case 'ImmediateData':
                        $button.attr('data-content',
                            'Optional. This allows the initiator to append  unsolicited  data ' +
                            'to  a command. To achieve better performance, this should be set ' +
                            'to "Yes". The default is "No".');
                        break;
                    case 'MaxRecvDataSegmentLength':
                        $button.attr('data-content',
                            'Optional. Sets the maximum data segment length  that  can  be ' +
                            'received. The value should be set to multiples of PAGE_SIZE. ' +
                            'Currently the maximum supported value is 64 * PAGE_SIZE, e.g. ' +
                            '262144 if PAGE_SIZE is 4kB. Configuring too large values may ' +
                            'lead to problems allocating sufficient memory, which in turn may ' +
                            'lead to SCSI commands timing out at the initiator host. The ' +
                            'default value is 8192.');
                        break;
                    case 'MaxXmitDataSegmentLength':
                        $button.attr('data-content',
                            'Optional. Sets the maximum data segment length that can be sent. ' +
                            'The value actually used is the minimum of ' +
                            'MaxXmitDataSegmentLength   and   the    MaxRecvDataSegmentLength ' +
                            'announced  by  the  initiator.  The  value  should  be  set to ' +
                            'multiples of PAGE_SIZE. Currently the maximum supported value is ' +
                            '64 * PAGE_SIZE, e.g. 262144 if PAGE_SIZE is 4kB. Configuring too ' +
                            'large values may lead to problems allocating sufficient  memory, ' +
                            'which  in  turn  may  lead  to  SCSI  commands timing out at the ' +
                            'initiator host. The default value is 8192.');
                        break;
                    case 'MaxBurstLength':
                        $button.attr('data-content',
                            'Optional. Sets the  maximum  amount  of  either  unsolicited  or ' +
                            'solicited  data  the  initiator  may send in a single burst. Any ' +
                            'amount of data exceeding this value must be explicitly solicited ' +
                            'by  the  target.  The  value  should  be  set  to multiples of ' +
                            'PAGE_SIZE. Configuring too large values  may  lead  to  problems ' +
                            'allocating  sufficient  memory,  which  in turn may lead to SCSI ' +
                            'commands timing out at the initiator host. The default value  is 262144.');
                        break;
                    case 'FirstBurstLength':
                        $button.attr('data-content',
                            'Optional.  Sets the amount of unsolicited data the initiator may ' +
                            'transmit in the first burst of a  transfer  either  with  and/or ' +
                            'right after the command, depending on the settings of InitialR2T ' +
                            'and  ImmediateData  value  should  be  set  to  multiples   of ' +
                            'PAGE_SIZE.  Configuring  too  large  values may lead to problems ' +
                            'allocating sufficient memory, which in turn  may  lead  to  SCSI ' +
                            'commands  timing out at the initiator host. The default value is 65536.');
                        break;
                    case 'DefaultTime2Wait':
                        $button.attr('data-content',
                            'Currently not implemented, but can  be  used  to  set  how  long ' +
                            'initiators  wait  before  logging  back in after a connection is ' +
                            'logged out or dropped.');
                        break;
                    case 'DefaultTime2Retain':
                        $button.attr('data-content',
                            'Currently we  only  support  0  which  means  sessions  are  not ' +
                            'retained after the last connection is logged out or dropped.');
                        break;
                    case 'MaxOutstandingR2T':
                        $button.attr('data-content',
                            'Optional.  Controls  the  maximum  number  of data transfers the ' +
                            'target may request at once, each of up to MaxBurstLength  bytes. ' +
                            'The default is 1.');
                        break;
                    case 'DataPDUInOrder':
                        $button.attr('data-content', 'Optional. Has to be set to "Yes" - which is also the default.');
                        break;
                    case 'DataSequenceInOrder':
                        $button.attr('data-content', 'Optional. Has to be set to "Yes" - which is also the default.');
                        break;
                    case 'ErrorRecoveryLevel':
                        $button.attr('data-content', 'Optional.  Has  to be set to "0" (in words: zero), which is also the default.');
                        break;
                    case 'NOPInterval':
                        $button.attr('data-content',
                            'Optional. If value is non-zero, the initiator will  be  "ping"ed ' +
                            'during phases of inactivity (i.e. no data transfers) every value ' +
                            'seconds  to  verify  the  connection  is  still  alive.  If  the ' +
                            'initiator  fails  to  respond  within  NOPTimeout  seconds,  the ' +
                            'connection will be closed.');
                        break;
                    case 'NOPTimeout':
                        $button.attr('data-content',
                            'Optional. If a non-zero  NOPInterval  is  used  to  periodically ' +
                            '"ping"  the  initiator during phases of inactivity (i.e. no data ' +
                            'transfers), the initiator must  respond  within  value  seconds, ' +
                            'otherwise the connection will be closed. If value is set to zero ' +
                            'or if it exceeds NOPInterval , it will be set to NOPInterval.');
                        break;
                    case 'Wthreads':
                        $button.attr('data-content',
                            'Optional. The iSCSI target employs several  threads  to  perform ' +
                            'the  actual  block I/O to the device. Depending on your hardware ' +
                            'and your (expected) workload, the number of these threads may be ' +
                            'carefully  adjusted. The default value of 8 should be sufficient ' +
                            'for most purposes.');
                        break;
                    case 'QueuedCommands':
                        $button.attr('data-content',
                            'Optional.  This  parameter  defines  a  window  of  commands  an ' +
                            'initiator  may  send  and  that  will be buffered by the target. ' +
                            'Depending on your hardware and  your  (expected)  workload,  the ' +
                            'value  may be carefully adjusted. The default value of 32 should ' +
                            'be sufficient for most purposes.');
                        break;
                }
            });
        },
        add_event_handler_settings_table_checkbox: function () {
            var value = $('.value');

            value.TouchSpin({
                verticalbuttons: true,
                verticalupclass: 'glyphicon glyphicon-plus',
                verticaldownclass: 'glyphicon glyphicon-minus',
                max: 10000000
            });

            value.once('input', function () {
                var $this = $(this);
                var $this_row = $this.closest('tr');
                var oldvalue = $this_row.find('.default_value_before_change').val();
                var newvalue = $this.val();
                var settingstablecheckbox = $this_row.find('.settingstablecheckbox');

                if (oldvalue !== newvalue) {
                    settingstablecheckbox.prop('checked', true)
                } else {
                    settingstablecheckbox.prop('checked', false)
                }
            });
        },
        add_event_handler_save_value: function () {
            $('.saveValueButton').once('click', function () {
                var $this = $(this),
                    this_row = $this.closest('tr'),
                    newvalue,
                    type,
                    oldvalue = this_row.find('.default_value_before_change').val();

                newvalue = this_row.find('.value').val();

                // If value is not defined
                if (typeof newvalue === 'undefined') {
                    newvalue = this_row.find('.optionselector option:selected').text();
                    type = 'select';
                } else {
                    type = 'input';
                }

                if (oldvalue === newvalue) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: 'No changes made!'
                    });
                } else if (newvalue === '') {
                    this_row.find('.value').parents('td').addClass('has-error')
                } else {
                    $this.button('loading');
                    $.ajax({
                        url: require.toUrl('../targets/configure/' + $('#targetSelect').find('option:selected').val() + '/settings'),
                        beforeSend: mylibs.checkAjaxRunning(),
                        data: {
                            'option': this_row.find('.option').text(),
                            'oldvalue': oldvalue,
                            'newvalue': newvalue,
                            'iqn': $('#target_selector').find('option:selected').val(),
                            'type': type
                        },
                        dataType: 'json',
                        type: 'post',
                        success: function (data) {
                            if (data['code'] === 0) {
                                swal({
                                    title: 'Success',
                                    type: 'success',
                                    text: data['message']
                                }, function () {
                                    newvalue = this_row.find('.value').val();

                                    // If value is not defined
                                    if (typeof newvalue === 'undefined') {
                                        newvalue = this_row.find('.optionselector option:selected').text();
                                    }

                                    this_row.find('.default_value_before_change').val(newvalue);

                                    $this.button('reset');
                                });
                            } else {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: data['message']
                                }, function() {
                                    $this.button('reset');
                                });
                            }
                        },
                        error: function () {
                            swal({
                                title: 'Error',
                                type: 'error',
                                text: 'Something went wrong while submitting!'
                            }, function() {
                                $this.button('reset');
                            });
                        }
                    });
                }
            });
        },
        remove_error: function () {
            /* remove error if field is clicked */
            $('.value').click(function () {
                $(this).parents('td').removeClass('has-error');
            });
        }
    }
});