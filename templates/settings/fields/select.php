<?php
/**
 * @var string $name
 * @var array $options
 * */
?>
<select name="<?php echo esc_attr( $name ) ?>">
    <?php foreach ( $options as $option ) : ?>
        <option value="<?php echo $option['value'] ?>"><?php echo esc_html( $option['value'] ) ?></option>
    <?php endforeach; ?>
</select>
