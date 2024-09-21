<?php echo $layout; ?>

<div id="payment-form">
    <div class="d-flex justify-content-center">
        <div class="w-100">
            <form id="form-submit" action="<?php echo base_url('pay/store'); ?>" method="POST">
                <div class="row py-4">
                    <div class="col-12 col-md-6">
                        <div class="shadow-lg p-4 border-rounded">
                            <h4 id="form-title" class="text-start mb-4">Personal Information</h4>
                            <input type="hidden" name="amount" value="98" readonly>
                            <div class="form-floating mb-3">
                                <input type="text" name="first_name" class="form-control" id="first-name" placeholder="John">
                                <label for="first-name">First Name</label>
                                <div class="error-message invalid-feedback"></div> 
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" name="last_name" class="form-control" id="last-name" placeholder="Doe">
                                <label for="last-name">Last Name</label>
                                <div class="error-message invalid-feedback"></div> 
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" name="street_name" class="form-control" id="street-name" placeholder="123 Main St">
                                <label for="street-name">Street Name</label>
                                <div class="error-message invalid-feedback"></div> 
                            </div>
                            <div class="d-flex gap-3">
                                <div class="form-floating mb-3 flex-grow-1">
                                    <input type="text" name="city" class="form-control" id="city" placeholder="City">
                                    <label for="city">City</label>
                                    <div class="error-message invalid-feedback"></div> 
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" name="state" class="form-control" id="state" placeholder="State" style="width: 80px;">
                                    <label for="state">State</label>
                                    <div class="error-message invalid-feedback"></div> 
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" name="zip_code" class="form-control" id="zip-code" placeholder="ZIP Code" style="width: 120px;">
                                    <label for="zip-code">ZIP Code</label>
                                    <div class="error-message invalid-feedback"></div> 
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" name="country" class="form-control" id="country" placeholder="Country">
                                <label for="country">Country</label>
                                <div class="error-message invalid-feedback"></div> 
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="list-group shadow-lg p-4 border-rounded">
                            <h4 id="form-title" class="text-start mb-4">Your Cart</h4>
                            <div class="d-flex gap-2 w-100 justify-content-between p-2">
                                <img src="<?php echo base_url('assets/images/product1.webp'); ?>" alt="Product Image">
                                <div class="ms-2">
                                    <h6 class="mb-0">LuxeGlow LED Desk Lamp</h6>
                                    <p id="product-description" class="mb-0 opacity-75">Quantity: 1</p>
                                </div>
                                <h5 class="text-nowrap">$18.00</small>
                            </div>
                            <div class="d-flex gap-2 w-100 justify-content-between p-2">
                            <img src="<?php echo base_url('assets/images/product2.webp'); ?>" alt="Product Image">
                                <div class="ms-2">
                                    <h6 class="mb-0">ZenVibe Aromatherapy Diffuser</h6>
                                    <p id="product-description" class="mb-0 opacity-75">Quantity: 1</p>
                                </div>
                                <h5 class="text-nowrap">$80.00</small>
                            </div>
                            <div class="d-flex gap-2 w-100 justify-content-between p-2 mt-4">
                                <div>
                                    <h5 class="mb-0">Subtotal:</h6>
                                </div>
                                <h5 class="text-nowrap">$98.00</small>
                            </div>
                            <button type="submit" class="btn custom-button mt-3">
                                <i class="fa-brands fa-paypal me-2"></i>
                                Proceed to Checkout
                            </button>
                            <button type="button" class="btn custom-button mt-2">
                                <i class="fa-solid fa-bag-shopping me-2"></i>
                                Go to Shopping Page
                            </button>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>

<?php echo $footer; ?>
