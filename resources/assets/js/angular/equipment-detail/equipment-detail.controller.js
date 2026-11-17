app.controller('EquipmentDetailController', ['$scope', '$timeout',
    function($scope, $timeout) {
        //NormalDOM
        var editEquipmentFormDOM = $('#edit-equipment-form');
        var roomEquipmentsSpanDOM = $('#room-equipments-span');
        var isAdminSpanDOM = $('#is-admin-span');
        var roomsSpanDOM = $('#rooms-span');
        var availableQuantitySpanDOM = $('#available-quantity-span');

        //TextDom
        var modalDeleteTitleTextDOM = $('#modal-delete-title')
        var modalDeleteBodyTextDOM  = $('#modal-delete-body')
        var buttonCancelTextDOM = $('#button-cancel')
        var buttonDeleteTextDOM = $('#button-delete')

        var availableQuantityShowDOM = $('#available_quantity_show');
        var totalQuantityShowDOM = $('#total_quantity_show');
        var roomEquipmentTakenQualityDOM = $('.input-group-room-equipment-taken-quantity').find('input');
        var editRoomEquipmentTakenQualityDOM = $('#editRoomEquipmentTakenQuality');

        //Datetime Pickers
        var addRoomEquipmentDatetimeGroupDOM = $('#add-room-equipment-datetime-group');
        var editRoomEquipmentDatetimeGroupDOM = $('#edit-room-equipment-datetime-group');

        $scope.init = function() {
            $scope.isAddNewRoomEquipment = false;
            $scope.isEditRoomEquipment = false;
            $scope.deletedRoomEquipment = [];
            $scope.editRooomEquipemnt = {};
            $scope.editRoomEquipmentIndex = null;
            $scope.roomEquipments = JSON.parse(roomEquipmentsSpanDOM.attr('data-room-equipments'));
            roomEquipmentsSpanDOM.remove();
            if (!$scope.roomEquipments && $scope.roomEquipments.length > 0) { 
                  $scope.roomEquipments = []; 
            } 
            $scope.rooms = JSON.parse(roomsSpanDOM.attr('data-rooms'));
            roomsSpanDOM.remove();
            $scope.availableQuantity = availableQuantitySpanDOM.attr('data-available-quantity');
            if ($scope.availableQuantity) {
                $scope.availableQuantity = parseFloat($scope.availableQuantity);
            }
            availableQuantitySpanDOM.remove();
            $scope.isAdmin = isAdminSpanDOM.attr('data-is-admin') == 1 ? true : false;
            isAdminSpanDOM.remove();
            initLocalizeText();
        }

        $scope.onAddRoomEquipmentBtnClick = function() {
            $scope.isAddNewRoomEquipment = true;
            initNewRoomEquipment();
        }

        $scope.validateRoomEquipmentExist = function(roomId) {
            var result = false;
            if ($scope.roomEquipments && $scope.roomEquipments.length > 0) {
                $scope.roomEquipments.forEach(function(value, index) {
                    if (value.room_id == roomId) {
                        result = true;
                    }
                });
            }
            return result;
        }

        $scope.validateEditRoomEquipmentExist = function(roomId) {
            var result = false;
            if ($scope.roomEquipments && $scope.roomEquipments.length > 0) {
                $scope.roomEquipments.forEach(function(value, index) {
                    if (value.room_id == roomId && value.room_id != $scope.selectedEditRoomEquipment) {
                        result = true;
                    }
                });
            }
            return result;
        }

        $scope.getRoomNo = function(roomId) {
            var result = '';
            if ($scope.rooms && $scope.rooms.length > 0) {
                $scope.rooms.forEach(function(value, index) {
                    if (value.id == roomId) {
                        result = value.room_no;
                    }
                });
            }
            return result;
        }

        $scope.onNewRoomEquipmentChangeRoom = function() {
            $scope.newRoomEquipment.room_id = $scope.selectedNewRoomEquipment;
        }

        $scope.onEditRoomEquipmentChangeRoom = function() {
            $scope.editRooomEquipemnt.room_id = $scope.selectedEditRoomEquipment;
        }

        $scope.onSubmitRoomEquipmentBtnClick = function() {
            var takenDate = addRoomEquipmentDatetimeGroupDOM.find('input').val();
            var isOverAvailableQuantity = roomEquipmentTakenQualityDOM.getNumber() > 0 && $scope.availableQuantity - roomEquipmentTakenQualityDOM.getNumber() >= 0;
            if ($scope.newRoomEquipment &&
                $scope.newRoomEquipment.room_id &&
                isOverAvailableQuantity &&
                takenDate) {
                $scope.newRoomEquipment.taken_date = takenDate;
                $scope.newRoomEquipment.taken_quantity_show = roomEquipmentTakenQualityDOM.getFormatted();
                $scope.newRoomEquipment.taken_quantity = roomEquipmentTakenQualityDOM.getNumber();
                $scope.isAddNewRoomEquipment = false;
                $scope.roomEquipments.push($scope.newRoomEquipment);
                $scope.availableQuantity -= roomEquipmentTakenQualityDOM.getNumber();
                availableQuantityShowDOM.set($scope.availableQuantity);
                initNewRoomEquipment();
            }

        }

        $scope.onCancelSubmitRoomEquipmentBtnClick = function() {
            $scope.isAddNewRoomEquipment = false;
            initNewRoomEquipment();
        }

        $scope.onDeleteRoomEquipmentBtnClick = function(index) {

            modal.confirm( $scope.localizeText.modalDeleteTitleText,
            $scope.localizeText.modalDeleteBodyText,
            $scope.localizeText.buttonCancelText,
            $scope.localizeText.buttonDeleteText,
            function(){ 
                if ($scope.roomEquipments[index].id) {
                    var deleteRoomEquipment = angular.copy($scope.roomEquipments[index]);
                    $scope.deletedRoomEquipment.push(deleteRoomEquipment);
                }
                $scope.availableQuantity += $scope.roomEquipments[index].taken_quantity;
                availableQuantityShowDOM.set($scope.availableQuantity);
                $scope.roomEquipments.splice(index, 1);
                $scope.$apply();
            }); 

        }

        $scope.onEditRoomEquipmentBtnClick = function(index) {
            $scope.isEditRoomEquipment = true;
            $scope.editRoomEquipmentIndex = index;
            $scope.editRooomEquipemnt = angular.copy($scope.roomEquipments[index]);
            if(typeof $scope.editRooomEquipemnt.room_id === 'number') {
                $scope.editRooomEquipemnt.room_id = $scope.editRooomEquipemnt.room_id.toString();
            }
            $scope.selectedEditRoomEquipment = $scope.editRooomEquipemnt.room_id;
            editRoomEquipmentTakenQualityDOM = $('.input-group-edit-room-equipment-taken-quantity').find('#editRoomEquipmentTakenQuality' + index);

            editRoomEquipmentTakenQualityDOM = new AutoNumeric(editRoomEquipmentTakenQualityDOM[0], {
                decimalPlaces: 0,
                minimumValue: 0,
                modifyValueOnWheel: false,
            });
            editRoomEquipmentTakenQualityDOM.set($scope.editRooomEquipemnt.taken_quantity);
            editRoomEquipmentDatetimeGroupDOM = $('#edit-room-equipment-datetime-group-' + index);
            editRoomEquipmentDatetimeGroupDOM.datetimepicker({
                date: moment($scope.editRooomEquipemnt.taken_date, 'DD/MM/YYYY HH:mm:ss'),
                format: 'DD/MM/YYYY HH:mm:ss',
                showTodayButton: true,
                allowInputToggle: true
            });
        }

        $scope.onSaveRoomEquipmentBtnClick = function() {

            var isOverAvailableQuantity = true;
            var takenDate = editRoomEquipmentDatetimeGroupDOM.find('input').val();

            if (editRoomEquipmentTakenQualityDOM.getNumber() > 0 &&
                editRoomEquipmentTakenQualityDOM.getNumber() < $scope.roomEquipments[$scope.editRoomEquipmentIndex].taken_quantity) {
                isOverAvailableQuantity = false;
                $scope.availableQuantity += $scope.roomEquipments[$scope.editRoomEquipmentIndex].taken_quantity - editRoomEquipmentTakenQualityDOM.getNumber();
                availableQuantityShowDOM.set($scope.availableQuantity);
            } else if (editRoomEquipmentTakenQualityDOM.getNumber() > 0 &&
                editRoomEquipmentTakenQualityDOM.getNumber() > $scope.roomEquipments[$scope.editRoomEquipmentIndex].taken_quantity &&
                $scope.availableQuantity + $scope.roomEquipments[$scope.editRoomEquipmentIndex].taken_quantity - editRoomEquipmentTakenQualityDOM.getNumber() >= 0) {
                isOverAvailableQuantity = false;
                $scope.availableQuantity += $scope.roomEquipments[$scope.editRoomEquipmentIndex].taken_quantity;
                $scope.availableQuantity -= editRoomEquipmentTakenQualityDOM.getNumber();
                availableQuantityShowDOM.set($scope.availableQuantity);
            } else if (editRoomEquipmentTakenQualityDOM.getNumber() == $scope.roomEquipments[$scope.editRoomEquipmentIndex].taken_quantity) {
                isOverAvailableQuantity = false;
            }

            if (takenDate &&
                !isOverAvailableQuantity &&
                $scope.editRooomEquipemnt.room_id) {

                $scope.editRooomEquipemnt.taken_quantity = editRoomEquipmentTakenQualityDOM.getNumber();
                $scope.editRooomEquipemnt.taken_quantity_show = editRoomEquipmentTakenQualityDOM.getFormatted();
                $scope.editRooomEquipemnt.taken_date = takenDate;
                $scope.roomEquipments[$scope.editRoomEquipmentIndex] = angular.copy($scope.editRooomEquipemnt);

                editRoomEquipmentTakenQualityDOM.remove();
                $scope.editRoomEquipmentIndex = null;
                $scope.isEditRoomEquipment = false;
            }
        }

        $scope.onCancelEditRoomEquipmentBtnClick = function() {
            editRoomEquipmentTakenQualityDOM.remove();
            $scope.editRoomEquipmentIndex = null;
            $scope.isEditRoomEquipment = false;
        }

        function initLocalizeText() {
            var modalDeleteTitleTextDOM = $('#modal-delete-title')
            var modalDeleteBodyTextDOM = $('#modal-delete-body')
            var buttonCancelTextDOM = $('#button-cancel')
            var buttonDeleteTextDOM = $('#button-delete')
            $scope.localizeText = {
                modalDeleteTitleText : modalDeleteTitleTextDOM.attr('data-modal-delete-title'),
                modalDeleteBodyText : modalDeleteBodyTextDOM.attr('data-modal-delete-body'),
                buttonCancelText : buttonCancelTextDOM.attr('data-button-cancel'),
                buttonDeleteText : buttonDeleteTextDOM.attr('data-button-delete')
            };
            modalDeleteTitleTextDOM.remove();
            modalDeleteBodyTextDOM.remove();
            buttonCancelTextDOM.remove();
            buttonDeleteTextDOM.remove();
        }

        function calculateAvailableQuantity() {
            if($scope.roomEquipments && $scope.roomEquipments.length > 0) {
                var useQuantity = 0;
                $scope.roomEquipments.forEach(function(value, index) {
                    useQuantity += value.taken_quantity;
                });
                if(totalQuantityShowDOM.getNumber() > useQuantity) { 
                    $scope.availableQuantity = totalQuantityShowDOM.getNumber() - useQuantity;
                } else {
                     $scope.availableQuantity = 0;
                }  
            } else {
                 $scope.availableQuantity = totalQuantityShowDOM.getNumber();
            }
             availableQuantityShowDOM.set($scope.availableQuantity);
             $scope.$apply();
        }

        function initNewRoomEquipment() {
            addRoomEquipmentDatetimeGroupDOM.find('input').val('');
            $scope.newRoomEquipment = {
                room_id: '',
                taken_quantity_show: '',
                taken_quantity: '',
                taken_date: ''
            };
            roomEquipmentTakenQualityDOM.set(0);
            $scope.selectedNewRoomEquipment = null;
        }

        function initDatetimePicker() {
            addRoomEquipmentDatetimeGroupDOM.datetimepicker({
                format: 'DD/MM/YYYY HH:mm:ss',
                showTodayButton: true,
                allowInputToggle: true
            });
        }

        function initNumericFields() {
            totalQuantityShowDOM = new AutoNumeric(totalQuantityShowDOM[0], {
                decimalPlaces: 0,
                minimumValue: 0,
                modifyValueOnWheel: false,
            });
            availableQuantityShowDOM = new AutoNumeric(availableQuantityShowDOM[0], {
                decimalPlaces: 0,
                minimumValue: 0,
                modifyValueOnWheel: false,
            });
            roomEquipmentTakenQualityDOM = new AutoNumeric(roomEquipmentTakenQualityDOM[0], {
                decimalPlaces: 0,
                minimumValue: 0,
                modifyValueOnWheel: false,
            }); 
            availableQuantityShowDOM.set($scope.availableQuantity);
            $(totalQuantityShowDOM.domElement).on('keyup', function() {
                calculateAvailableQuantity();
            });
        }

        //Initial Controller
        $scope.init();

        angular.element(document).ready(function() {
            initDatetimePicker();
            initNumericFields();
        });

        editEquipmentFormDOM.on('submit', function() {
            $scope.totalQuantity = totalQuantityShowDOM.getNumber();
            $scope.$apply();
        });
    }
]);