$(function(){

    var showMessage = function(message, type) {
        $('#alertModalContent').html(message);
        $('#alertModal').modal({});
        setTimeout(function(){$('#alertModal').modal('hide')}, 3000);
    };

    var reloadTopCart = function(){
        var $cont = $('#topShopCartContainer');
        $cont.load($cont.attr('data-url'));
    };

    var checkIfCanPay = function(){
        var selDest = $('input.destination_id:checked').size();
        var selPaym = $('input.payment_id:checked').size();
        if (selDest>0 && selPaym>0) {
            $('#destination_id').val($('input.destination_id:checked').val());
            $('#payment_id').val($('input.payment_id:checked').val());
            $('#blockConfirm').show();
        } else {
            $('#blockConfirm').hide();
        }
    };

   $('#formLogin').submit(function(){
       var url = $(this).attr('action');
       var data = $(this).serialize();
       $.post(url, data, function(res){
           if (res.result=='ok') {
                document.location.reload();
           } else {
               $('#loginErrorModal').modal('show');
           }
       });

       return false;
   });

    $('form.add-to-cart').submit(function(){
        return false;
    });

    $('.container').on('click', 'button.add-to-cart', function(){
        var $form = $(this).closest('form');
        var url = $form.attr('action');
        var data = $form.serialize();
        $.post(url, data, function(r){
            if (r.result=='ok') {
                reloadTopCart();
                showMessage(r.message, 'success');
            } else {

            }
        });
        return false;
    });

    $('#topShopCartContainer')
        .hover(function(){$(this).addClass('hover')},function(){$(this).removeClass('hover')})
        .on('click','a.remove-from-cart',function(){
            $.get($(this).attr('href'), {}, function(r){
                reloadTopCart();
                showMessage(r.message, 'warning');
            });
            return false;
        });

    $('.update-cart-item').change(function(){
        $(this).closest('form').submit();
    });

    $('#formForgotPassword').submit(function(){
        var data = $(this).serialize();
        var url = $(this).attr('action');

        $('#sc-forgot-password').hide();
        $('#forgotPasswordForm').hide();
        $('#forgotPasswordMessage').hide();
        $('#forgotPasswordLoading').show();

        $.post(url, data, function(res){
            $('#forgotPasswordLoading').hide();
            $('#forgotPasswordMessage').html(res).show();
        });

        return false;
    });

    $('#sc-forgot-password').click(function(){
        $('#formForgotPassword').submit();
    });

    $('#sc-forgot-password-cancel').click(function(){
        $('#forgotPasswordLoading').hide();
        $('#forgotPasswordMessage').hide();
        $('#forgotPasswordForm').show();
        $('#sc-forgot-password').show();
    });

    $('#forgotPasswordMessage').on('click','a', function(){
        $('#forgotPasswordLoading').hide();
        $('#forgotPasswordMessage').hide();
        $('#forgotPasswordForm').show();
        $('#sc-forgot-password').show();

        return false;
    });

    $('#blockProfileDestinations')
        .on('click','.destination_edit', function(){
            var url = $(this).attr('href');
            var container = $(this).closest('.destination-container');
            container.load(url);
            return false;
        })
        .on('click','.destination_delete', function(){
            return (confirm('Eliminar?'));
        })
        .on('click', '#btnDestinationSave', function(){
            var container = $(this).closest('.destination-container');
            var form = $(this).closest('form');
            var url = form.attr('action');
            var data = form.serialize();
            $.post(url, data, function(res){
                container.html(res);
            });
        })
        .on('click', '#btnDestinationCancel', function(){
            var url = $(this).attr('href');
            var container = $(this).closest('.destination-container');
            container.load(url);
            return false;
        })
        .on('submit', 'form', function(){
            return false;
        });

    $('#blockDestination')
        .on('click','#btn-add-destination', function(){
            $('#sc-destination-sel').hide();
            $('#sc-destination-new').show();
            $('#usershippingaddress-shipping_contact').focus();
        })
        .on('click', '#btn-add-destination-cancel', function(){
            $('#sc-destination-new').hide();
            $('#sc-destination-sel').show();
        })
        .on('change', '.destination_id', function(){
            var form = $(this).closest('form');
            var url = form.attr('action');
            var data = form.serialize();
            $('#blockDestination').load(url, data, function(){
                checkIfCanPay();
            });
        })
        .on('click', '#btn-add-destination-save', function(){
            var form = $(this).closest('form');
            var url = form.attr('action');
            var data = form.serialize();
            $('#blockDestination').load(url, data, function(){
                checkIfCanPay();
            });
        })
        .on('click', '.btn-destination-edit', function(){
            var url = $(this).attr('href');
            $('#blockDestination').load(url, function(){
                $('#usershippingaddress-shipping_contact').focus();
                checkIfCanPay();
            });
            return false;
        })
        .on('click', '#btn-destination-delete', function(){
            var url = $(this).attr('href');
            $('#blockDestination').load(url, function(){
                checkIfCanPay();
            });
            return false;
        })
        .on('submit', 'form', function(){
            return false;
        });

    $('#blockPayment')
        .on('click','#btn-add-payment', function(){
            $('#sc-payment-sel').hide();
            $('#sc-payment-new').show();
            $('#creditcardform-credit_card').focus();
        })
        .on('click', '#btn-add-payment-cancel', function(){
            $('#sc-payment-new').hide();
            $('#sc-payment-sel').show();
        })
        .on('change', '.payment_id', function(){
            var form = $(this).closest('form');
            var url = form.attr('action');
            var data = form.serialize();
            $('#blockPayment').load(url, data, function(){
                checkIfCanPay();
            });
        })
        .on('click', '#btn-add-payment-save', function(){
            var form = $(this).closest('form');
            var url = form.attr('action');
            var data = form.serialize();
            $('#blockPayment').load(url, data, function(){
                checkIfCanPay();
            });
        })
        .on('click', '.btn-payment-edit', function(){
            var url = $(this).attr('href');
            $('#blockPayment').load(url, function(){
                $('#creditcardform-credit_card').focus();
                checkIfCanPay();
            });
            return false;
        })
        .on('click', '#btn-payment-delete', function(){
            var url = $(this).attr('href');
            $('#blockPayment').load(url, function(){
                checkIfCanPay();
            });
            return false;
        });

    $('.date-select').each(function(){
       var input = $(this);
       var minDate = new Date(input.attr('data-year'),input.attr('data-month'), input.attr('data-day'), 0, 0, 0, 0);
       input.datepicker({
           format:'yyyy-mm-dd',
           onRender: function(date) {
               return date.valueOf() < minDate.valueOf() ? 'disabled' : '';
           }
       }).on('changeDate', function(ev){
           $(this).datepicker('hide');
       });
    });

    checkIfCanPay();
});