/*  jQuery Nice Select - v1.0
https://github.com/hernansartorio/jquery-nice-select
Made by Hernán Sartorio  */
!(function (e) {
  e.fn.niceSelect = function (t) {
    function s(t) {
      t.after(
        e("<div></div>")
          .addClass("nice-select")
          .addClass(t.attr("class") || "")
          .addClass(t.attr("disabled") ? "disabled" : "")
          .attr("tabindex", t.attr("disabled") ? null : "0")
          .html('<span class="current"></span><ul class="list"></ul>')
      );
      var s = t.next(),
        n = t.find("option"),
        i = t.find("option:selected");
      s.find(".current").html(i.data("display") || i.text()),
        n.each(function (t) {
          var n = e(this),
            i = n.data("display");
          s.find("ul").append(
            e("<li></li>")
              .attr("data-value", n.val())
              .attr("data-display", i || null)
              .addClass(
                "option" +
                  (n.is(":selected") ? " selected" : "") +
                  (n.is(":disabled") ? " disabled" : "")
              )
              .html(n.text())
          );
        });
    }
    if ("string" == typeof t)
      return (
        "update" == t
          ? this.each(function () {
              var t = e(this),
                n = e(this).next(".nice-select"),
                i = n.hasClass("open");
              n.length && (n.remove(), s(t), i && t.next().trigger("click"));
            })
          : "destroy" == t
          ? (this.each(function () {
              var t = e(this),
                s = e(this).next(".nice-select");
              s.length && (s.remove(), t.css("display", ""));
            }),
            0 == e(".nice-select").length && e(document).off(".nice_select"))
          : console.log('Method "' + t + '" does not exist.'),
        this
      );
    this.hide(),
      this.each(function () {
        var t = e(this);
        t.next().hasClass("nice-select") || s(t);
      }),
      e(document).off(".nice_select"),
      e(document).on("click.nice_select", ".nice-select", function (t) {
        var s = e(this);
        e(".nice-select").not(s).removeClass("open"),
          s.toggleClass("open"),
          s.hasClass("open")
            ? (s.find(".option"),
              s.find(".focus").removeClass("focus"),
              s.find(".selected").addClass("focus"))
            : s.focus();
      }),
      e(document).on("click.nice_select", function (t) {
        0 === e(t.target).closest(".nice-select").length &&
          e(".nice-select").removeClass("open").find(".option");
      }),
      e(document).on(
        "click.nice_select",
        ".nice-select .option:not(.disabled)",
        function (t) {
          var s = e(this),
            n = s.closest(".nice-select");
          n.find(".selected").removeClass("selected"), s.addClass("selected");
          var i = s.data("display") || s.text();
          n.find(".current").text(i),
            n.prev("select").val(s.data("value")).trigger("change");
        }
      ),
      e(document).on("keydown.nice_select", ".nice-select", function (t) {
        var s = e(this),
          n = e(s.find(".focus") || s.find(".list .option.selected"));
        if (32 == t.keyCode || 13 == t.keyCode)
          return (
            s.hasClass("open") ? n.trigger("click") : s.trigger("click"), !1
          );
        if (40 == t.keyCode) {
          if (s.hasClass("open")) {
            var i = n.nextAll(".option:not(.disabled)").first();
            i.length > 0 &&
              (s.find(".focus").removeClass("focus"), i.addClass("focus"));
          } else s.trigger("click");
          return !1;
        }
        if (38 == t.keyCode) {
          if (s.hasClass("open")) {
            var l = n.prevAll(".option:not(.disabled)").first();
            l.length > 0 &&
              (s.find(".focus").removeClass("focus"), l.addClass("focus"));
          } else s.trigger("click");
          return !1;
        }
        if (27 == t.keyCode) s.hasClass("open") && s.trigger("click");
        else if (9 == t.keyCode && s.hasClass("open")) return !1;
      });
    var n = document.createElement("a").style;
    return (
      (n.cssText = "pointer-events:auto"),
      "auto" !== n.pointerEvents && e("html").addClass("no-csspointerevents"),
      this
    );
  };
})(jQuery);


$(document).ready(function () {
  /**blog-slider**/

  /******  Nice Select  ******/
  $("select").niceSelect();

  // /********* On scroll header Sticky *********/
  function initHeaderSticky() {
    if (jQuery(document).height() > jQuery(window).height()) {
      if (jQuery(this).scrollTop() > 100) {
        jQuery(".site-header").addClass("fixed");
      } else {
        jQuery(".site-header").removeClass("fixed");
      }
    }
  }

  $(document).ready(function () {
    initHeaderSticky();
  });
  $(window).on("resize scroll", function () {
    initHeaderSticky();
  });

  // /********* On scroll heder back *********/
  var prevScrollpos = window.pageYOffset;
  window.onscroll = function () {
    var currentScrollPos = window.pageYOffset;
    if (prevScrollpos > currentScrollPos) {
      document.getElementById("header-sticky").style.top = "0";
    } else {
      document.getElementById("header-sticky").style.top = "-200px";
    }
    prevScrollpos = currentScrollPos;
  };

  // mobile-menu
  $(".mobile-menu-button").on("click", function () {
    $(".menu-item-left").toggleClass("open");
    $(this).toggleClass("open-menu");
    $(this).toggleClass("change");
  });

  /** footer only one acnav open **/
  $(".acnav-label1").on("click", function () {
    if ($(window).width() < 768) {
      if ($(this).hasClass("is-open")) {
        $(this).removeClass("is-open");
        $(this).siblings(".acnav-list1").slideUp(200);
      } else {
        $(".acnav-label1").removeClass("is-open");
        $(this).addClass("is-open");
        $(".acnav-list1").slideUp(200);
        $(this).siblings(".acnav-list1").slideDown(200);
      }
    }
  });

  $(".counter").each(function () {
    $(this)
      .prop("Counter", 0)
      .animate(
        {
          Counter: $(this).text(),
        },
        {
          duration: 2000,
          easing: "swing",
          step: function (now) {
            $(this).text(Math.ceil(now));
          },
        }
      );
  });

  /****  TAB Js ****/
  $("ul.tabs li").click(function () {
    var $this = $(this);
    var $theTab = $(this).attr("data-tab");
    if ($this.hasClass("active")) {
    } else {
      $this
        .closest(".tabs-wrapper")
        .find("ul.tabs li, .tabs-container .tab-content")
        .removeClass("active");
      $(
        '.tabs-container .tab-content[id="' +
          $theTab +
          '"], ul.tabs li[data-tab="' +
          $theTab +
          "]"
      ).addClass("active");
    }
    $(this).addClass("active");
  });

  /******  STEPPY FORM  CSS  ******/
  $(".date-labls li").on("click", function () {
    $(".date-labls li").removeClass("active");
    $(this).addClass("active");
  });
  $(".time-labls li").on("click", function () {
    $(".time-labls li").removeClass("active");
    $(this).addClass("active");
  });

  var totalSteps = $(".steps li").length;
  $(".submit").on("click", function () {
    return false;
  });

  $(".steps li:nth-of-type(1)").addClass("active");
  $(".myContainer .step-container:nth-of-type(1)").addClass("active");

  $(".step-container").on("click", ".next", function () {
    $(".steps li")
      .eq($(this).parents(".step-container").index() + 1)
      .addClass("active");
    $(this)
      .parents(".step-container")
      .removeClass("active")
      .next()
      .addClass("active");
  });

  $(".step-container").on("click", ".back", function () {
    $(".steps li")
      .eq($(this).parents(".step-container").index() - totalSteps)
      .removeClass("active");
    $(this)
      .parents(".step-container")
      .removeClass("active")
      .prev()
      .addClass("active");
  });
  /******  STEPPY FORM  CSS  End******/

  // Back to top button
  var btn = $("#button");
  $(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
      btn.addClass("show");
    } else {
      btn.removeClass("show");
    }
  });

  btn.on("click", function (e) {
    e.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, "300");
  });

  // show-all btn
  $(".show-btn").click(function () {
    $(".read-more-content").slideToggle();
    if ($(".show-btn").text() == "read more") {
      $(this).text("read less");
    } else {
      $(this).text("read more");
    }
  });

  /**property-slider **/
  var swiper = new Swiper(".property-slider", {
    loop: true,
    slidesPerView: 3,
    infinite: true,
    autoplay: true,
    spaceBetween: 15,
    speed: 800,
    navigation: {
      nextEl: ".property-btn-wrp .swiper-button-next",
      prevEl: ".property-btn-wrp .swiper-button-prev",
    },
    breakpoints: {
      992: {
        slidesPerView: 3,
        spaceBetween: 30,
      },
      768: {
        slidesPerView: 2,
        spaceBetween: 15,
      },
      576: {
        slidesPerView: 2,
      },
      0: {
        slidesPerView: 1,
      },
    },
  });

  /**product-slider **/
  let $lgSwiper = document.getElementById('lg-swipper');
  var swiper = new Swiper('.product-slider', {
      loop: true,
      slidesPerView: 3,
      infinite: true,
      autoplay: true,
      spaceBetween: 15,
      speed: 800,
      navigation: {
      nextEl: ".product-btn-wrp .swiper-button-next",
      prevEl: ".product-btn-wrp .swiper-button-prev",
      },
      breakpoints: {
      768: {
          slidesPerView: 3,
      },
      481: {
          slidesPerView: 2,
      },
      0: {
          slidesPerView: 1,
      },
      },

      // Init lightGallery ince swiper is initilized
      on: {
          init: function () {
              const lg = lightGallery($lgSwiper);
              $lgSwiper.addEventListener('lgBeforeClose', () => {
                  swiper.slideTo(lg.index, 0)
              });
          },
      }
  });
});

