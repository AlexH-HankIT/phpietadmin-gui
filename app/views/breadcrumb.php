<?php $last = end(array_keys($data))?>
<div class="workspace">
    <div class="container">
        <ol class="breadcrumb">
            <?php foreach ($data as $key => $value) { ?>
                <?php if ($key === $last) { ?>
                    <li class="active"><?php echo $data[$last]['text'] ?></li>
                <?php } else { ?>
                    <li><a href="<?php echo $data[$key]['href'] ?>"><?php echo $data[$key]['text'] ?></a></li>
                <?php } ?>
            <?php } ?>
        </ol>
    </div>
</div>