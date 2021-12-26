<?php
/**
 * @var string $id
 * @var string $value
 * @var string $title
 * @var string $description
 * @var string $rows
 * @var string $cols
 * */
?>
<textarea id="<?php echo $id ?>" name="<?php echo $id ?>" rows="<?php echo $rows ?>" cols="<?php echo $cols ?>">
<?php echo esc_attr( $value ) ?>
</textarea>
<br>
<label for="<?php echo $id ?>"><?php echo $description ?></label>
