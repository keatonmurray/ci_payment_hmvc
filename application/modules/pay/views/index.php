<?php echo $layout; ?>

<div id="payment-form">
    <h4 id="form-title" class="text-center mb-4">Pay Now</h4>
    <div class="d-flex justify-content-center">
        <div class="w-100">
            <form action="<?php echo base_url('index.php/pay/store'); ?>" method="POST">
                <input type="hidden" name="amount" value="8" readonly>
                <div class="form-floating mb-3">
                    <input type="text" name="first_name" class="form-control" id="first-name" placeholder="John">
                    <label for="first-name">First Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="last_name" class="form-control" id="last-name" placeholder="Doe">
                    <label for="last-name">Last Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="street_name" class="form-control" id="street-name" placeholder="123 Main St">
                    <label for="street-name">Street Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="city" class="form-control" id="city" placeholder="City">
                    <label for="city">City</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="state" class="form-control" id="state" placeholder="State">
                    <label for="state">State</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="zip_code" class="form-control" id="zip-code" placeholder="ZIP Code">
                    <label for="zip-code">ZIP Code</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="country" class="form-control" id="country" placeholder="Country">
                    <label for="country">Country</label>
                </div>
                <button type="submit" class="btn custom-button btn-primary">Proceed to Checkout</button>
            </form>
        </div>
    </div>
</div>

<?php echo $footer; ?>
