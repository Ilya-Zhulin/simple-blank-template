/*
 * Simple Blank Template
 * Created by Vio Cassel and Ilya A.Zhulin
 * Sebloders 2015
 * http://sebloders.ru
 *
 * This file for edit. Use .min.js in template
 */
var Base64 = {
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
    //метод для кодировки в base64 на javascript
    encode: function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0
        //input = Base64._utf8_encode(input);
        while (i < input.length) {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);
            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;
            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }
            output = output +
                    this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
                    this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
        }
        return output;
    },
    //метод для раскодировки из base64
    decode: function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));
            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
        }
        // output = Base64._utf8_decode(output);
        return output;
    }
}

function sb_width() {
    var $width_array = [];
    var $html = '';
    $this = jQuery('select#jform_params_sb1_width');
    $width_array = {12: 6, 18: 6, 24: 6, 30: 3, 15: 15, 20: 20};
    for (var key in $width_array) {
        if ($this.val() % $width_array[key] === 0) {
            $html += '<option value="' + key + '">' + Math.floor(key * 100 / 60) + '%</option>'
        }
    }
    jQuery('select#jform_params_sb2_width').html($html);
    jQuery('select#jform_params_sb2_width').trigger('liszt:updated.chosen');
}
function ajax_query(a, k, b) {
    id = jQuery('#jform_params_less_compile_button').attr('data-extension-id');
    if (k < a.length) {
        jQuery.ajax({
            url: '/administrator/index.php',
            type: 'POST',
            data: 'option=com_templates&view=template&id=' + id + '&file=' + a[k] + '&task=template.less',
            dataType: 'html',
            success: function (data) {
                var m;
                var re = /<div id="system-message-container">((\n|.)*)<ul class="nav nav-tabs"/i;
                k++;
                if ((m = re.exec(data)) !== null) {
                    if (m.index === re.lastIndex) {
                        re.lastIndex++;
                    }
                }
                c = parseInt(b) * parseInt(k);
                if (c > 100) {
                    c = 100;
                }
                jQuery('#jform_params_less_compile_button').parent().parent().next('.control-group').find('.progress > div').attr('style', 'width:' + c + '%');
                if (k < a.length) {
                    jQuery('.bar').html('Processing ' + Base64.decode(a[k]));
                } else {
                    jQuery('.bar').html('');
                }
                jQuery('#jform_params_less_compile_button').parent().parent().next('.control-group').find('ul.unstyled').append('<li>' + Base64.decode(a[k - 1]) + m[0].replace('<ul class="nav nav-tabs"', '') + '</li>');
                ajax_query(a, k, c);
            }
        });
    } else {
        jQuery('#jform_params_less_compile_button').removeAttr('disabled');
        jQuery('.progress').removeClass('active');
    }
}
function LessCompile() {
    $files = [Base64.encode('/less/uikit.less')];
    $custom = jQuery('#jform_params_less_custom_file').val();
    if ($custom.length > 0) {
        $custom_files = $custom.split(',');
        $custom_files.forEach(function ($el) {
            $files.push(Base64.encode('/less/custom/' + $el.trim()));
        });
    }
    $dif = 100 / $files.length;
    $html = '<div class="control-group"><div class="control-label"><label title="" class="hasTooltip" title="Processing..."></label></div><div class="controls"><div class="progress progress-success progress-striped active"><div style="width: 0%" class="bar"><span class="text-error">Processing ' + Base64.decode($files[0]) + '...</span></div></div><ul class="unstyled"></ul></div></div>';
    jQuery('#jform_params_less_compile_button').attr('disabled', 'disabled').parent().parent().after($html);
    ajax_query($files, 0, $dif);
}


jQuery(document).ready(function () {
    sb_width();
    jQuery('select#jform_params_sb1_width').on('change', function () {
        sb_width();
    });
    jQuery('#jform_params_less_compile_button').on('click', function () {
        jQuery('#jform_params_less_compile_button').parent().parent().next('.control-group').slideUp(300);
        setTimeout("jQuery('#jform_params_less_compile_button').parent().parent().next('.control-group').remove()", 500);
        setTimeout(function () {
            LessCompile();
        }, 600);
    });
});
