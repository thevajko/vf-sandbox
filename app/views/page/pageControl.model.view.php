<?php

/** @var \app\models\DummyModel $users */
$users =  $data->users;
?>
<h2>Some random users</h2>
<ul>
<?php foreach ($users->data as $item) { ?>
    <li><?php echo $item[0]." ".$item[1] ?></li>
<?php } ?>
</ul>
<h2>Output of print_r function</h2>
<pre>
    <?php print_r($users->data) ?>
</pre>

