function createOrUpdate(route) {
    $('#saveButton').attr('route', route);
    $.ajax({
        url: route,
        success: function (result) {
            $('#modalForm').empty();
            $('#modal').modal();
            $('#modalForm').append(result);
        },
        fail: function () {
        }
    });
}

function remove(route, index) {
    $('#button' + index).confirmation({
        placement: 'left',
        container: 'body',
        onConfirm: function (e) {
            e.preventDefault();
            $.ajax({
                url: route,
                success: function () {
                    $('#button' + index).confirmation('destroy');
                    setTimeout(function () {
                        location.reload();
                    },1);
                },
                fail: function () {
                    $('#button' + index).confirmation('destroy');
                }
            });

        },
        onCancel: function () {
        }
    }).confirmation('show');
}

function save() {
    var route = $('#saveButton').attr('route');
    var sendData = {};
    $('#modalForm *').each(function () {
        var currentElement = $(this);
        var name = currentElement.attr('name');
        if (name && (currentElement.attr('type') != 'hidden')) {
            var value;
            if (currentElement.is('select')) {
                value = currentElement.find(':selected').attr('value');
            } else if (currentElement.is('input')) {
                if (currentElement.attr('type') == 'checkbox') {
                    value = currentElement.is(":checked") ? 1 : 0;
                } else {
                    value = currentElement.val();
                }
            }
            sendData[name] = value;
        }
    });
    setTimeout(function () {
        $.ajax({
            type: 'POST',
            data: sendData,
            url: route,
            success: function (result) {
                result = JSON.parse(result);
                if (result.success) {
                    location.reload();
                } else {
                    $('.negative_message').text('');
                    for (var key in result) {
                        if (result.hasOwnProperty(key)) {
                            for (var keyArr in result[key]) {
                                var newMessage = $('<div>').attr('class', 'negative_message');
                                newMessage.text(result[key][keyArr].text).css('color', 'red');
                                $('.message').append(newMessage);
                            }

                        }
                    }
                }
            },
            fail: function () {
            }
        });
    }, 1)
}