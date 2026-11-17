function ModalClass() {

    var scope = this;
    var modalDom = $('.modal-default');
    var submitCallback;
    var cancelCallback;

    function init() {
        modalDom.find('.confirm').click(function() {
            if (submitCallback) {
                submitCallback();
            }
            modalDom.modal('hide');
        });
        modalDom.find('.cancel').click(function() {
            if (cancelCallback) {
                cancelCallback();
            }
            modalDom.modal('hide');
        });
        // modalDom.click(function(e) {
        //     var elm = e.target;
        //     if (elm && !$(elm).hasClass('modal-content') && !$(elm).hasClass('title')) {
        //         modalDom.modal('hide');
        //     }
        // });

        modalDom.bind('keydown', function(e) {
            if (e.keyCode == 27) {
                modalDom.modal('hide');
            }
        });

    }

    init();

    scope.confirm = function(_title, _body, _cancelBtnText, _confirmBtnText, _onSubmitCallback, _onCancelCallback) {
        submitCallback = _onSubmitCallback;
        cancelCallback = _onCancelCallback;
        modalDom.find('.cancel').text(_cancelBtnText);
        modalDom.find('.confirm').text(_confirmBtnText);
        modalDom.find('.modal-title').text(_title);
        modalDom.find('.modal-body').html(_body);
        modalDom.modal('show');
        modalDom.focus();
    };

}