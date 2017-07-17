<?php
/**
 * @package   WooCommerce Stock Manager
 * @author    Vladislav Musílek
 * @license   GPL-2.0+
 * @link      http:/toret.cz
 * @copyright 2015 Toret.cz
 */

$stock = $this->stock();

function stockautoUTF($s){
    if (preg_match('#[\x80-\x{1FF}\x{2000}-\x{3FFF}]#u', $s))
        return $s;

    if (preg_match('#[\x7F-\x9F\xBC]#', $s))
        return iconv('WINDOWS-1250', 'UTF-8', $s);

    return iconv('ISO-8859-2', 'UTF-8', $s);
}


?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>




<div class="t-col-6">
  <div class="toret-box box-info">
    <div class="box-header">
      <h3 class="box-title"><?php _e('Import','stock-manager'); ?></h3>
    </div>
  <div class="box-body">
    <h4><?php _e('You can upload csv file, with your stock data. ','stock-manager'); ?></h4>
    <p><?php _e('CSV file must be in this format, or you can export file with exist data and edit them. ','stock-manager'); ?></p>
    <p><?php _e('If you have a lot of products and export/import not working, increase memory limit.. ','stock-manager'); ?></p>
    <h3><?php _e('File format. ','stock-manager'); ?></h3>
    <table class="table-bordered">
      <tr>
        <td><?php _e('SKU','stock-manager'); ?></td>
        <td><?php _e('Product name','stock-manager'); ?></td>
        <td><?php _e('Manage stock','stock-manager'); ?></td>
        <td><?php _e('Stock status','stock-manager'); ?></td>
        <td><?php _e('Backorders','stock-manager'); ?></td>
        <td><?php _e('Stock','stock-manager'); ?></td>
        <td><?php _e('Product type','stock-manager'); ?></td>
        <td><?php _e('Parent SKU','stock-manager'); ?></td>
      </tr>
      <tr>
        <td><?php _e('111111','stock-manager'); ?></td>
        <td><?php _e('T-shirt','stock-manager'); ?></td>
        <td><?php _e('yes','stock-manager'); ?></td>
        <td><?php _e('instock','stock-manager'); ?></td>
        <td><?php _e('yes','stock-manager'); ?></td>
        <td><?php _e('10','stock-manager'); ?></td>
        <td><?php _e('simple','stock-manager'); ?></td>
        <td></td>
      </tr>
    </table>

    <ul>
      <li><strong><?php _e('SKU','stock-manager'); ?></strong> <?php _e('product unique identificator, required. Neccessary for import and export.','stock-manager'); ?></li>
      <li><strong><?php _e('Manage stock','stock-manager'); ?></strong> <?php _e('values: "yes", "notify", "no". If is empty "no" will be save.','stock-manager'); ?></li>
      <li><strong><?php _e('Stock status','stock-manager'); ?></strong> <?php _e('values: "instock", "outofstock". If is empty "outofstock" will be save.','stock-manager'); ?></li>
      <li><strong><?php _e('Backorders','stock-manager'); ?></strong> <?php _e('values: "yes", "notify", "no". If is empty "no" will be save.','stock-manager'); ?></li>
      <li><strong><?php _e('Stock','stock-manager'); ?></strong> <?php _e('quantity value. If is empty, 0 will be save.','stock-manager'); ?></li>
    </ul>


    <form method="post" action="" class="setting-form" enctype="multipart/form-data">
        <table class="table-bordered">
          <tr>
            <th><?php _e('Upload csv file', 'stock-manager'); ?></th>
            <td>
              <input type="file" name="uploadFile">
            </td>
          </tr>

        </table>
      <input type="hidden" name="upload" value="ok" />
      <input type="submit" class="btn btn-info" value="<?php _e('Upload', 'stock-manager'); ?>" />
    </form>
    <?php
    if(isset($_POST['upload'])){

      $target_dir = STOCKDIR.'admin/views/upload/';
      $target_dir = $target_dir . basename( $_FILES["uploadFile"]["name"]);
      $uploadOk   = true;

      if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $target_dir)) {

          echo __('The file '. basename( $_FILES['uploadFile']['name']). ' has been uploaded.','csv-category');

          $row = 1;
          if (($handle = fopen($target_dir, "r")) !== FALSE) {

              while (($data = fgetcsv($handle, 1000, WC_STOCK_CSV_SEPERATOR )) !== FALSE) {
                $num = count($data);

                  $product_id   = stockautoUTF($data[0]);
                  $sku          = stockautoUTF($data[1]);
                  $manage_stock = stockautoUTF($data[3]);
                  $stock_status = stockautoUTF($data[4]);
                  $backorders   = stockautoUTF($data[5]);
                  $stock        = stockautoUTF($data[6]);



                      if($row != 1){


                        if(!empty($product_id)){

                          update_post_meta($product_id, '_manage_stock', $manage_stock);
                          update_post_meta($product_id, '_stock_status', $stock_status);
                          update_post_meta($product_id, '_backorders', $backorders);
                          update_post_meta($product_id, '_stock', $stock);

                          echo '<p>'.__('Product with ID: '.$product_id.' was updated.','stock-manager').'</p>';

                        }
                      }
              $row++;

          }
          fclose($handle);
    }

  }else{
    echo '<p>'.__('Sorry, there was an error uploading your file.','stock-manager').'</p>';
  }

}
?>
  </div>
</div>
</div>



<div class="t-col-6">
  <div class="toret-box box-info">
    <div class="box-header">
      <h3 class="box-title"><?php _e('Export','stock-manager'); ?></h3>
    </div>
  <div class="box-body">
    <h4><?php _e('You can download csv file, with your stock data. ','stock-manager'); ?></h4>
    <p><a href="<?php echo admin_url().'admin.php?page=stock-manager-import-export&action=export'; ?>" class="btn btn-danger"><?php _e('Create export file','stock-manager'); ?></a></p>
  </div>
</div>
</div>


</div>
