$(function () {
        var time = 300;
        var delayAuto = $('#delay').attr("value");
        var sliderNextDelay = 1000 * delayAuto;
 
        var sliderContainer = $('div#tv');
        var tabContainers = sliderContainer.find('div.tv_tab');
        var tabSelectors = sliderContainer.find('.tab_nav a');
 
        function resizeSlider() {
                var maxHeight = 0;
                tabContainers.each(function () {
                        if ($(this).height() > maxHeight) {
                                maxHeight = $(this).height();
                        }
                });
 
                if (maxHeight > 0) {
                        sliderContainer.find('.tv_container').height(maxHeight);
                }
        }
 
        $('.tv_menu a').each(function () {
                if ($(this).text().length > 20)
                        $(this).parent().addClass('wide');
        });
 
        tabSelectors.on('click',function (e) {
                var _self = this;
                e.preventDefault();
                tabContainers.stop(true, true);
                var old_hash = $('.tab_nav a.active').removeClass('active').attr('href');
                if (typeof(old_hash) != 'undefined') {
                        tabContainers.filter(old_hash).fadeOut('time', function () {
                                tabContainers.filter(_self.hash).fadeIn(time);
                        });
                } else {
                        tabContainers.filter(_self.hash).fadeIn(time);
                }
 
                yeni_curSliderTabIndex = parseInt(this.hash.substring(5));
 
                $('.tab_nav a[href=' + $(_self).attr("href") + ']').addClass('active');
        }).first().trigger('click');
 
        $('body').on('mouseover', sliderContainer.selector,function () {
                yeni_mouseInSlider = true;
        }).on('mouseout', sliderContainer.selector, function () {
                        yeni_mouseInSlider = false;
                });
 
        var useAuto = $('#use').attr("value");
        if (useAuto == "Y") {
                var yeni_slider_interval = setInterval('yeni_slider_nextSlide()', sliderNextDelay);
        }
 
        $(window).on('resize', function () {
                resizeSlider();
        });
 
        $(window).on('load', function () {
                resizeSlider();
        });
 
        resizeSlider();
 
});
 
var yeni_mouseInSlider = false;
var yeni_curSliderTabIndex = 0;
 
function yeni_slider_nextSlide() {
        if (yeni_mouseInSlider) {
                return true;
        }
 
        var sliderContainer = $('div#tv');
        var tabSelectors = sliderContainer.find('.tab_nav a');
 
        var tabsCount = tabSelectors.length;
        yeni_curSliderTabIndex++;
        if (yeni_curSliderTabIndex >= tabsCount) {
                yeni_curSliderTabIndex = 0;
        }
 
        tabSelectors.eq(yeni_curSliderTabIndex).trigger('click');
}