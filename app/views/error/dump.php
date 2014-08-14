<div>
    <p>Message: <?php echo $e->getMessage(); ?></p>

    <p>Code: <?php echo $e->getCode(); ?></p>

    <p>Line: <?php echo $e->getLine(); ?></p>
</div>

<?php foreach ($e->getTrace() as $key => $value): ?>
    <p><?php echo $key; ?>:
    <?php foreach ($value as $k => $v): ?>
        <p><?php echo $k; ?> : <?php var_dump($v); ?></p>
    <?php endforeach ?>
<?php endforeach ?>
