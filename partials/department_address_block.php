<?php
    // If this file is called directly, abort.
    if ( ! defined( 'WPINC' ) ) {
        die;
    }
?>
<div class="department-address-block">
    <table class="form-table">
        <tbody>
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="address">
                        <?php _e('Address','departments');?>
                        <span class="description">
                        (<?php _e('required','departments'); ?>)
                        </span>
                    </label>
                </th>
                <td>
                    <input name="address" type="text" id="address" value="<?php echo $address;?>" aria-required="true" maxlength="300">
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row">
                    <label for="coordinates">
                         <?php _e('Coordinates','departments'); ?>
                    </label>
                </th>
                <td>
                    <input disabled="disabled" type="text" id="coordinates_mutted" value='<?php echo $coordinates;?>'>
                    <input name="coordinates" type="hidden" id="coordinates" value='<?php echo $coordinates;?>'>
                </td>
            </tr>
        </tbody>
    </table>
    <div id="map"></div>
</div>