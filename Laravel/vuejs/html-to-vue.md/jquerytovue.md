यह एक बहुत ही विशिष्ट और उपयोगी `main.js` फ़ाइल है। यह स्पष्ट रूप से दिखाता है कि आपका टेम्पलेट लगभग पूरी तरह से **jQuery** पर निर्भर है, और इसमें DOM मैनीपुलेशन, प्लगइन इनिशियलाइज़ेशन (जैसे Swiper, Isotope, Lightcase), और इवेंट हैंडलिंग (`.on('click')`, `.on('scroll')`) शामिल है।

जैसा कि हमने चर्चा की, इस कोड को Vue के बाहर सीधे चलाने से **गंभीर समस्याएँ** (जैसे कि प्लगइन्स का काम न करना या Vue DOM में कॉन्फ्लिक्ट) होंगी।

### 🎯 समाधान: `main.js` का Vue-Compatible रूपांतरण

हम `main.js` के कोड को **दो भागों** में विभाजित करेंगे, ताकि वह Vue के जीवनचक्र के अनुसार चले:

1.  **तत्काल और Global लॉजिक (Immediate & Global):** (बहुत कम कोड, मुख्य रूप से Lightcase)
2.  **Initialization लॉजिक (On Mounted):** (आपका 95% कोड, जो DOM मैनीपुलेशन करता है)

#### चरण 1: `resources/js/vendor/main.js` को अपडेट करें

हम सभी jQuery लॉजिक को एक निर्यात किए गए (exported) फ़ंक्शन के अंदर रखेंगे, सिवाय `$(window).on('load')` और `lightcase` के टॉप-लेवल कॉल के लिए, क्योंकि उन्हें भी हमें `onMounted` में नियंत्रित करना होगा।

```javascript
// resources/js/vendor/main.js (Modified for Vue/Inertia)

// Note: यह मानते हुए कि jQuery ($) app.js में globally सेट किया गया है।

// =========================================================================
// 1. Initialization Logic (Exported Function)
// यह फ़ंक्शन Vue कॉम्पोनेंट के DOM में आने के बाद कॉल किया जाएगा।
// =========================================================================

export function initializeThemeFeatures() {
    // jQuery को लोकल स्कोप में परिभाषित करें (Closure)
    const $ = window.jQuery; 
    
    // यदि jQuery उपलब्ध नहीं है, तो तुरंत बाहर निकल जाएँ
    if (typeof $ === 'undefined') {
        console.error("jQuery is not defined. Theme features cannot be initialized.");
        return;
    }

    // --- A. Top-Level (जो DOM के तैयार होने पर चलता था) ---
    
    // lightcase - इसे onMounted में डालना सबसे सुरक्षित है
    $('a[data-rel^=lightcase]').lightcase();
    
    
    // --- B. $(document).ready() Logic का रूपांतरण ---

    // 1. Header Navigation Logic
    $("ul>li>ul").parent("li").addClass("menu-item-has-children");
    // Drop down menu width overflow problem fix (hover event, runs once)
    $('ul').parent('li').on('hover', function () {
        var menu = $(this).find("ul");
        var menupos = $(menu).offset();
        if (menupos.left + menu.width() > $(window).width()) {
            var newpos = -$(menu).width();
            menu.css({ left: newpos });
        }
    });
    // Mobile Menu Toggle Logic (click event)
    $('.mainmenu ul li a').off('click').on('click', function (e) {
        // ... (आपका पूरा मोबाइल मेनू टॉगल लॉजिक) ...
        var element = $(this).parent('li');
        if (parseInt($(window).width()) < 992) {
            if (element.hasClass('open')) {
                element.removeClass('open');
                element.find('li').removeClass('open');
                element.find('ul').slideUp(300, "swing");
            } else {
                element.addClass('open');
                element.children('ul').slideDown(300, "swing");
                element.siblings('li').children('ul').slideUp(300, "swing");
                element.siblings('li').removeClass('open');
                element.siblings('li').find('li').removeClass('open');
                element.siblings('li').find('ul').slideUp(300, "swing");
            }
        }
    });


    // 2. Member Filter Isotop initialization and events
    var $grid = $('.member__grid').isotope({
        itemSelector: '.member__item',
        layoutMode: 'fitRows',
    });
    var filterFns = {
        ium: function () {
            var name = $(this).find('.name').text();
            return name.match(/ium$/);
        }
    };
    $('.member__buttongroup').off('click', '.filter-btn').on('click', '.filter-btn', function () {
        var filterValue = $(this).attr('data-filter');
        filterValue = filterFns[filterValue] || filterValue;
        $grid.isotope({ filter: filterValue });
    });
    $('.member__buttongroup').each(function (i, buttonGroup) {
        var $buttonGroup = $(buttonGroup);
        $buttonGroup.off('click', '.filter-btn').on('click', '.filter-btn', function () {
            $buttonGroup.find('.is-checked').removeClass('is-checked');
            $(this).addClass('is-checked');
        });
    });

    // --- C. Top-Level Slider/Plugin Initialization ---

    // Banner slider
    new Swiper('.banner__slider', {
        slidesPerView: 1,
        spaceBetween: 0,
        autoplay: { delay: 10000, disableOnInteraction: false },
        loop: true,
    });
    // ragi slider
    new Swiper(".ragi__slider", {
        slidesPerView: 2,
        spaceBetween: 20,
        loop: true,
        autoplay: { delay: 5000, disableOnInteraction: false },
        navigation: { nextEl: ".ragi-next", prevEl: ".ragi-prev" },
        // ... breakpoints
    });
    // post thumb slider
    new Swiper('.blog__slider', {
        slidesPerView: 1,
        autoplay: { delay: 5000, disableOnInteraction: false },
        navigation: { nextEl: '.thumb-next', prevEl: '.thumb-prev' },
        loop: true,
    });
    // product single thumb slider
    var galleryThumbs = new Swiper('.pro-single-thumbs', { /* ... */ });
    var galleryTop = new Swiper('.pro-single-top', { /* ... */ });


    // product view mode change js
    $('.product-view-mode').off('click', 'a').on('click', 'a', function (e) {
        e.preventDefault();
        // ...
    });

    // model option start here
    $('.view-modal').off('click').on('click', function () {
        $('.modal').addClass('show');
    });
    $('.close').off('click').on('click', function () {
        $('.modal').removeClass('show');
    });

    // shop cart + - start here
    $(".qtybutton").off('click').on("click", function() {
        // ... (आपका Cart +/- लॉजिक) ...
    });

    // shop sidebar menu
    $(".shop-menu>li>ul").parent("li").addClass("catmenu-item-has-children");
    $('.shop-menu li a').off('click').on('click', function (e) {
        // ... (आपका Shop Menu Toggle लॉजिक) ...
    });

    // Review Tabs
    $('ul.review-nav').off('click', 'li').on('click', 'li', function (e) {
        // ... (आपका Review Tabs लॉजिक) ...
    });

    // countdown or date & time
    $('#countdown').countdown({
        date: '10/22/2023 17:00:00',
        offset: +2,
        day: 'Day',
        days: 'Days'
    });

    // contact form js
    var form = $('#contact-form');
    // ... (आपका Contact Form Submission लॉजिक) ...


    // D. Scroll/Window Based Logic (यह Vue Component के अंदर भी सेट हो सकता है)

    // Header Fixed on Scroll (इसे onMounted/onUnmounted में event listener के रूप में सेट करना बेहतर है)
    // For simplicity, we keep it here, but it might fire too often.
    $(window).off('scroll.header').on('scroll.header', function () {
        if ($(this).scrollTop() > 200) {
            fixed_top.addClass("header-fixed animated fadeInDown");
        } else {
            fixed_top.removeClass("header-fixed animated fadeInDown");
        }
    });

    // Scroll Up Button (on scroll)
    $(window).off('scroll.scrollToTop').on('scroll.scrollToTop', function () {
        // ... (आपका scroll to top show/hide logic) ...
    });
    $('a.scrollToTop').off('click').on('click', function () {
        // ... (आपका click event) ...
    });

    // Counter (on scroll)
    $(window).off('scroll.counter').on('scroll.counter', function () {
        // ... (आपका Counter init logic) ...
    });


    // E. Initialization (जो पहले टॉप लेवल पर था)
    new WOW().init();

}

// =========================================================================
// 2. Preloader Logic (Handling window.on('load'))
// Preloader को मुख्य Inertia/Vue App.blade.php में संभालना चाहिए।
// =========================================================================

export function handlePreloader() {
    const $ = window.jQuery;
    if (typeof $ === 'undefined') return;

    // यह Vue Component के onMounted हुक के बजाय window load पर चलेगा (जो Inertia app.blade.php के बाद होता है)
    // या, आप इसे Vue के onMounted में चला सकते हैं, जो DOM के तैयार होने पर Preloader को हटा देगा।
    
    setTimeout(function() {
        $('.preloader').fadeOut(1000);
    }, 100); // 100ms का छोटा विलंब ताकि DOM render हो जाए
}

```

### चरण 2: Vue Page Component में कॉल करें

अब आप अपने Vue कॉम्पोनेंट (`resources/js/Pages/HomePage.vue`) में इन फ़ंक्शंस का उपयोग करेंगे:

```vue
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { onMounted, onUnmounted } from 'vue';

// 1. main.js से फ़ंक्शंस Import करें
import { initializeThemeFeatures, handlePreloader } from '@/vendor/main.js'; 

onMounted(() => {
    // 2. जब Vue ने DOM को रेंडर कर दिया हो, तब थीम के फीचर्स को चलाएँ
    initializeThemeFeatures();
    
    // 3. Preloader को हटाएँ (यह मानकर कि Preloader HTML app.blade.php या AppLayout में है)
    handlePreloader(); 
});

onUnmounted(() => {
    // 4. Cleanup (यदि आवश्यक हो)
    // चूँकि आपके main.js में कई $(window).on('scroll') इवेंट हैं, 
    // आपको मेमोरी लीक से बचने के लिए उन्हें onUnmounted में हटाना चाहिए (off() का उपयोग करके)।
    const $ = window.jQuery;
    if (typeof $) {
        $(window).off('scroll.header');
        $(window).off('scroll.scrollToTop');
        $(window).off('scroll.counter');
    }
});
</script>

<template>
    <AppLayout>
    </AppLayout>
</template>
```

### ✅ महत्वपूर्ण ध्यान दें: Vue और jQuery के टकराव

हमने `off('event').on('event')` का उपयोग करके सुनिश्चित किया है कि इवेंट हैंडलर (event handlers) केवल एक बार सेट हों, भले ही कॉम्पोनेंट कई बार लोड हो।

  * **Preloader:** `handlePreloader` फ़ंक्शन में, आपको यह सुनिश्चित करना होगा कि `<div class="preloader">...</div>` HTML आपके `resources/views/app.blade.php` या `resources/js/Layouts/AppLayout.vue` में मौजूद हो।
  * **Scroll Events:** `$(window).on('scroll', function() { ... })` जैसे कोड Vue के दर्शन के विरुद्ध हैं। इसे चलाने के बजाय, Vue के अंदर ही Scroll Listener को जोड़ना और हटाना (जैसा कि `onUnmounted` में दिखाया गया है) एक क्लीनर तरीका है, लेकिन ऊपर दिया गया तरीका आपके कोड को कम बदलेगा।