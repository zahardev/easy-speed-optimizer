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
<textarea id="<?php echo esc_attr( $id ) ?>" name="<?php echo esc_attr( $id ) ?>" rows="<?php echo esc_attr( $rows ) ?>" cols="<?php echo esc_attr( $cols ) ?>">
<?php echo esc_textarea( $value ) ?>
</textarea>
<br>
<label for="<?php echo esc_attr( $id ) ?>"><?php echo esc_html( $description ) ?></label>
