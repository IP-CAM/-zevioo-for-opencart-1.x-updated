<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
  	<div class="heading">
      <h1><?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
  	<div class="content">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $text_username; ?></td>
            <td><input type="text" name="module_zevioo_username" value="<?php echo $zevioo_username; ?>" placeholder="<?php echo $text_username; ?>" id="entry-username"/>
              <?php if ($error_username) { ?>
              <span class="error"><?php echo $error_username; ?></span>
              <?php } ?></td>
          </tr>
		  <tr>
            <td><span class="required">*</span> <?php echo $text_password; ?></td>
            <td><input type="text" name="module_zevioo_password" value="<?php echo $zevioo_password; ?>" placeholder="<?php echo $text_password; ?>" id="entry-password"/>
              <?php if ($error_password) { ?>
              <span class="error"><?php echo $error_password; ?></span>
              <?php } ?></td>
          </tr>
		  <tr>
            <td><?php echo $text_canceled_status; ?></td>
            <td><select name="module_zevioo_canceled_status_id">\
					<?php foreach($order_statuses as $order_status) { ?>
                    <?php if($order_status['order_status_id'] == $module_zevioo_canceled_status_id){?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php }
					}
					?>
                  </select></td>
          </tr>
		  <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="module_zevioo_status">
                <?php if ($module_zevioo_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
		</table>
		</form>
	</div>
  </div>
</div>
<?php echo $footer; ?>