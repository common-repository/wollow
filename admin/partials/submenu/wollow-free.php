<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.1
 *
 * @package    WollowPro
 * @subpackage WollowPro/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="container-fluid">

    <form method="POST" action="options.php">
        <div class="d-flex heading mt-3">
            <div class="align-self-center">
                <div class="icon-placeholder"></div>
            </div>
            <div class="align-self-center">
                <h5 class="px-2 mb-0"><?php echo __('Wollow Settings', 'wollow'); ?></h5>
            </div>
            <div class="ms-auto align-self-center">
                <button type="submit" id="inputWollow" class="simpan"><?php echo __('Save Settings', 'wollow'); ?></button>
            </div>
        </div>

        <!-- cta -->
        <div class="d-flex flex-row cta-upgrade">
            <div class="close close-custom align-self-end" id="cta-close-up">
                <img src="<?php echo WOLLOW_URL . 'admin/images/close.png'; ?>" alt="">
            </div>

            <div class="flex-grow-1">
                <div class="content-header align-self-center">
                    <?php echo __('Upgrade to Premium Now and <br> Get the Pro Features!', 'wollow'); ?>
                </div>
            </div>
            <div class="align-self-center align-self-b" data-bs-toggle="modal" data-bs-target="#visit-wollow">
                <button class="" type="button" id="custom-premium"><?php echo __('Upgrade to Premium', 'wollow'); ?></button>
            </div>
            <div class="close close-custom" id="cta-close-down">
                <img src="<?php echo WOLLOW_URL . 'admin/images/close.png'; ?>" alt="">
            </div>
        </div>
        <!-- end cta -->

        <!-- tab follow up -->
        <div class="d-flex  tab-follow-up">
            <div class="flex-grow-1">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link  box-button" aria-current="page" href="#">1</a>
                    </li>
                    <button type="button" class="box-nav mb-8" data-bs-toggle="modal" data-bs-target="#visit-wollow">
                        +
                    </button>

                </ul>
            </div>
        </div>
        <!-- end tab follow up -->

        <!-- text area -->
        <div class="w-60 mt-3 float-start">
            <?php
            settings_fields('submenuWollow');
            do_settings_sections('submenuWollow');
            ?>
            <div class="tab-content " id="addTab">
                <div class="tab-pane active" id="1">
                    <textarea id="summernote-1" class="template-wa" name="wollow1" aria-hidden="false">
                            <?php echo $result ?>
                        </textarea>
                </div>
            </div>
        </div>
        <!-- end text area -->
    </form>

    <div class="float-start mx-3 mt-3 roboto lh-base">
        <h4>Shortcode</h4>
        <div>{site_name} : <?php echo __('Site Name', 'wollow'); ?></div>
        <div>{order_id} : <?php echo __('Order Id', 'wollow'); ?></div>
        <div>{customer_name} : <?php echo __('Customer Name', 'wollow'); ?></div>
        <div>{product_name} : <?php echo __('Product Name', 'wollow'); ?></div>
        <div>{order_date} : <?php echo __('Product Name', 'wollow'); ?></div>
        <div>{customer_email} : <?php echo __('Customer Email', 'wollow'); ?></div>
        <div>{order_details} : <?php echo __('Order Details', 'wollow'); ?></div>
        <div>{billing_total} : <?php echo __('Billing Total', 'wollow'); ?></div>
    </div>
    <div style="clear:both;"></div>
</div>

<!-- upgrade premium -->
<div class="modal fade" id="visit-wollow" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="d-flex">
                <div class="flex-grow-1">
                    <h2 class="lh-base text-white roboto px-2 py-2">
                        <?php echo __('Upgrade to Premium Now <br> and Get the Pro <br> Features!', 'wollow'); ?>
                    </h2>
                    <p class="fs-6 lh-sm roboto mt-3 my-4 py-2 px-2">
                        <?php echo __('Follow up your customers up to 7 times and export unlimited customers\' phone numbers!', 'wollow'); ?>
                    </p>
                    <p class="px-2">
                        <button class="box-nav button-popup mb-2 px-2">
                            <a href="https://alus.io/downloads/wollow/" target="_blank" class="tkp"><?php echo __('Upgrade to Premium', 'wollow'); ?></a>
                        </button>
                    </p>
                </div>
                <div class="cursor-pointer" data-bs-dismiss="modal" aria-label="Close">
                    <img src="<?php echo WOLLOW_URL . 'admin/images/close.png'; ?>" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<!-- upgrade premium -->