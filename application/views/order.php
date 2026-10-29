<?php 
    $user_order = $this->db->get_where('user_order', array('user_order_id' => $user_order_id)); 
    $bkash_payment = $this->db->get_where('bkash_payment', array('user_order_id' => $user_order_id));
?>

<!-- Ordered By -->
<div class="row" style="margin-bottom: 10%">
    <div class="col-lg-12" align="center">
        <div class="col-lg-3" style="padding-left: 0">
            <dl>
                <dt class="th">Name</dt>
                <dd><?php echo $user_order->row()->name; ?></dd>
            </dl>
        </div>
        <div class="col-lg-3" style="padding-left: 0">
            <dl>
                <dt class="th">Email</dt>
                <dd><?php echo $user_order->row()->email; ?></dd>
            </dl>
        </div>
        <div class="col-lg-3" style="padding-left: 0">
            <dl>
                <dt class="th">Phone</dt>
                <dd><?php echo $user_order->row()->phone; ?></dd>
            </dl>
        </div>
        <div class="col-lg-3" style="padding-left: 0">
            <dl>
                <dt class="th">Order Date</dt>
                <dd><?php echo date('d M, Y', $user_order->row()->ordered_on); ?></dd>
            </dl>
        </div>     
    </div>
</div>

<div class="row">
    <div class="display center">
        <div class="col-lg-4">
            <div class="panel panel-success home-panel">
                <div style="background-color: #FF0000" class="panel-heading">
                    <h5 style="color:black" class="panel-title">Order</h5>
                </div>
                <ul class="list-group">
                    <li class="list-group-item">#<?php echo $user_order->row()->invoice_num; ?></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel panel-warning home-panel">
                <div style="background-color: #f1c40f" class="panel-heading">
                    <h5 style="color:black" class="panel-title">Payment ID</h5>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><?php echo $bkash_payment->num_rows() > 0 ? $bkash_payment->row()->payment_id : '-'; ?></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel panel-danger home-panel">
                <div style="background-color: #165E00" class="panel-heading">
                    <h5 style="color:black" class="panel-title">Transaction ID</h5>
                </div>
                <ul class="list-group">
                    <li class="list-group-item"><?php echo $bkash_payment->num_rows() > 0 ? $bkash_payment->row()->trx_id : '-'; ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>