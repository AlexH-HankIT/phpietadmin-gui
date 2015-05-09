<!--

Liste mit allen targets anzeigen (targets ohne luns nicht anzeigen!)
user wählt target aus
liste mit luns zu target per ajax nachladen (nur luns ohne session anzeigen!)
bestätigung um lun zu löschen

lun live mit ietadm löschen
wenn erfolgreich im config file entfernen (delete_line_in_file $string = pathtoblockdevice)

ansonsten fehler anzeigen

-->

<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Delete lun</li>
    </ol>
    <div id="deleteluncontent">
        <div class="jumbotron">
            <p>
                <select id="deleteluniqnselection" class="form-control">
                    <option value="default" id="default">Select target</option>
                    <?php foreach ($data as $value) { ?>
                        <option value="<?php echo $value ?>"><?php echo $value ?></option>
                    <?php } ?>
                </select>
            </p>

            <!-- list with luns will be inserted here using ajax -->
            <div id="deletelunluns"></div>
        </div>
    </div>
</div>