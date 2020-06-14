jQuery(function ($) {
    function getPageSize() {
        var xScroll, yScroll;

        if (window.innerHeight && window.scrollMaxY) {
            xScroll = document.body.scrollWidth;
            yScroll = window.innerHeight + window.scrollMaxY;
        } else if (document.body.scrollHeight > document.body.offsetHeight) { // all but Explorer Mac
            xScroll = document.body.scrollWidth;
            yScroll = document.body.scrollHeight;
        } else if (document.documentElement && document.documentElement.scrollHeight > document.documentElement.offsetHeight) { // Explorer 6 strict mode
            xScroll = document.documentElement.scrollWidth;
            yScroll = document.documentElement.scrollHeight;
        } else { // Explorer Mac...would also work in Mozilla and Safari
            xScroll = document.body.offsetWidth;
            yScroll = document.body.offsetHeight;
        }

        var windowWidth, windowHeight;
        if (self.innerHeight) { // all except Explorer
            windowWidth = self.innerWidth;
            windowHeight = self.innerHeight;
        } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
            windowWidth = document.documentElement.clientWidth;
            windowHeight = document.documentElement.clientHeight;
        } else if (document.body) { // other Explorers
            windowWidth = document.body.clientWidth;
            windowHeight = document.body.clientHeight;
        }

// for small pages with total height less then height of the viewport
        if (yScroll < windowHeight) {
            pageHeight = windowHeight;
        } else {
            pageHeight = yScroll;
        }

// for small pages with total width less then width of the viewport
        if (xScroll < windowWidth) {
            pageWidth = windowWidth;
        } else {
            pageWidth = xScroll;
        }

        return [pageWidth, pageHeight, windowWidth, windowHeight];
    }
    function _SliderSize() {
        $size = getPageSize();
        if (($size[3] * 1) <= 770) {
            $(".main-slideshow").css('padding-top', 'calc(100vh - ' + $("#slider-sub-bg").height() + 'px - ' + $(".main-slideshow .uk-slideshow-items").height() + 'px + 1px)');
        } else {
            $(".main-slideshow").removeAttr("style");
        }
    }
    $(window).resize(function () {
        _SliderSize();
    });
    $(document).ready(function ($) {
        _SliderSize();
        function _QCount() {
            $ids = '';
            jQuery(".sb-questions-receiver li").each(function () {
                $this = jQuery(this);
                $class = $this.find("a.sb-lesson").attr("class");
                $class_array = $class.split(' ');
                jQuery.each($class_array, function ($i, $val) {
                    if ($val.indexOf('sb-lesson-') > -1) {
                        $ids += $val.replace('sb-lesson-', '') + "||";
                    }
                });
            });
            return $ids.replace('||toggle||', '');
        }
        /**
         * Добывает цену курса на странице транзакции
         */
        function _getPrice($id) {
            return $.ajax({
                type: "POST",
                url: "/",
                data: "tmpl=ajaxscript&t=getPrice&e=" + $id
            });
        }
        /**
         * Заполняем вопросы в форме олимпиады или ДЗ при открытии
         */
        if (jQuery("#cck1r_homework_questions").text().length > 0) {
            $ids = jQuery("#cck1r_homework_questions").text().replace("COM_CCK_Homework_Questions", '').split("||");
            jQuery.each($ids, function ($i, $val) {
                jQuery('.sb-lesson-' + $val).closest('li').appendTo('.sb-questions-receiver').find('.uk-h4').removeClass('uk-hidden');
            });
        }
        UIkit.util.on('.sb-word-placeholder', 'added', function (a, b, c) {
//            console.log(a);
//            console.log(b);
//            console.log(c);
//            console.log(jQuery(c).parent());
            jQuery(c).parent().children().slice(1).appendTo('.uk-drop-answer-words');
        });
        UIkit.util.on('.sb-word-placeholder', 'start', function (a, b, c) {
//            console.log(a);
//            console.log(b);
//            console.log(c);
            console.log("started10");
        });
        UIkit.util.on('.sb-questions-receiver', 'added', function (a, b, c) {
//            console.log(a);
//            console.log(b);
//            console.log(c);
            console.log("Added1");
            jQuery(c).find('.uk-h4').removeClass('uk-hidden');
            jQuery("#homework_questions").val(_QCount());
        });
        UIkit.util.on('.sb-questions-receiver', 'stop', function (a, b, c) {
            jQuery("#homework_questions").val(_QCount());
        });
        UIkit.util.on('.sb-questions-receiver', 'removed', function (a, b, c) {
            console.log(a);
            console.log(b);
            console.log(c);
            console.log("Removed1");
            jQuery(c).find('div.uk-h4').addClass('uk-hidden');
            jQuery("#homework_questions").val(_QCount());
        });
        jQuery(".sb-homework-question").on("click", function () {
            $this = jQuery(this);
            $this.closest('li').appendTo('.sb-questions-receiver').find('.uk-h4').removeClass('uk-hidden');
            jQuery("#homework_questions").val(_QCount());
            return false;
        });
        jQuery('[data-uk-datepicker]').flatpickr({
            locale: "ru",
            altInput: true,
            dateFormat: "Y-m-d",
            altFormat: "j M Y",
            onClose: function (selectedDates, dateStr, instance) {
                Jdate = Calendar.parseDate(dateStr, 0, "%d-%m-%Y");
                console.log(Calendar.printDate(Jdate, "%Y-%m-%d %H:%M:00"));
                jQuery('#' + instance.element.id + '_hidden').val(Calendar.printDate(Jdate, "%Y-%m-%d %H:%M:00"));
            }
        });
        $("#tl_period input").each(function () {
            if ($(this).prop('checked')) {
                $('.time-' + $(this).val().toLowerCase()).removeClass('uk-hidden');
            } else {
                $('.time-' + $(this).val().toLowerCase()).addClass('uk-hidden').find('select').val('');
            }
        });
        $("#tl_period").on('change', "input", function () {
            if ($(this).prop('checked')) {
                $('.time-' + $(this).val().toLowerCase()).removeClass('uk-hidden');
            } else {
                $('.time-' + $(this).val().toLowerCase()).addClass('uk-hidden').find('select').val('');
            }
        });
        $("select[id $= _hour]").on('change', function () {
            $this = $(this);
            $id = $this.attr('id');
            console.log($id);
            $("#" + $id + "_finish").find('option').each(function () {
                $this2 = $(this);
                $val = $this2.attr('value');
                if ($val * 1 < $this.val() * 1) {
                    $this2.attr('disabled', 'disabled');
                } else {
                    $this2.removeAttr('disabled');
                }
            });
        });
        $('#subjects_list').on('change', function () {
            $this = $(this);
            $val = $this.val();
            if ($val.length > 0) {
                $.ajax({
                    type: "POST",
                    url: "/",
                    data: "tmpl=ajaxscript&t=getLessonImage&e=" + $val,
                    success: function (data) {
                        $('#img_cont img').css('opacity', '0');
                        setTimeout(function () {
                            $('#img_cont').html('<img class="uk-transition-fade" src="/' + data + '" >');
                        }, 100);
                        setTimeout(function () {
                            $('#img_cont img').css('opacity', '1')
                        }, 300);
                    }
                });
            } else {
                $('#img_cont').html('<div id="user-ava-container" class="uk-square-medium uk-flex uk-flex-center uk-flex-middle background-ava uk-float-left uk-light uk-overflow-hidden"><span uk-icon = "icon: image; ratio: 10"></span></div>');
            }
        });


        $(".ui-sortable").on('click', '.icon-plus', function () {
            $this = $(this);
            setTimeout(function () {
                $this.closest(".ui-sortable").find('.icon-plus:not([uk-icon])').attr('uk-icon', 'icon: plus-circle').closest('.cck_cgx').addClass('cck_cgx_button');
                $this.closest(".ui-sortable").find('.icon-minus:not([uk-icon])').attr('uk-icon', 'icon: minus-circle');
                $this.closest(".ui-sortable").find('.icon-circle:not([uk-icon])').attr('uk-icon', 'icon: move');
                $this.closest(".ui-sortable").find('.collection-group-wrap:not(.uk-flex)').addClass('uk-flex uk-flex-between uk-flex-middle');
                $this.closest(".ui-sortable").find('select').addClass('uk-select');
                $this.closest(".ui-sortable").find('input:not(.uk-input)').addClass('uk-input').addClass('uk-width-1-1');
                $this.closest(".ui-sortable").find('textarea:not(.uk-textarea)').addClass('uk-textarea').addClass('uk-width-1-1');
                $this.closest(".ui-sortable").find('.cck_cgx_form').addClass('uk-width-1-1');
                $this.closest(".ui-sortable").find('.cck_form').addClass('uk-clearfix').addClass('uk-margin');
            }, 100);
            if ($this.val().length > 0) {
            }
        });
        $(".ui-sortable").each(function () {
            $this = $(this);
            $this.find('.icon-plus:not([uk-icon])').attr('uk-icon', 'icon: plus-circle').closest('.cck_cgx').addClass('cck_cgx_button');
            $this.find('.icon-minus:not([uk-icon])').attr('uk-icon', 'icon: minus-circle').closest('.cck_cgx').addClass('cck_cgx_button');
            $this.find('.icon-circle:not([uk-icon])').attr('uk-icon', 'icon: move').closest('.cck_cgx').addClass('cck_cgx_button');
            $this.find('.collection-group-wrap:not(.uk-flex)').addClass('uk-flex uk-flex-between uk-flex-middle');
            $this.find('textarea').addClass('uk-textarea').removeClass('uk-input');
        });
        /**
         * Убирает показ ошибок под полями через 5 секунд
         */
        function errorRemover(id) {
            setTimeout(function () {
                $('#' + id).next('.formError').remove();
            }, 5000);
        }
        $('input, textarea').each(function () {
            $this = $(this);
            if ($this.val().length > 0) {
                $this.addClass('uk-form-filled');
            }
        });
        $('input, textarea').on('blur', function () {
            $this = $(this);
            $id = $this.attr('id');
            if ($this.val().length > 0) {
                $this.addClass('uk-form-filled');
            } else {
                $this.removeClass('uk-form-filled');
            }
            errorRemover($id);
        });
        $("#menu-button").on('click', function () {
            $this = $(this);
            if ($this.hasClass('opened')) {
                UIkit.dropdown("#sb-menu-dropdown").hide(0);
            } else {
                UIkit.dropdown("#sb-menu-dropdown").show();
                $this.find('span').attr('uk-icon', 'icon: close-small; ratio: 2');
                $this.addClass('opened');
            }
        });
        UIkit.util.on('#sb-menu-dropdown', 'hidden', function () {
            $("#menu-button").removeClass('opened');
            $("#menu-button span").attr('uk-icon', 'icon: menu-2; ratio: 2');
        });
        UIkit.util.on('#sb-menu-dropdown', 'hidden', function () {
            $("#menu-button").removeClass('opened');
            $("#tabs-118 > li").removeClass('uk-active');
            $("#menu-button span").attr('uk-icon', 'icon: menu-2; ratio: 2');
        });
        $('#sb-menu-dropdown ul>li>a').click(function () {
            $href = $(this).attr('href');
            window.location = ($href);
        });
        $("#tabs-118 > li").hover(function () {
            $this = $(this);
            $("#tabs-118 > li").removeClass('uk-active');
            UIkit.tab('#tabs-118').show($this.index());
            $this.addClass('uk-active');
        });
        UIkit.util.on('#tab-content-118', 'hidden', function () {
            console.log('cloded');
        });
        $('#user-toggle').on('hover', function () {
            $width = ($('.uk-navbar-right').width() > 250) ? $('.uk-navbar-right').width() : 250;
            $('.user-menu').css('min-width', $width);
            UIkit.dropdown('.user-menu').show();
        });
        $('#user-toggle').on('mouseout', function () {
            UIkit.dropdown('.user-menu').hide();
        });
        $("#transaction_article").on('change', function () {
            $this = jQuery(this);
            $("#transaction_sum").html("");
            $id = $this.find("option:selected").attr("value");
            if ($id.length > 0) {
                $price = _getPrice($id);
                console.log($price);
                $price.done(function (data) {
                    $("#transaction_sum").html(data + " рублей");
                });

            }
        });
        $(".sb-button-schedule").on('click', function () {
            $("#sch_group").val($(this).attr('data-gid'));
        });
    });
});