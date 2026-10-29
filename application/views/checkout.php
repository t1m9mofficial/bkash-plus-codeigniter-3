<?php echo form_open(base_url('order_now')); ?>
<?php if (count($deliver_orders) > 0): ?>
<div class="col-lg-4" style="padding: 0">
    <div class="form-group">
        <select class="form-control" name="address_id" id="address_id" required="requied">
            <option value="">Select Delivery Address *</option>
            <?php
                $user_addresses = $this->db->get_where('address', array('user_id' => $this->session->userdata('user_id')))->result_array();
                foreach ($user_addresses as $user_address) :
            ?>
            <option value="<?php echo $user_address['address_id']; ?>"><?php echo $user_address['title'] . ' - ' . $user_address['content']; ?></option>
            <?php endforeach; ?>
        </select>
        <span class="help-block">
            <a href="javascript:;" onclick="showAjaxModal('<?php echo base_url('modal/frontend_modal/modal_add_new_address'); ?>');">
                <small>Add New Address</small>
            </a>
        </span>
    </div>
</div>
<?php
endif;
if (count($pickup_orders) > 0):
?>
<?php if (count($deliver_orders) > 0 && count($pickup_orders) > 0): ?><div class="col-lg-1"></div><?php endif; ?>
<div class="col-lg-4" style="padding: 0; margin-bottom: 10%">
    <div class="form-group">
        <select class="form-control" name="ppp_id" id="ppp_id" required="required">
            <option value="">Select Preferred Pickup Point *</option>
            <?php
            $ppp_info = $this->db->get_where('ppp', array('status' => 1))->result_array();
            foreach ($ppp_info as $ppp) :
            ?>
            <option value="<?php echo $ppp['ppp_id']; ?>"><?php echo $ppp['ppp_name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<?php endif; ?>
<input style="float:right" class="btn btn-primary" value="Place Order" type="submit">
<?php echo form_close(); ?>