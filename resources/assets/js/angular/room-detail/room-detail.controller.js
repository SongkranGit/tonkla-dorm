app.controller('RoomDetailController', ['$scope', '$timeout',
    function ($scope, $timeout) {
        //Normal DOM
        var statusSpanDOM = $('#status-span');
        var keycardsSpanDOM = $('#keycards-span');
        var utitlitiesSpanDOM = $('#utitlities-span');
        var roomUtitlitiesSpanDOM = $('#room-utitlities-span');
        var editRoomFormDOM = $('#edit-room-form');
        var currencyDOM = $('#currency-span');
        var moveRoomModalDOM = $('#move-room-modal');
        var isAdminDOM = $('#is-admin-span');
        var isUseCustomRentalFeeDOM = $('#is-use-custom-rental-fee');
        var printPreviewModal = $('#print-preview-modal');
        var checkoutModal = $('#check-out-room-modal');
        var languageSpanDOM = $('#language-span');

        //Datetime Pickers
        var checkInDatetimeGroupDOM = $('#check-in-datetime-group');
        var checkOutDatetimeGroupDOM = $('#check-out-datetime-group');
        var addKeycardDatetimeGroupDOM = $('#add-keycard-datetime-group');

        //Numberic Fields
        var depositDOM = $('#deposit');
        var depositKeycardDOM = $('#deposit_keycard');
        var depositOtherDOM = $('#deposit_other');
        var rentalFeeDOM = $('#rental_fee');
        var customRentalFeeDOM = $('#custom_rental_fee');
        var discountDOM = $('#discount');

        var electricMeterStartDOM = $('#electric_meter_start');
        var electricMeterEndDOM = $('#electric_meter_end');
        var electricMeterUnitDOM = $('#electric_meter_unit');
        var electricMeterFeeDOM = $('#electric_meter_fee');

        var waterMeterStartDOM = $('#water_meter_start');
        var waterMeterEndDOM = $('#water_meter_end');
        var waterMeterUnitDOM = $('#water_meter_unit');
        var waterMeterFeeDOM = $('#water_meter_fee');

        var totalPriceDOM = $('#total_price');
        var roomMaintenanceCostDOM = $('#room_maintenance_cost');
        var branchDOM = $('#branch');
        var branchHotel = 4;
        var currentFurniturePrice = 0;

        $scope.init = function () {
            $scope.isAddNewKeycard = false;
            $scope.selectedExtra = [];
            $scope.devaredKeycard = [];
            $scope.defaultSelectedExtra = [];
            $scope.devaredSelectedExtra = [];
            $scope.status = statusSpanDOM.attr('data-status');
            statusSpanDOM.remove();
            $scope.keycards = JSON.parse(keycardsSpanDOM.attr('data-keycards'));
            keycardsSpanDOM.remove();
            $scope.utitlities = JSON.parse(utitlitiesSpanDOM.attr('data-utitlities'));
            utitlitiesSpanDOM.remove();
            $scope.roomUtitlities = JSON.parse(roomUtitlitiesSpanDOM.attr('data-room-utitlities'));
            roomUtitlitiesSpanDOM.remove();
            $scope.utitlities.currency = currencyDOM.attr('data-currency');
            currencyDOM.remove();
            $scope.isAdmin = isAdminDOM.attr('data-is-admin') == 1 ? true : false;
            isAdminDOM.remove();
            $scope.isUseCustomRentalFee = isUseCustomRentalFeeDOM.attr('data-is-use-custom-rental-fee') == 1 ? true : false;
            isUseCustomRentalFeeDOM.remove();
            $scope.language = languageSpanDOM.attr('data-language');
            if (!$scope.language) {
                $scope.language = 'th';
            }
            $scope.branch = branchDOM.attr('data-branch');
        }

        $scope.onLanguageChange = function () {
            window.location.href = window.location.origin + window.location.pathname + '?lang=' + $scope.language;
        }

        $scope.showMoveRoomModal = function (event) {
            event.preventDefault();
            moveRoomModalDOM.modal('show');
        }

        $scope.onStatusChange = function () {
            if ($scope.status == 'daily') {
                rentalFeeDOM.set($scope.utitlities.roomDaily);
            } else if ($scope.status == 'daily_2') {
                rentalFeeDOM.set($scope.utitlities.roomDaily2);
            } else if ($scope.status == 'daily_3') {
                rentalFeeDOM.set($scope.utitlities.roomDaily3);
            } else if ($scope.status == 'monthly_air') {
                rentalFeeDOM.set($scope.utitlities.roomMonthlyAir);
            } else if ($scope.status == 'monthly_fan') {
                rentalFeeDOM.set($scope.utitlities.roomMonthlyFan);
            } else if ($scope.status == 'monthly_air_2') {
                rentalFeeDOM.set($scope.utitlities.roomMonthlyAir2);
            } else if ($scope.status == 'monthly_fan_2') {
                rentalFeeDOM.set($scope.utitlities.roomMonthlyFan2);
            } else if ($scope.status == 'monthly_house') {
                rentalFeeDOM.set($scope.utitlities.roomMonthlyHouse);
            } else {
                rentalFeeDOM.set(0);
            }
            $scope.calculate();
        }


        $scope.onAddKeyCardBtnClick = function () {
            $scope.isAddNewKeycard = true;
        }

        $scope.onSubmitKeyCardBtnClick = function () {
            var takenDate = addKeycardDatetimeGroupDOM.find('input').val();
            if ($scope.newKeycard && $scope.newKeycard.key_card_no && takenDate) {
                $scope.newKeycard.taken_date = takenDate;
                $scope.isAddNewKeycard = false;
                $scope.keycards.push($scope.newKeycard);
                initNewKeycard();
            }
        }

        $scope.onCancelSubmitKeyCardBtnClick = function () {
            $scope.isAddNewKeycard = false;
            initNewKeycard();
        }


        $scope.onDevareKeyCardBtnClick = function (index) {
            if ($scope.keycards[index].id) {
                var devareKeycard = angular.copy($scope.keycards[index]);
                $scope.devaredKeycard.push(devareKeycard);
            }
            $scope.keycards.splice(index, 1);
        }

        $scope.calculate = function () { 
            if ($scope.isRoomTypeDaily($scope.status)) {
                var rentalFee = $scope.isUseCustomRentalFee ? customRentalFeeDOM.getNumber() : rentalFeeDOM.getNumber();
                if (rentalFee) {
                    var total = 0;
                    var checkInDate = checkInDatetimeGroupDOM.data('DateTimePicker').date();
                    var checkOutDate = checkOutDatetimeGroupDOM.data('DateTimePicker').date();
                    var totalDate = 1;
                    if (checkInDate != null && checkOutDate != null) {
                        totalDate = checkOutDate.startOf('day').diff(checkInDate.startOf('day'), 'days');
                        if (totalDate == 0) {
                            totalDate = 1;
                        }
                    }
                    total = rentalFee * totalDate;
                    // Extra
                    if ($scope.branch == branchHotel) {
                        total += calculateExtraBranchHotel();
                    }
                    totalPriceDOM.set(total);
                }
            } else if ($scope.status == 'monthly_air' || $scope.status == 'monthly_fan' || $scope.status == 'monthly_air_2' || $scope.status == 'monthly_fan_2' || $scope.status == 'monthly_house') {
                //Calculate Electric
                electricMeterFeeDOM.set(0);
                var electricMeterEnd = electricMeterEndDOM.getNumber();
                var electricMeterStart = electricMeterStartDOM.getNumber();
                if (electricMeterEnd >= electricMeterStart) {
                    var electricMeterUnit = electricMeterEnd - electricMeterStart;
                    electricMeterUnitDOM.set(electricMeterUnit);
                    electricMeterFeeDOM.set(electricMeterUnit * $scope.utitlities.electricUnit);
                }
                //Calculate water
                waterMeterFeeDOM.set(0);
                var waterMeterEnd = waterMeterEndDOM.getNumber();
                var waterMeterStart = waterMeterStartDOM.getNumber();
                if (waterMeterEnd >= waterMeterStart) {
                    var waterMeterUnit = waterMeterEnd - waterMeterStart;
                    waterMeterUnitDOM.set(waterMeterUnit);
                    waterMeterFeeDOM.set(waterMeterUnit * $scope.utitlities.waterUnit);
                }
                //Calculate Room
                var rentalFee = $scope.isUseCustomRentalFee ? customRentalFeeDOM.getNumber() : rentalFeeDOM.getNumber();
                if (rentalFee) {
                    var total = 0;
                    var totalExtra = 0;
                    $scope.selectedExtra.forEach(function (item, index) {
                        if (item.is_use) {
                            var itemVal = parseFloat(item.value)
                            if ($scope.isUseCustomRentalFee && item.is_use_calculate_vat) {
                                // costNotCalculateTax += itemVal;
                            } else {
                                totalExtra += itemVal;
                            }
                        }
                    });
                    total += rentalFee;
                    total += totalExtra + waterMeterFeeDOM.getNumber() + electricMeterFeeDOM.getNumber() ;
                    totalPriceDOM.set(total);
                }
            }
        };

        $scope.extraCheckBoxChange = function (extra, index) {
            if ($scope.selectedExtra && !$scope.selectedExtra[index].is_use) {
                if ($scope.defaultSelectedExtra && $scope.defaultSelectedExtra.length > 0) {
                    $scope.defaultSelectedExtra.forEach(function (value, defaultIndex) {
                        if (value && value.setting_id == $scope.selectedExtra[index].setting_id && value.id) {
                            var deleteItem = angular.copy($scope.selectedExtra[index]);
                            $scope.devaredSelectedExtra.push(deleteItem);
                        }
                    });
                }
            } else {
                $scope.selectedExtra[index].is_use_calculate_vat = extra.is_use_calculate_vat == 1 ? true : false;
                $scope.selectedExtra[index].setting_id = extra.id;
                $scope.selectedExtra[index].value = extra.value;
                if ($scope.defaultSelectedExtra && $scope.defaultSelectedExtra.length > 0) {
                    $scope.defaultSelectedExtra.forEach(function (value, defaultIndex) {
                        if (value && value.setting_id == $scope.selectedExtra[index].setting_id && value.id) {
                            $scope.selectedExtra[index].id = value.id;
                        }
                    });
                    if ($scope.devaredSelectedExtra && $scope.devaredSelectedExtra.length > 0) {
                        $scope.devaredSelectedExtra.forEach(function (deleteValue, deleteIndex) {
                            if (deleteValue && deleteValue.setting_id == $scope.selectedExtra[index].setting_id && $scope.selectedExtra[index].id) {
                                $scope.devaredSelectedExtra.splice(deleteIndex, 1);
                            }
                        });
                    }
                }
            }

            $scope.calculate();
        }

        $scope.isRoomTypeDaily = function(roomStatus){
            if(roomStatus == 'daily' || roomStatus == 'daily_2' || roomStatus == 'daily_3'){
                return true;
            }
            return false;
        }

        $scope.getExtraValue = function (item, bth) {
            var value = parseFloat(item);
            return value.toFixed(2) + ' ' + $scope.utitlities.currency;
        }

        $scope.quickCheckOutBtnClick = function () {
            // checkoutModal 
            var bodyWidth = window.innerWidth
                || document.documentElement.clientWidth
                || document.body.clientWidth;

            var checkoutModalPrintIframe = checkoutModal.find('#check-out-print-preview-iframe');

            if (bodyWidth < 992) {
                checkoutModalPrintIframe.attr('width', bodyWidth - 200)
                console.log('checkoutModalPrintIframe', $(checkoutModalPrintIframe).width())
            }
            var modalWith = $(checkoutModalPrintIframe).width();
            $(checkoutModalPrintIframe).parents('.modal-dialog').css('width', modalWith + 100);
            $scope.timing = $timeout(function () {
                checkoutModal.modal('show');
            }, 700);

            checkoutModal.find('.room-check-out-btn').on('click', function () {
                $scope.status = 'available';
                $timeout(function () {
                    editRoomFormDOM.submit();
                }, 10);
            });
        }

        checkoutModal.on('shown.bs.modal', function () {
            //prevent scrolling hide on chrome
            if (/chrome/.test(navigator.userAgent.toLowerCase())) {
                $('#check-out-print-preview-iframe').css('height', 701);
                $timeout(function () {
                    $('#check-out-print-preview-iframe').css('height', 700);
                }, 100);
            }
        });


        $scope.isUseCustomRentalFeeChange = function () {
            if ($scope.isUseCustomRentalFee && customRentalFeeDOM.getNumber() <= 0) {
                customRentalFeeDOM.set(rentalFeeDOM.getNumber());
            }
            $scope.calculate();
        }

        $scope.showPrintRoomModal = function (currentCheckedInRoomStayHistroyId, isNormal) {
            if (currentCheckedInRoomStayHistroyId > 0) {
                var bodyWidth = window.innerWidth
                    || document.documentElement.clientWidth
                    || document.body.clientWidth;

                var printIframe = printPreviewModal.find('#print-preview-iframe');
                var printCheckInIframe = printPreviewModal.find('#check-in-print-preview-iframe');
                if (isNormal) {
                    printIframe.show();
                    printCheckInIframe.hide();
                } else {
                    printIframe.hide();
                    printCheckInIframe.show();
                }
                if (bodyWidth < 992) {
                    printIframe.attr('width', bodyWidth - 200)
                    printCheckInIframe.attr('width', bodyWidth - 200)
                }

                $scope.timing = $timeout(function () {
                    printPreviewModal.modal('show');
                }, 700);
            }
        }

        printPreviewModal.on('shown.bs.modal', function () {
            //prevent scrolling hide on chrome
            if (/chrome/.test(navigator.userAgent.toLowerCase())) {
                $('#print-preview-iframe').css('height', 701);
                $timeout(function () {
                    $('#print-preview-iframe').css('height', 700);
                }, 100);
            }
        });

        function initNewKeycard() {
            addKeycardDatetimeGroupDOM.find('input').val('');
            $scope.newKeycard = {
                key_card_no: '',
                taken_date: ''
            }
        }

        function initDatetimePicker() {
            checkInDatetimeGroupDOM.datetimepicker({
                format: 'DD/MM/YYYY HH:mm:ss',
                allowInputToggle: true
            });
            checkOutDatetimeGroupDOM.datetimepicker({
                format: 'DD/MM/YYYY HH:mm:ss',
                allowInputToggle: true
            });
            checkInDatetimeGroupDOM.on('dp.change', function () {
                $scope.calculate();
            });
            checkOutDatetimeGroupDOM.on('dp.change', function () {
                $scope.calculate();
            });
            addKeycardDatetimeGroupDOM.datetimepicker({
                format: 'DD/MM/YYYY HH:mm:ss',
                showTodayButton: true,
                allowInputToggle: true
            });
        }

        function initNumericFields() {
            depositDOM = new AutoNumeric(depositDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false,
            });
            depositKeycardDOM = new AutoNumeric(depositKeycardDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false,
            });
            depositOtherDOM = new AutoNumeric(depositOtherDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false,
            });
            rentalFeeDOM = new AutoNumeric(rentalFeeDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false
            });

            customRentalFeeDOM = new AutoNumeric(customRentalFeeDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false
            });
            discountDOM = new AutoNumeric(discountDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false
            });

            electricMeterStartDOM = new AutoNumeric(electricMeterStartDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false
            });
            electricMeterEndDOM = new AutoNumeric(electricMeterEndDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false
            });
            electricMeterUnitDOM = new AutoNumeric(electricMeterUnitDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false
            });
            electricMeterFeeDOM = new AutoNumeric(electricMeterFeeDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false
            });

            waterMeterStartDOM = new AutoNumeric(waterMeterStartDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false
            });
            waterMeterEndDOM = new AutoNumeric(waterMeterEndDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false
            });
            waterMeterUnitDOM = new AutoNumeric(waterMeterUnitDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false
            });
            waterMeterFeeDOM = new AutoNumeric(waterMeterFeeDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false
            });

            totalPriceDOM = new AutoNumeric(totalPriceDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false
            });

            roomMaintenanceCostDOM = new AutoNumeric(roomMaintenanceCostDOM[0], {
                minimumValue: 0,
                modifyValueOnWheel: false
            });
        }

        function bindNumbericFields() {
            $([depositDOM.domElement,
                rentalFeeDOM.domElement,
                customRentalFeeDOM.domElement,
                discountDOM.domElement,
                electricMeterStartDOM.domElement,
                electricMeterEndDOM.domElement,
                waterMeterStartDOM.domElement,
                waterMeterEndDOM.domElement,
                roomMaintenanceCostDOM.domElement
            ]).on('keyup', function () {
                $scope.calculate();
            });
        }

        function initExtraFields() {
            if ($scope.roomUtitlities && $scope.roomUtitlities.length > 0) {
                $scope.utitlities.extra.forEach(function (extra, indexExtra) {
                    $scope.roomUtitlities.forEach(function (item, index) {
                        if (extra.id == item.setting_id) {
                            $scope.selectedExtra[indexExtra] = {
                                id: item.id,
                                setting_id: item.setting_id,
                                is_use: item.is_use,
                                is_use_calculate_vat: extra.is_use_calculate_vat,
                                value: extra.value
                            };

                        }
                    });
                });
                $scope.defaultSelectedExtra = angular.copy($scope.selectedExtra);
                $scope.$apply();
            }
        }

        function calculateExtraBranchHotel() {
            var totalExtra = 0;
            if ($scope.branch == branchHotel) { // case
                $scope.selectedExtra.forEach(function (item, index) {
                    if (item.is_use) {
                        var itemVal = parseFloat(item.value)
                        totalExtra += itemVal;
                    }
                });
            }
            return totalExtra;
        }

        function validate() {
            var checkInDate = checkInDatetimeGroupDOM.data('DateTimePicker').date();
            if (checkInDate == null) {
                alert('กรุณากรอกวันที่เข้าพัก')
                return false;
            }

            if ($scope.isRoomTypeDaily($scope.status)) {
                var checkoutDate = checkOutDatetimeGroupDOM.data('DateTimePicker').date();
                if (checkoutDate == null) {
                    alert('กรุณากรอกวันที่ออกห้องพัก')
                    return false;
                }
            }

            return true;
        }

        // Initial Controller
        $scope.init();
        angular.element(document).ready(function () {
            initDatetimePicker();
            initNumericFields();
            bindNumbericFields();
            initExtraFields();
            $scope.onStatusChange();
            $timeout(function () {
                $("#submitBtn").removeAttr("disabled");
            }, 100);
            console.log('ready');
        });

        editRoomFormDOM.on('submit', function () {
            if (!validate()) {
                return false;
            }

            $("#submitBtn").attr("disabled", "disabled");
            $scope.deposit = depositDOM.get();
            $scope.deposit_keycard = depositKeycardDOM.get();
            $scope.deposit_other = depositOtherDOM.get();
            $scope.rental_fee = rentalFeeDOM.get();
            $scope.custom_rental_fee = customRentalFeeDOM.get();
            $scope.discount = discountDOM.get();

            $scope.electric_meter_start = electricMeterStartDOM.get();
            $scope.electric_meter_end = electricMeterEndDOM.get();

            $scope.water_meter_start = waterMeterStartDOM.get();
            $scope.water_meter_end = waterMeterEndDOM.get();
            $scope.room_maintenance_cost = roomMaintenanceCostDOM.get();

            $scope.total_price = totalPriceDOM.get();

            $scope.$apply();

        });
    }
]);