
//phone number validation function
function addHyphen(element) {
    let ele = document.getElementById(element.id);
    ele = ele.value.split('-').join('');    // Remove dash (-) if mistakenly entered.

    // console.log(ele);
    // console.log(ele.length);
    let finalVal = ele;
    if (ele.length == 10) {
        finalVal = ele.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
    }
    else if (ele.length >= 11) {
        finalVal = ele.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
        finalVal = finalVal.substring(0, 12);//get first 12 chars
    } else {
        finalVal = ele.match(/.{1,3}/g).join('-');
    }
    document.getElementById(element.id).value = finalVal;
}


// image upload validation
function readURL(input, class_name) {
    if (input.files && input.files[0]) {
        $type = input.files[0].type;
        if ($type == 'image/png' || $type == 'image/jpg' || $type == 'image/jpeg') {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.' + class_name).css('background', 'url(' + e.target.result + ')');
                $('.' + class_name).css('background-size', 'contain');
                $('.' + class_name).css('background-repeat', 'no-repeat');
                $('.' + class_name).css('background-position', 'center');
                $('.' + class_name + ' h2').hide();
                $('.' + class_name + ' p').hide();
                $('.' + class_name + ' svg').hide();
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            alert('You can only attached png, jpg or jpeg image format.')
        }
    }
}

function previewImages() {
    var attr_name = $(this).attr('data-img-val');

    $('.' + attr_name).html('');
    // $('.preview').html('');

    var preview = document.querySelector('.' + attr_name);
    // var preview = document.querySelector('.preview');

    if (this.files) {
        [].forEach.call(this.files, readAndPreview);
    }

    function readAndPreview(file) {

        // Make sure `file.name` matches our extensions criteria
        if (!/\.(jpe?g|png|gif)$/i.test(file.name)) {
            return alert(file.name + " is not an image");
        } // else...

        var reader = new FileReader();

        reader.addEventListener("load", function () {
            var image = new Image();
            image.height = 100;
            image.title = file.name;
            image.src = this.result;
            // console.log(image);
            // preview.appendChild('<a data-fancybox="demo" data-src="' + this.result + '">' + image + '</a>');
            // preview.appendChild(image);

            $('.' + attr_name).append('<a data-fancybox="demo" data-src="' + this.result + '"><img title="' + file.name + '" src="' + this.result + '" height="100"></a>');
            $('.' + attr_name).show();

        });

        reader.readAsDataURL(file);

    }

}

// document.querySelector('#profile_image').addEventListener("change", previewImages);
$(window).on('load', function () {
    if (feather) {
        feather.replace({
            width: 14,
            height: 14
        });
    }
});

// display a modal (delete modal)
$(document).on('click', '#delButton', function(event) {

    let href = $(this).attr('data-attr');
    let mode = $(this).attr('data-mode');
    let data_id = $(this).attr('data-id');

    $('#delForm').attr('action', href);
    
    if (mode == 'ajax') {
        $('#form_delete_Btn').hide();
        $('#ajax_delete_Btn').show();
        $('#ajax_delete_Btn').attr('data_id', data_id);
    }
    else {
        $('#form_delete_Btn').show();
        $('#ajax_delete_Btn').hide();
    }

    $('#delModal').modal('show');
});

$(document).on('click', '#ajax_delete_Btn', function(event) {
    
    var data_id = $(this).attr('data_id');
    delete_post_assets(data_id);
    
    // var data_id = $(this).attr('data-id');
    // let href = $(this).attr('data-attr');

    // var data_id = $('#ajax_delete_Btn').data('id');;
    // let href = $('#ajax_delete_Btn').data('attr');

    // console.log(' data_id ' + data_id);
    // console.log(' href ' + href);

    // return true;
    
    
});


$(document).on('change', '#user_role', function (event) {
    if ($(this).val() == 3) {
        $('.for_provider_role').hide();
        $('.for_customer_role').show();
    }
    else if ($(this).val() == 2) {
        $('.for_customer_role').hide();
        $('.for_provider_role').show();
    } else {
        $('.for_provider_role').hide();
        $('.for_customer_role').hide();
    }
}); 