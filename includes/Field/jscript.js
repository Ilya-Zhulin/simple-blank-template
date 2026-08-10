/*
 * Simple Blank Template
 * Created by Vio Cassel and Ilya A.Zhulin
 * Sebloders 2015
 * http://sebloders.ru
 *
 * This file for edit. Use .min.js in template
 * Rewritten for Joomla 4/5 (no jQuery)
 */
var Base64 = {
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
    encode: function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;
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
        return output;
    }
};

function sb_width() {
    var widthArray = {12: 6, 18: 6, 24: 6, 30: 3, 15: 15, 20: 20};
    var sb1 = document.getElementById('jform_params_sb1_width');
    var sb2 = document.getElementById('jform_params_sb2_width');
    if (!sb1 || !sb2) return;
    var val = parseInt(sb1.value, 10);
    var html = '';
    for (var key in widthArray) {
        if (val % widthArray[key] === 0) {
            html += '<option value="' + key + '">' + Math.floor(key * 100 / 60) + '%</option>';
        }
    }
    sb2.innerHTML = html;
}

function ajax_query(a, k, b) {
    var btn = document.getElementById('jform_params_less_compile_button');
    var id = btn ? btn.getAttribute('data-extension-id') : '';
    if (k < a.length) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/administrator/index.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4) return;
            var data = xhr.responseText;
            var m;
            var re = /<div id="system-message-container">((\n|.)*)<ul class="nav nav-tabs"/i;
            k++;
            if ((m = re.exec(data)) !== null) {
                if (m.index === re.lastIndex) {
                    re.lastIndex++;
                }
            }
            var c = parseInt(b) * parseInt(k);
            if (c > 100) c = 100;

            var progressGroup = btn.parentElement.parentElement.nextElementSibling;
            if (progressGroup) {
                var bar = progressGroup.querySelector('.progress > div');
                if (bar) bar.style.width = c + '%';
            }

            var barText = document.querySelector('.bar');
            if (barText) {
                barText.innerHTML = k < a.length
                    ? 'Processing ' + Base64.decode(a[k])
                    : '';
            }

            if (progressGroup) {
                var list = progressGroup.querySelector('ul.unstyled');
                if (list) {
                    list.innerHTML += '<li>' + Base64.decode(a[k - 1]) + (m ? m[0].replace('<ul class="nav nav-tabs"', '') : '') + '</li>';
                }
            }
            ajax_query(a, k, c);
        };
        xhr.send('option=com_templates&view=template&id=' + id + '&file=' + a[k] + '&task=template.less');
    } else {
        if (btn) btn.removeAttribute('disabled');
        var progress = document.querySelector('.progress');
        if (progress) progress.classList.remove('active');
    }
}

function LessCompile() {
    var files = [Base64.encode('/media/less/uikit.less')];
    var customInput = document.getElementById('jform_params_less_custom_file');
    var custom = customInput ? customInput.value : '';
    if (custom.length > 0) {
        var customFiles = custom.split(',');
        customFiles.forEach(function (el) {
            files.push(Base64.encode('/media/less/custom/' + el.trim()));
        });
    }
    var dif = 100 / files.length;
    var btn = document.getElementById('jform_params_less_compile_button');
    if (btn) {
        btn.setAttribute('disabled', 'disabled');
        var progressHtml = '<div class="control-group"><div class="control-label"><label title="" class="hasTooltip" title="Processing..."></label></div><div class="controls"><div class="progress progress-success progress-striped active"><div style="width: 0%" class="bar"><span class="text-error">Processing ' + Base64.decode(files[0]) + '...</span></div></div><ul class="unstyled"></ul></div></div>';
        var container = btn.parentElement.parentElement;
        if (container) container.insertAdjacentHTML('afterend', progressHtml);
    }
    ajax_query(files, 0, dif);
}

document.addEventListener('DOMContentLoaded', function () {
    sb_width();
    var sb1 = document.getElementById('jform_params_sb1_width');
    if (sb1) {
        sb1.addEventListener('change', sb_width);
    }
    var compileBtn = document.getElementById('jform_params_less_compile_button');
    if (compileBtn) {
        compileBtn.addEventListener('click', function () {
            var container = compileBtn.parentElement.parentElement;
            var nextGroup = container ? container.nextElementSibling : null;
            if (nextGroup) {
                nextGroup.style.transition = 'max-height 0.3s, opacity 0.3s';
                nextGroup.style.maxHeight = '0';
                nextGroup.style.opacity = '0';
                nextGroup.style.overflow = 'hidden';
                setTimeout(function () {
                    if (nextGroup.parentElement) nextGroup.parentElement.removeChild(nextGroup);
                }, 500);
            }
            setTimeout(LessCompile, 600);
        });
    }
});
