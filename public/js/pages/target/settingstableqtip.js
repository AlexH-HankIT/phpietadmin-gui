define(['jquery', 'qtip'], function($, qtip) {
    var methods;

    return methods = {
        add_qtip: function() {
            $(function() {
                $('#HeaderDigest').qtip({
                    content: {
                        text: 'Optional. If set to "CRC32C" and  the  initiator  is  configured ' +
                        'accordingly,  the  integrity  of  an iSCSI PDU\'s header segments ' +
                        'will be protected by a CRC32C checksum. The default  is  "None". ' +
                        'Note  that  header  digests  are  not supported during discovery sessions.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#DataDigest').qtip({
                    content: {
                        text: 'Optional. If set to "CRC32C" and  the  initiator  is  configured ' +
                        'accordingly,  the  integrity of an iSCSI PDU\'s data segment will ' +
                        'be protected by a CRC32C checksum. The default is  "None". ' +
                        'Note that data digests are not supported during discovery sessions.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#MaxConnections').qtip({
                    content: {
                        text:  'Optional.  The number of connections within a session. Has to be ' +
                        'set to "1" (in words: one), which is also the default since MC/S ' +
                        'is not supported.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#InitialR2T').qtip({
                    content: {
                        text: 'Optional.  If  set to "Yes" (default), the initiator has to wait ' +
                        'for the target to solicit SCSI data before sending  it.  Setting ' +
                        'it   to   "No"   allows   the  initiator  to  send  a  burst  of ' +
                        'FirstBurstLength bytes unsolicited right after and/or (depending' +
                        'on  the  setting  of  ImmediateData ) together with the command. ' +
                        'Thus setting it to "No" may improve performance.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#ImmediateData').qtip({
                    content: {
                        text: 'Optional. This allows the initiator to append  unsolicited  data ' +
                        'to  a command. To achieve better performance, this should be set ' +
                        'to "Yes". The default is "No".'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#MaxRecvDataSegmentLength').qtip({
                    content: {
                        text: 'Optional. Sets the maximum data segment length  that  can  be ' +
                        'received. The value should be set to multiples of PAGE_SIZE. ' +
                        'Currently the maximum supported value is 64 * PAGE_SIZE, e.g. ' +
                        '262144 if PAGE_SIZE is 4kB. Configuring too large values may ' +
                        'lead to problems allocating sufficient memory, which in turn may ' +
                        'lead to SCSI commands timing out at the initiator host. The ' +
                        'default value is 8192.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#MaxXmitDataSegmentLength').qtip({
                    content: {
                        text: 'Optional. Sets the maximum data segment length that can be sent. ' +
                        'The value actually used is the minimum of ' +
                        'MaxXmitDataSegmentLength   and   the    MaxRecvDataSegmentLength ' +
                        'announced  by  the  initiator.  The  value  should  be  set to ' +
                        'multiples of PAGE_SIZE. Currently the maximum supported value is ' +
                        '64 * PAGE_SIZE, e.g. 262144 if PAGE_SIZE is 4kB. Configuring too ' +
                        'large values may lead to problems allocating sufficient  memory, ' +
                        'which  in  turn  may  lead  to  SCSI  commands timing out at the ' +
                        'initiator host. The default value is 8192. '
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#MaxBurstLength').qtip({
                    content: {
                        text: 'Optional. Sets the  maximum  amount  of  either  unsolicited  or ' +
                        'solicited  data  the  initiator  may send in a single burst. Any ' +
                        'amount of data exceeding this value must be explicitly solicited ' +
                        'by  the  target.  The  value  should  be  set  to multiples of ' +
                        'PAGE_SIZE. Configuring too large values  may  lead  to  problems ' +
                        'allocating  sufficient  memory,  which  in turn may lead to SCSI ' +
                        'commands timing out at the initiator host. The default value  is 262144.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#FirstBurstLength').qtip({
                    content: {
                        text: 'Optional.  Sets the amount of unsolicited data the initiator may ' +
                        'transmit in the first burst of a  transfer  either  with  and/or ' +
                        'right after the command, depending on the settings of InitialR2T ' +
                        'and  ImmediateData  value  should  be  set  to  multiples   of ' +
                        'PAGE_SIZE.  Configuring  too  large  values may lead to problems ' +
                        'allocating sufficient memory, which in turn  may  lead  to  SCSI ' +
                        'commands  timing out at the initiator host. The default value is 65536.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#DefaultTime2Wait').qtip({
                    content: {
                        text: 'Currently not implemented, but can  be  used  to  set  how  long ' +
                        'initiators  wait  before  logging  back in after a connection is ' +
                        'logged out or dropped.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#DefaultTime2Retain').qtip({
                    content: {
                        text: 'Currently we  only  support  0  which  means  sessions  are  not ' +
                        'retained after the last connection is logged out or dropped.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#MaxOutstandingR2T').qtip({
                    content: {
                        text: 'Optional.  Controls  the  maximum  number  of data transfers the ' +
                        'target may request at once, each of up to MaxBurstLength  bytes. ' +
                        'The default is 1.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#DataPDUInOrder').qtip({
                    content: {
                        text: 'Optional. Has to be set to "Yes" - which is also the default.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#DataSequenceInOrder').qtip({
                    content: {
                        text: 'Optional. Has to be set to "Yes" - which is also the default.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#ErrorRecoveryLevel').qtip({
                    content: {
                        text: 'Optional.  Has  to be set to "0" (in words: zero), which is also the default.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#NOPInterval').qtip({
                    content: {
                        text: 'Optional. If value is non-zero, the initiator will  be  "ping"ed ' +
                        'during phases of inactivity (i.e. no data transfers) every value ' +
                        'seconds  to  verify  the  connection  is  still  alive.  If  the ' +
                        'initiator  fails  to  respond  within  NOPTimeout  seconds,  the ' +
                        'connection will be closed.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#NOPTimeout').qtip({
                    content: {
                        text: 'Optional. If a non-zero  NOPInterval  is  used  to  periodically ' +
                        '"ping"  the  initiator during phases of inactivity (i.e. no data ' +
                        'transfers), the initiator must  respond  within  value  seconds, ' +
                        'otherwise the connection will be closed. If value is set to zero ' +
                        'or if it exceeds NOPInterval , it will be set to NOPInterval.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#Wthreads').qtip({
                    content: {
                        text: 'Optional. The iSCSI target employs several  threads  to  perform ' +
                        'the  actual  block I/O to the device. Depending on your hardware ' +
                        'and your (expected) workload, the number of these threads may be ' +
                        'carefully  adjusted. The default value of 8 should be sufficient ' +
                        'for most purposes.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
                $('#QueuedCommands').qtip({
                    content: {
                        text: 'Optional.  This  parameter  defines  a  window  of  commands  an ' +
                        'initiator  may  send  and  that  will be buffered by the target. ' +
                        'Depending on your hardware and  your  (expected)  workload,  the ' +
                        'value  may be carefully adjusted. The default value of 32 should ' +
                        'be sufficient for most purposes.'
                    },
                    style: {
                        classes: 'qtip-youtube'
                    }
                });
            });
        }
    };
});