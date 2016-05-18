<?php

?>
<?php foreach ($treatments as $brand_name => $brand_params) : ?>
    <tr>
            <td><?php echo $brand_name ?></td>
            <td >
                <?php foreach ($brand_params as $param) : ?>
                    <?php echo $param['param']; ?><br/>
                <?php endforeach; ?>
            </td>
            <td>
                <?php foreach ($brand_params as $param) : ?>
                    <?php echo $param['price']; ?><br/>
                <?php endforeach; ?>
            </td>
    </tr>
<?php endforeach; ?>
