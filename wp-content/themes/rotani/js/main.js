$(document).ready(function () {

  let vh = window.innerHeight * 0.01;
  document.documentElement.style.setProperty('--vh', `${vh}px`)

  const popUp = document.querySelector('.pop-up .container');
  const popUpElem = document.querySelector('.pop-up');
  const bodyElem = document.querySelector('body')
  let popUpClose;

  $('.product__learn-btn').click(function (e) {
    e.preventDefault();
    $.ajax({
      url: '/wp-admin/admin-ajax.php',
      type: 'POST',
      data: {
        action: 'popup_ajax_call',
        id: parseInt($(e.currentTarget).attr('data-id'))
      },
      success: function (res) {
        popUp.innerHTML = res;
        popUpElem.classList.add('active');
        bodyElem.style.overflow = 'hidden';
        var swiperThumb = new Swiper(".product__swiper-thumbs", {
          loop: true,

          slidesPerView: 5,
          watchSlidesProgress: true,
          allowTouchMove: false,
          spaceBetween: 18,
          speed: 800,
        });
        var swiper = new Swiper(".product__swiper", {
          simulateTouch: true,
          speed: 800,
          loop: true,
          watchSlidesProgress: true,
          thumbs: {
            swiper: swiperThumb,
          },
        });
        addCloseListener()
        // Arta.configure({
        //   apiKey: '1YreSAk470DkT2QgCdpcBAjl',
        //   objects: [
        //     {
        //       depth: parseInt($(e.target).next('.info').attr('data-length')),
        //       width: parseInt($(e.target).next('.info').attr('data-width')),
        //       height: parseInt($(e.target).next('.info').attr('data-height')),
        //       subtype: $(e.target).attr('data-subtype'),
        //       unit_of_measurement: 'in',
        //       weight_unit: 'lb',
        //       value_currency: 'USD',
        //       value: 200.0,
        //       weight: parseInt($(e.target).next('.info').attr('data-weight'))
        //     }
        //   ],
        //   origin: {
        //     address_line_1: $(e.target).next('.info').attr('data-address'),
        //     city: $(e.target).next('.info').attr('data-city'),
        //     region: $(e.target).next('.info').attr('data-region'),
        //     country: $(e.target).next('.info').attr('data-country'),
        //     postal_code: $(e.target).next('.info').attr('data-postal')
        //   }
        // })
        // .then((response) => {
        //   if (response && response.ok) {
        //     // Arta is ready. Enable the "Estimate Shipping" button (Optional)
        //   }
        // });
        if (document.querySelector('.wishlist-fragment')) {
          document.querySelector('.pop-up__wishlist').innerHTML = e.currentTarget.closest('.product').querySelector('.wishlist-fragment').outerHTML;
        }
        $('.pop-up .yith-wcwl-add-to-wishlist').on('click', function (e) {
          setTimeout(function () {
            $.ajax({
              url: '/wp-admin/admin-ajax.php',
              type: 'GET',
              data: {
                action: 'cart_ajax_call',
              },
              success: function (res) {
                $('.header__cart-num').removeClass('hidden')
                $('.header__cart-num').text(res);
              }
            });
          }, 4000)

        })
        // calculate shipping cost on single product
        $('.pisol-woocommerce-shipping-calculator').on('submit', function (e) {
          e.preventDefault();
          $.ajax({
            url: $('.pisol-woocommerce-shipping-calculator').attr('action'),
            type: 'POST',
            data: $('.pisol-woocommerce-shipping-calculator').serialize(),
            success: function (res) {
              $('.delivery-variants').html(JSON.parse(res).shipping_methods);
            }
          })
        });
        $('.pisol-woocommerce-shipping-calculator select').select2();
        $('.shipping-widget').click(function (e) {
          e.preventDefault();
          $('.shipping-widget').toggleClass('active')
          $('.pisol-ppscw-container').toggleClass('active')

        })
        $('.pisol-woocommerce-shipping-calculator button').attr('disabled', 'disabled');
        $('.pisol-woocommerce-shipping-calculator input:not([type="hidden"])').keyup(function () {

          var empty = false;
          $('.pisol-woocommerce-shipping-calculator input:not([type="hidden"])').each(function () {
            if ($(this).val() == '') {
              empty = true;
            }
          });

          if (empty) {
            $('.pisol-woocommerce-shipping-calculator button').attr('disabled', 'disabled'); // updated according to http://stackoverflow.com/questions/7637790/how-to-remove-disabled-attribute-with-jquery-ie
          } else {
            $('.pisol-woocommerce-shipping-calculator button').removeAttr('disabled'); // updated according to http://stackoverflow.com/questions/7637790/how-to-remove-disabled-attribute-with-jquery-ie
          }
        });
      }
    });
  });

  function addCloseListener() {
    popUpClose = document.querySelector('.pop-up__close')
    popUpClose.addEventListener('click', function (e) {
      popUpElem.classList.remove('active');
      bodyElem.style.overflow = 'auto';
    });
  }


  // const data = {
  //     "request":
  //     {
  //         "additional_services": ["origin_condition_check"],
  //         "currency": "USD",
  //         "destination": {
  //             "access_restrictions": ["stairs_only"],
  //             "address_line_1": "11 W 53rd St",
  //             "address_line_2": "string",
  //             "address_line_3": "string",
  //             "city": "New York",
  //             "region": "NY",
  //             "postal_code": "10019",
  //             "country": "US",
  //             "title": "Gallery",
  //             "contacts": [{
  //                 "name": "Mary Quinn Sullivan",
  //                 "email_address": "mary@example.com",
  //                 "phone_number": "(333) 333-3333"
  //             }
  //             ]
  //         },
  //         "insurance": "arta_transit_insurance",
  //         "internal_reference": "Purchase Order: 2801",
  //         "objects": [{
  //             "internal_reference": "Accession ID: 823",
  //             "current_packing": ["no_packing"],
  //             "depth": "3",
  //             "details": {
  //                 "materials": ["canvas"],
  //                 "creation_date": "1980",
  //                 "creator": "Bob Smithson",
  //                 "notes": "Artist signature in the lower left corner",
  //                 "title": "Black Rectangle",
  //                 "is_fragile": false,
  //                 "is_cites": false
  //             },
  //             "height": "32",
  //             "images": ["http://example.com/image.jpg"],
  //             "public_reference": "Round Smithson work",
  //             "subtype": "painting_unframed",
  //             "width": "15",
  //             "unit_of_measurement": "in",
  //             "weight": "3.0",
  //             "weight_unit": "lb",
  //             "value": "2500",
  //             "value_currency": "USD"
  //         }],
  //         "origin": {
  //             "access_restrictions": ["non_paved"],
  //             "address_line_1": "87 Richardson St",
  //             "address_line_2": "string",
  //             "address_line_3": "string",
  //             "city": "Brooklyn",
  //             "region": "NY",
  //             "postal_code": "11249",
  //             "country": "US",
  //             "title": "Warehouse",
  //             "contacts": [{
  //                 "name": "Rachel Egistrar",
  //                 "email_address": "registrar@example.com",
  //                 "phone_number": "(212) 123-4567"
  //             }]
  //         },
  //         "preferred_quote_types": ["parcel"],
  //         "public_reference": "Order #1437",
  //         "shipping_notes": "New customer"
  //     }
  // }

  const btn1 = document.querySelector('.shop__btns .grid')
  const btn2 = document.querySelector('.shop__btns .line')
  const products = document.querySelector('.shop-sec .products')
  if (btn1 && btn2 && products) {
    btn1.addEventListener('click', function () {
      btn1.classList.add('active');
      btn2.classList.remove('active');
      products.classList.add('grid');
    })
    btn2.addEventListener('click', function () {
      btn1.classList.remove('active');
      btn2.classList.add('active');
      products.classList.remove('grid');
    })
  }

  const timer = document.querySelector('.shop-sec__time');
  if (timer) {
    const timerPos = $(timer).offset().top;
    console.log(timerPos)
    window.addEventListener('scroll', function () {
      if (window.scrollY > timerPos) {
        timer.classList.add('active');
      } else {
        timer.classList.remove('active');
      }
    })
  }

  $('.product').on('click', function (e) {
    if (e.target.classList.contains('add_to_wishlist') || e.target.parentNode.classList.contains('add_to_wishlist')) {
      setTimeout(function () {
        $.ajax({
          url: '/wp-admin/admin-ajax.php',
          type: 'GET',
          data: {
            action: 'cart_ajax_call',
          },
          success: function (res) {
            $('.header__cart-num').removeClass('hidden')
            $('.header__cart-num').text(res);
          }
        });
      }, 4000)
    }
  })



  $('.cart-sec .remove').on('click', function () {
    setTimeout(function () {
      $.ajax({
        url: '/wp-admin/admin-ajax.php',
        type: 'GET',
        data: {
          action: 'cart_ajax_call',
        },
        success: function (res) {
          console.log(res);
          if (parseInt(res) === 0) {
            $.ajax({
              url: '/wp-admin/admin-ajax.php',
              type: 'GET',
              data: {
                action: 'empty_ajax_call',
              },
              success: function (res) {
                document.querySelector('main').innerHTML = res;
              }
            });
          }
        }
      });
    }, 2000)
  })

});