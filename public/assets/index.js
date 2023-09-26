$( document ).ready(function() {
    var total = 0;
    $( ".fa-mobile-alt" ).click(function() {
      $(".preview").css("width", "350px");
      $(".preview").css("margin", "30px 17%");
      $(".preview-order").css("width", "400px");
      $( ".products" ).removeClass( "col-md-7" ).addClass( "col-md-12" );
      $( ".price1" ).removeClass( "col-md-5" ).addClass( "col-md-12" );
      $(".fa-mobile-alt").css("color", "#262931");
      $(".fa-desktop-alt").css("color", "gray");
    });

    $( ".fa-desktop" ).click(function() {
      $(".preview").css("width", "100% ");
      $(".preview").css("margin", "30px 0%");
      $( ".products" ).removeClass( "col-md-12" ).addClass( "col-md-7" );
      $( ".price1" ).removeClass( "col-md-12" ).addClass( "col-md-5" );
      $(".fa-desktop").css("color", "#262931");
      $(".fa-mobile-alt").css("color", "gray");
      $(".preview-order").css("width", "100%");

    }) ;

      function fieldTransfer(x, y){
          $(x).last().val($(y).val());
      }

      function showField(x, y) {
          $(x).change(function() {
              $(y).toggle();
          });
        }
      var items = [
          ['.hs-1', '.h-1'],
          ['.hs-3', '.h-3'],
          ['.hs-4', '.h-4'],
          ['.hs-5', '.h-5'],
          ['.hs-6', '.h-6'],
          ['.hs-7', '.h-7'],
          ['.tbc', '#tpay_div'],
          ['.payzes_split', '#payze_split_div'],
          ['.open-banking', '#open_banking_div'],
          ['.payzes', '#payz_div'],
          ['.payriff', '#payriff_div'],
          ['.stripe', '#stripe_div'],
          ['.ipay', '#ipay_div'],
      ];

      items.forEach(fieldForeach);

      $("#image").change(function(event) {
        var x = URL.createObjectURL(event.target.files[0]);
        $("#review_image").attr("src",x);
        $("#review_image").addClass( "review-image" );
      });

      $( "#reset" ).click(function() {
          if($('#prod_price').val() == '')
          {
              Swal.fire({
                  icon: 'error',
                  title: 'შეცდომა...',
                  text: 'გთხოვთ შეიყვანოთ პროდუქტის ფასი',
                })
          }
          else if($('#prod_valuta').val() == null)
          {
              Swal.fire({
                  icon: 'error',
                  title: 'შეცდომა...',
                  text: 'გთხოვთ პარამეტრებიდან აირჩიოთ სასურველი ვალუტა',
              })
          }
          else if($('#prod_name').val() != '' && $('#prod_price').val() != '' && $('#prod_valuta').val() != null)
          {
              $( "#nano" ).append( "<div><div class='preview-tr d-flex'><td><div class='prod-img th-1 d-flex'><img style='width:50px; height:50px; object-fit:cover;'  src='../assets/image/product.png' class='upload-imgs x-img mr-2'><h6 class='ap-name'></h6></div></td><div class='th-2'><h6 class='preview-quantity ap-amount'></h6></div><div class='th-3'><h6 class='preview-price ap-price'></h6></div> <div class='th-0'><div class='btn btn-removes' onclick='$( this ).parent().parent().parent().remove()'>x</div></div></div>                   <div class='d-none'>  <div><input type='text' class='input-save' name='save_index[]'></div> <div> <input type='text' class='input-valuta' name='valuta[]'></div> <div><input type='text' class='input-amounts' name='amount[]'></div> <div><input type='text' class='input-ge-name' name='ge_name[]'></div> <div><input type='text' class='input-en-name' name='en_name[]'></div><div><input type='text' class='input-am-name' name='am_name[]'></div><div><input type='text' class='input-az-name' name='az_name[]'></div><div><input type='text' class='input-de-name' name='de_name[]'></div><div><input type='text' class='input-kz-name' name='kz_name[]'></div><div><input type='text' class='input-ru-name' name='ru_name[]'></div><div><input type='text' class='input-ua-name' name='ua_name[]'></div><div><input type='text' class='input-uz-name' name='uz_name[]'></div><div><input type='text' class='input-tj-name' name='tj_name[]'></div><div><input type='text' class='input-tr-name' name='tr_name[]'></div>              <div> <input type='text' class='input-prices' name='price[]'> </div> <div> <input type='text' class='input-discount-prices' name='price_discount[]'> </div> <div> <input type='text' class='saved_images' name='saved_image[]'> </div></div>" );
              $(".image-input").last().clone().appendTo("#nano");
              $( ".ap-name" ).last().append($('#prod_ge_name').val());
              $( ".ap-amount" ).last().append($('#prod_amount').val());

              onerror='this.src="../assets/image/products.png"'
              if($('#prod_discount_price').val() != 0 && $('#prod_discount_price').val() != null){
                $( ".ap-price" ).last().append($('#prod_discount_price').val());
              }
              else{
                $( ".ap-price" ).last().append($('#prod_price').val());
              }
              fieldTransfer('.input-ge-name', '#prod_ge_name')
              fieldTransfer('.input-en-name', '#prod_en_name')
              fieldTransfer('.input-am-name', '#prod_am_name')
              fieldTransfer('.input-az-name', '#prod_az_name')
              fieldTransfer('.input-de-name', '#prod_de_name')
              fieldTransfer('.input-kz-name', '#prod_kz_name')
              fieldTransfer('.input-ru-name', '#prod_ru_name')
              fieldTransfer('.input-ua-name', '#prod_ua_name')
              fieldTransfer('.input-uz-name', '#prod_uz_name')
              fieldTransfer('.input-tj-name', '#prod_tj_name')
              fieldTransfer('.input-tr-name', '#prod_tr_name')

              fieldTransfer('.input-valuta', '#prod_valuta')
              fieldTransfer('.input-save', '#prod_save')
              fieldTransfer('.input-prices', '#prod_price')
              fieldTransfer('.input-discount-prices', '#prod_discount_price')
              fieldTransfer('.input-amounts', '#prod_amount')
              fieldTransfer('.input-desc', '#prod_desc')
              if($('#saved_image').last().val() != '' && $('#saved_image').last().val() != null){
                $('.saved_images').last().val($('#saved_image').val());
                $(".x-img").last().attr("src", '../assets/image/'+ $('#saved_image').val());
                $('#prod_save').last().val(0)
              }
              else {
                $('.saved_images').last().val($('#saved_image').val());
                $(".x-img").last().attr("src", '../assets/image/product.png');
                $('#prod_save').last().val(0)
              }
              $('#prod_name').val('');
              $('#prod_desc').val('');
              $('#prod_amount').val('1');
              $('#prod_price').val('');
              $('#exampleModalCenter').modal('toggle');

              var amount = $('.input-amounts').last().val();
              var price = $('.input-prices').last().val();
              total  = total + amount * price;
              let total_n = total.toFixed(2)
              $( ".last-total-price" ).append(total_n);
              $( ".total-price" ).last().empty().append(total_n);
              $('.image-input').last().val('');
              $('#saved_image').last().val('');
              $("#review_image").removeClass( "review-image" );

          }
      });
      function fieldForeach(item) {
          showField(item[0], item[1]);
      }

      if(($('.tbc').prop("checked") == true)) {
          $('#tpay_div').css("display","block");
      }
      if(($('.open-banking').prop("checked") == true)) {
        $('#open_banking_div').css("display","block");
      }
      if(($('.payzes').prop("checked") == true)) {
          $('#payz_div').css("display","block");
      }
      if(($('.payzes_split').prop("checked") == true)) {
        $('#payze_split_div').css("display","block");
      }
      else{
        $('#payze_split_div').css("display","none");
      }
      if(($('.stripe').prop("checked") == true)) {
          $('#stripe_div').css("display","block");
      }
      if(($('.payriff').prop("checked") == true)) {
        $('#payriff_div').css("display","block");
      }
      if(($('.ipay').prop("checked") == true)) {
          $('#ipay_div').css("display","block");
      }
      else{
          $('#ipay_div').css("display","none");
      }

      $('.tbc-prev').change(function() {
          $('#tbc_prev').toggle();
          $('.pay-method').css("display","block");
      });
      if(($('.tbc-prev').prop("checked") == true)) {
        $('#tbc_prev').css("display","block");
      }

      $('.payriff-prev').change(function() {
        $('#payriff_prev').toggle();
        $('.pay-method').css("display","block");
      });
      if(($('.payriff-prev').prop("checked") == true)) {
        $('#payriff_prev').css("display","block");
      }

      $('.payze-prev').change(function() {
          $('#payze_prev').toggle();
          $('.pay-method').css("display","block");
      });
      if(($('.payze-prev').prop("checked") == true)) {
        $('#payze_prev').css("display","block");
      }

      $('.payze-iban-prev').change(function() {
        $('#payze_iban_prev').toggle();
        $('.pay-method').css("display","block");
      });
      if(($('.payze-iban-prev').prop("checked") == true)) {
        $('#payze_iban_prev').css("display","block");
      }

      $('.stripe-prev').change(function() {
          $('#stripe_prev').toggle();
          $('.pay-method').css("display","block");
      });
      if(($('.stripe-prev').prop("checked") == true)) {
        $('#stripe_prev').css("display","block");
      }

      $('.ipay-prev').change(function() {
          $('#ipay_prev').toggle();
          $('.pay-method').css("display","block");
      });
      if(($('.ipay-prev').prop("checked") == true)) {
        $('#ipay_prev').css("display","block");
      }

      $('.open-prev').change(function() {
        $('#open_prev').toggle();
        $('.pay-method').css("display","block");
      });
      if(($('.open-prev').prop("checked") == true)) {
        $('#open_prev').css("display","block");
    }

      if ($(window).width() > 991) {
          $( ".collapse-none" ).removeClass( "collapse" );
      }
  });

  $( ".x" ).click(function() {
      $('.cover').css( "display", "none" );
      $('.hidden-nav').css( "display", "none" );
  });

  $( ".cover" ).click(function() {
      $('.cover').css( "display", "none" );
      $('.hidden-nav').css( "display", "none" );
  });

  $( ".burger" ).click(function() {
      $('.hidden-nav').css( "display", "block" );
      $('.cover').css( "display", "block" );
  });



  $( ".display-invoice" ).click(function() {
    $('.invoice-report').css( "display", "block" );
    $('.products-report').css( "display", "none" );
  });
  $( ".display-product" ).click(function() {
    $('.products-report').css( "display", "block" );
    $('.invoice-report').css( "display", "none" );
  });

  function linkFunction() {
    var copyText = document.getElementById("linkInput");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    }


  function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
  }
  
  function filterFunction() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    div = document.getElementById("myDropdown");
    a = div.getElementsByTagName("li");
    for (i = 0; i < a.length; i++) {
      txtValue = a[i].textContent || a[i].innerText;
      if (txtValue.toUpperCase().indexOf() > -1) {
        a[i].style.display = "";
      } else {
        a[i].style.display = "none";
      }
    }
  }




  










  // ---------------------------------------------------------------------------------------------------





