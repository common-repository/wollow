jQuery(document).ready(function ($) {
    // add tab - textarea
    append_tabs()
    tinyMCE();
 
    var num_tabs = $("div#row-tab ul li").length + 1;
    if (num_tabs > 7) {
        $('.add-tabs').hide();
    }

    function append_tabs() {
        $("#item-tabs").tabs();



        $(".add-tabs").click(function () {
            var num_tabs = $("div#row-tab ul li").length + 1;
            var tabs = num_tabs - 1;

            var dataMce = tinymce.get(`summernote-${tabs}`).getContent();

            // data-target="#free"
            $(".popup-free").attr('data-bs-target', 'itemku');
            $(".popup-free").attr('class', 'f-right box-nav bg-danger popup-danger');

            if (num_tabs > 7) {
                $('.add-tabs').hide();
            }

            if (dataMce != '' && num_tabs < 8) {

                $("#tab-menu-pro").append(
                    "<li class='nav-item active'><a class='nav-link' aria-current='page' href='#" + num_tabs + "'>" + num_tabs + "</a></li>"
                );

                $("#tab-textarea").append(
                    "<div class='tab-pane active' id='" + num_tabs + "'><textarea id='summernote-" + num_tabs + "' class='template-wa' name='wollow" + num_tabs + "' id='after_checkout_textarea'></textarea></div>"
                );

                $("#tab-textarea").append(
                    tinyMCE()
                );

                $("#item-tabs").tabs("refresh");

                $("#item-tabs").tabs({
                    active: num_tabs - 1
                });

                $(".popup-danger").click(function () {
                    let message = `Please fill tab ${num_tabs}`;
                    iziToast.warning({
                        title: 'Warning',
                        message: message,
                        position: 'topRight', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
                    });
                });
            } else {
                let message = 'Your message in the previous tab is not complete';
                iziToast.info({
                    title: 'Info',
                    message: message,
                    position: 'topRight', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
                });
            }
        });


    }

    function tinyMCE() {
        tinymce.init({
            selector: 'textarea.template-wa',
            height: 250,
            width: '100%',
            menubar: false,
            plugins: 'emoticons,paste',
            toolbar: '| bold | italic | strikethrough | emoticons |',
            mode: "specific_textareas",
            editor_selector: "mceEditor"
        });
    }
    // end add tab - textarea

    setTimeout(() => {
        $('.information').hide();
    }, 5000);


    $('#hapusAktivasi').hover(function () {
        $("#hapusForm").attr('action', '#');
        $(".aktivasi-premium").css('background-color', 'red');
        $(".aktivasi-premium").css('color', 'white');
    }, function () {
        $("#hapusForm").attr('action', 'options.php');
        $(".aktivasi-premium").css('background-color', '#DEE2F7');
        $(".aktivasi-premium").css('color', 'black');
    })

    $('.close-custom').click(function (e) {
        e.preventDefault();
        $('.cta-upgrade').addClass('d-none');
    });
});