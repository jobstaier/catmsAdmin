var MailerView = Backbone.View.extend({
    el: '#kontakt',

    events: {
        'submit form': 'sendMail'
    },

    sendMail: function(event) {
        event.preventDefault();

        var form = this.$('form');
        var url = form.attr('action');
        var content = form.find('textarea').val();
        var contactMail = form.find('input:eq(0)').val();
        var contactName = form.find('input:eq(1)').val();

        var context = this;

        if (content != '' && contactMail != '' && contactName != '') {
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {
                    content : content,
                    contactMail: contactMail,
                    contactName: contactName
                },

                beforeSend: function(){
                    $.colorbox({
                        html: "<img src='/cary/img/loading.gif' />",
                        overlayClose: false,
                        escKey: false,
                        closeButton: false,
                        opacity: 0.25,
                        width: 410,
                        height: 220
                    })
                },
                success: function(data) {
                    var returnedData = JSON.parse(data);

                    if (returnedData.status == true) {
                        $.colorbox({
                            html: 'Wiadomość została wysłana poprawnie.',
                            close: 'Zamknij',
                            opacity: 0.25,
                            width: 410,
                            height: 200
                        });
                        context.cleanTextarea();
                    }
                },

                error: function(data) {
                    $.colorbox({
                        html: 'Nie udało się wysłać wiadomości. Spróbuj ponownie.',
                        close: 'Zamknij',
                        opacity: 0.25,
                        width: 410,
                        height: 200
                    })
                }
            });
        } else {
            $.colorbox({
                html: 'Należy uzupełnić wszystkie pola',
                close: 'Zamknij',
                opacity: 0.25,
                width: 410,
                height: 170
            });
        }
    },

    cleanTextarea: function() {
        var form = this.$('form');
        form.find('textarea').val('');
        form.find('input:eq(0)').val('');
        form.find('input:eq(1)').val('');
    }
});

$(function() {
    new MailerView();
});