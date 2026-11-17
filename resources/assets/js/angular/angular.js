var modal;
var app = angular.module('app', []);
app.config(['$interpolateProvider', function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
}]);
var sys = {
    init: function() {
        modal = new ModalClass();
        angular.bootstrap(document, ['app']);
    }
};
$(document).ready(function() {
    sys.init();
});