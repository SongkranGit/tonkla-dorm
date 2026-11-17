<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{__('shared.title',[], $lang)}}</title>

    {{--Style--}}
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/admin-lte.css') }}" rel="stylesheet">
    <link href="{{ mix('css/print-table-1.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ mix('css/print-table-2.css') }}" rel="stylesheet" type="text/css">

    <style>
        @page {
            size: auto;   /* auto is the current printer page size */
            margin: 5mm;  /* this affects the margin in the printer settings */
        }
    </style>

</head>

<body>
<?php
$priceBeforeVat = 0;
$totalRowPerpage = 0;
?>
<div id="print-container">
    <div class="hide-print">
        <div class="pull-right">
            {{--  request for approve before print checkin and checkout--}}
            <a onClick="showPrint();" href="javascript:void(0)" class="btn btn-success btn-print">
                <i class="fa fa-print" aria-hidden="true"></i> {{__('shared.print_button',[], $lang)}}
            </a>
        </div>
    </div>
    {{------------------------------------------  SECTION 1 ---------------------------------------------------------}}
    <div class="print-receipt">
        <table class="print-table-1">
            <tbody>
            <tr>
                <th style="width: 75%">
                    <img src="{{ asset('images/tonkla-jukkum-logo.png') }}" class="logo" alt="Tonkla-Logo">
                </th>
                <th style="width: 25%" class="print-title">
                    @if($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee)
                        <div style="font-size: 18px">{{__('shared.room_is_use_custom_rental_fee_with_invoice',[], $lang)}}</div>
                    @else
                        <div style="font-size: 18px"> {{__('shared.print_title_receipt',[], $lang)}}</div>
                    @endif
                    <img src="{{ asset('images/tonkla-watermark.png') }}" class="watermark-1" alt="Tonkla-Watermark"
                         width="125px" height="110px">
                </th>
            </tr>
            </tbody>
        </table>

        <table class="print-table-2">
            <tr>
                <td style="width: 75%">
                    @if($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee)
                        <b>{{__('shared.print_jugkum_tax',[], $lang)}}</b>
                        <br>{{__('shared.print_address_jugkum_tax',[], $lang)}}
                        <br>{{__('shared.print_tel_jugkum',[], $lang)}}
                    @else
                        <b>{{__('shared.print_jugkum',[], $lang)}}</b>
                        <br>{{__('shared.print_address_jugkum',[], $lang)}}
                        <br>{{__('shared.print_tel_jugkum',[], $lang)}}
                    @endif
                </td>
                <td style="width: 25%">{{__('shared.print_no',[], $lang)}}<span class="print_receipt_number"></span></td>
            </tr>
            </tbody>
        </table>

        <table class="print-table-3">
            <tr>
                <td style="width: 50%">{{__('shared.print_name',[], $lang).' '.$currentCheckedInRoomStayHistroy->renter_firstname.' '.$currentCheckedInRoomStayHistroy->renter_lastname}}</td>
                <td style="width: 30%">
                    @if(\App\Helpers\RoomServiceHelper::isRoomTypeDaily($currentCheckedInRoomStayHistroy))
                        {{__('shared.print_paid_date',[], $lang) }} {{$currentCheckedInRoomStayHistroy->check_in}}
                        - {{$currentCheckedInRoomStayHistroy->check_out}}
                    @else
                        {{__('shared.print_paid_date',[], $lang).(  Carbon\Carbon::now()->format('d/m/Y') )}}
                    @endif
                </td>
                <td style="width: 20%">{{__('shared.print_room',[], $lang).' '.$currentCheckedInRoomStayHistroy->room->room_no}}</td>
            </tr>
        </table>

        <table class="print-table-4">
            <tbody>
            <tr>
                <td style="width: 60%;">{{__('shared.print_list',[], $lang)}}</td>
                <td style="width: 20%;">{{__('shared.print_quantity',[], $lang)}}</td>
                <td style="width: 20%;">{{__('shared.print_price',[], $lang)}}</td>
            </tr>
            </tbody>
        </table>
        <table class="print-table-5">
            <tbody>
            @if(($currentCheckedInRoomStayHistroy->room->status != 'daily' && $currentCheckedInRoomStayHistroy->room->status != 'available'
                 || \App\Helpers\RoomServiceHelper::isRoomTypeDaily($currentCheckedInRoomStayHistroy)
                ))
                <tr>
                    <td style="width: 60%;">{{__('shared.print_room_fee',[], $lang)}}</td>
                    @if(\App\Helpers\RoomServiceHelper::isRoomTypeDaily($currentCheckedInRoomStayHistroy))
                        @if(isset($currentCheckedInRoomStayHistroy->check_out) && isset($currentCheckedInRoomStayHistroy->check_in ) && $currentCheckedInRoomStayHistroy->check_out != null && $currentCheckedInRoomStayHistroy->check_in != null)
                            <?php $totalDate = Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $currentCheckedInRoomStayHistroy->check_out)->startOfDay()->diffInDays(Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $currentCheckedInRoomStayHistroy->check_in)->startOfDay());
                            if ($totalDate == 0) $totalDate = 1;
                            ?>
                            <td style="width: 20%;"> {{ $totalDate}}  </td>
                            <td style="width: 20%;">
                                {{ isset($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ) && $currentCheckedInRoomStayHistroy->is_use_custom_rental_fee  ? number_format($currentCheckedInRoomStayHistroy->custom_rental_fee * $totalDate,2) : number_format($currentCheckedInRoomStayHistroy->rental_fee * $totalDate,2) }}
                            </td>
                        @else
                            <td style="width: 20%;"> 1</td>
                            <td style="width: 20%;">{{ isset($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ) && $currentCheckedInRoomStayHistroy->is_use_custom_rental_fee  ? number_format($currentCheckedInRoomStayHistroy->custom_rental_fee,2) : number_format($currentCheckedInRoomStayHistroy->rental_fee,2) }}</td>
                        @endif
                    @else
                        <td style="width: 20%;">1</td>
                        <td style="width: 20%;">
                            @if($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee )
                                {{\App\Helpers\RoomServiceHelper::getMonthlyRoomRate($currentCheckedInRoomStayHistroy , $settingService)}}
                            @else
                                {{number_format($currentCheckedInRoomStayHistroy->rental_fee,2) }}
                            @endif
                        </td>
                    @endif
                </tr>
            @endif
            @if( $currentCheckedInRoomStayHistroy->electric_meter_fee > 0)
                <tr>
                    <td style="width: 60%;">{{__('shared.print_room_electric_fee',[], $lang)}}</td>
                    <td style="width: 20%;">{{ $currentCheckedInRoomStayHistroy->electric_meter_unit }}</td>
                    <td style="width: 20%;">{{  number_format($currentCheckedInRoomStayHistroy->electric_meter_fee,2) }}</td>
                </tr>
                <?php $totalRowPerpage += 1; ?>
            @endif
            @if( $currentCheckedInRoomStayHistroy->water_meter_fee > 0)
                <tr>
                    <td style="width: 60%;">{{__('shared.print_room_water_fee',[], $lang)}}</td>
                    <td style="width: 20%;">{{ $currentCheckedInRoomStayHistroy->water_meter_unit }}</td>
                    <td style="width: 20%;">{{  number_format($currentCheckedInRoomStayHistroy->water_meter_fee,2) }}</td>
                </tr>
                <?php $totalRowPerpage += 1; ?>
            @endif

            {{-- Those cases can be print extras value --}}
            @if( sizeof($currentCheckedInRoomStayHistroy->roomUtilityHistory) > 0)
                @foreach($currentCheckedInRoomStayHistroy->roomUtilityHistory as $item)
                    @if($item->appSettingEloquentStorage->key != 'extra_2'
                        ||( $printAction != config('constants.print_checkout') && !$currentCheckedInRoomStayHistroy->is_use_custom_rental_fee)
                        ||( $printAction != config('constants.print_checkout') && $currentCheckedInRoomStayHistroy->is_use_custom_rental_fee && $totalPayTax == 0 ))
                        <tr>
                            <td style="width: 60%;">{{ $lang == 'en'? \App\Helpers\RoomServiceHelper::getExtraNameEng($item->appSettingEloquentStorage->key) : $item->appSettingEloquentStorage->extra_key }}</td>
                            <td style="width: 20%;">1</td>
                            <td style="width: 20%;">{{  number_format($item->appSettingEloquentStorage->value,2) }}</td>
                        </tr>
                        <?php $totalRowPerpage += 1; ?>
                    @endif
                @endforeach
            @endif

            @if( $currentCheckedInRoomStayHistroy->is_use_custom_rental_fee && $totalPayTax > 0)
                <tr>
                    <td style="width: 60%;">{{__('shared.print_vat',[], $lang)}}</td>
                    <td style="width: 20%;">1</td>
                    <td style="width: 20%;">{{ number_format($totalPayTax  ,2)}}</td>
                </tr>
            @endif

            </tbody>
        </table>

        <table class="print-table-6">
            <tbody>
            @if($currentCheckedInRoomStayHistroy->discount > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_discount',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentCheckedInRoomStayHistroy->discount ,2)}}</td>
                </tr>
                <?php $totalRowPerpage += 1; ?>
            @endif
            @if($printAction == config('constants.print_checkout') && $currentCheckedInRoomStayHistroy->deposit > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_deposit',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentCheckedInRoomStayHistroy->deposit ,2)}}</td>
                </tr>
                <?php $totalRowPerpage += 1; ?>
            @endif
            @if($printAction == config('constants.print_checkout') && $currentCheckedInRoomStayHistroy->deposit_other > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_deposit_other',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentCheckedInRoomStayHistroy->deposit_other ,2)}}</td>
                </tr>
                <?php $totalRowPerpage += 1; ?>
            @endif
            <tr style="background-color:white !important;">
                <td style="width: 80%;">{{__('shared.print_total_price',[], $lang)}}</td>
                <td style="width: 20%;">{{ number_format( $sumTotal ,2)}} </td>
            </tr>
            <?php $totalRowPerpage += 1; ?>
            </tbody>
        </table>

        <br>
        <table class="print-table-7">
            <tbody>
            <tr>
                <td style="width:50%" style="">{{__('shared.print_renter_1',[], $lang)}}</td>
                <td style="width:50%">{{__('shared.print_receiver_date_1',[], $lang)}}</td>
            </tr>
            <tr>
                <td>{{__('shared.print_receiver_1',[], $lang)}}</td>
            </tr>
            </tbody>
        </table>
    </div>
    {{------------------------------------------  SECTION 2 ---------------------------------------------------------}}
    <div class="print-invoice {{$totalRowPerpage > 10  ? 'pagebreak' : ''}}">
        <table class="print-table-1">
            <tbody>
            <tr>
                <th style="width: 75%">
                        <img src="{{ asset('images/tonkla-jukkum-logo.png') }}" class="logo" alt="Tonkla-Logo">
                </th>
                <th style="width: 25%" class="print-title">
                    @if($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee)
                        <div style="font-size: 18px">{{__('shared.room_is_use_custom_rental_fee_with_invoice',[], $lang)}}</div>
                    @else
                        @if(\App\Helpers\RoomServiceHelper::isRoomTypeDaily($currentCheckedInRoomStayHistroy))
                            <div style="font-size: 18px"> {{__('shared.print_title_receipt',[], $lang)}}</div>
                        @else
                            <div style="font-size: 18px"> {{__('shared.print_title_invoice',[], $lang)}}</div>
                        @endif
                    @endif
                    <img src="{{ asset('images/tonkla-watermark.png') }}" class="watermark-2" alt="Tonkla-Watermark"
                         width="125px" height="110px">
                </th>
            </tr>
            </tbody>
        </table>

        <table class="print-table-2">
            <tr>
                <td style="width: 75%">
                    @if($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee)
                        <b>{{__('shared.print_jugkum_tax',[], $lang)}}</b>
                        <br>{{__('shared.print_address_jugkum_tax',[], $lang)}}
                        <br>{{__('shared.print_tel_jugkum',[], $lang)}}
                    @else
                        <b>{{__('shared.print_jugkum',[], $lang)}}</b>
                        <br>{{__('shared.print_address_jugkum',[], $lang)}}
                        <br>{{__('shared.print_tel_jugkum',[], $lang)}}
                    @endif
                </td>
                <td style="width: 25%">{{__('shared.print_no',[], $lang)}}<span class="print_receipt_number"></span></td>
            </tr>
            </tbody>
        </table>

        <table class="print-table-3">
            <tr>
                <td style="width: 50%">{{__('shared.print_name',[], $lang).' '.$currentCheckedInRoomStayHistroy->renter_firstname.' '.$currentCheckedInRoomStayHistroy->renter_lastname}}</td>
                <td style="width: 30%">
                    @if(\App\Helpers\RoomServiceHelper::isRoomTypeDaily($currentCheckedInRoomStayHistroy))
                        {{__('shared.print_paid_date',[], $lang) }} {{$currentCheckedInRoomStayHistroy->check_in}}
                        - {{$currentCheckedInRoomStayHistroy->check_out}}
                    @else
                        {{__('shared.print_paid_date',[], $lang).(  Carbon\Carbon::now()->format('d/m/Y') )}}
                    @endif
                </td>
                <td style="width: 20%">{{__('shared.print_room',[], $lang).' '.$currentCheckedInRoomStayHistroy->room->room_no}}</td>
            </tr>
        </table>

        <table class="print-table-4">
            <tbody>
            <tr>
                <td style="width: 60%;">{{__('shared.print_list',[], $lang)}}</td>
                <td style="width: 20%;">{{__('shared.print_quantity',[], $lang)}}</td>
                <td style="width: 20%;">{{__('shared.print_price',[], $lang)}}</td>
            </tr>
            </tbody>
        </table>

        <table class="print-table-5">
            <tbody>
            @if(($currentCheckedInRoomStayHistroy->room->status != 'daily' && $currentCheckedInRoomStayHistroy->room->status != 'available'
                 || \App\Helpers\RoomServiceHelper::isRoomTypeDaily($currentCheckedInRoomStayHistroy)
                ))
                <tr>
                    <td style="width: 60%;">{{__('shared.print_room_fee',[], $lang)}}</td>
                    @if(\App\Helpers\RoomServiceHelper::isRoomTypeDaily($currentCheckedInRoomStayHistroy))
                        @if(isset($currentCheckedInRoomStayHistroy->check_out) && isset($currentCheckedInRoomStayHistroy->check_in ) && $currentCheckedInRoomStayHistroy->check_out != null && $currentCheckedInRoomStayHistroy->check_in != null)
                            <?php $totalDate = \App\Helpers\RoomServiceHelper::calculateCheckinDates($currentCheckedInRoomStayHistroy)?>
                            <td style="width: 20%;"> {{ $totalDate}}  </td>
                            <td style="width: 20%;">
                                {{ isset($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ) && $currentCheckedInRoomStayHistroy->is_use_custom_rental_fee  ? number_format($currentCheckedInRoomStayHistroy->custom_rental_fee * $totalDate,2) : number_format($currentCheckedInRoomStayHistroy->rental_fee * $totalDate,2) }}
                            </td>
                        @else
                            <td style="width: 20%;"> 1</td>
                            <td style="width: 20%;">{{ isset($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ) && $currentCheckedInRoomStayHistroy->is_use_custom_rental_fee  ? number_format($currentCheckedInRoomStayHistroy->custom_rental_fee,2) : number_format($currentCheckedInRoomStayHistroy->rental_fee,2) }}</td>
                        @endif
                    @else
                        <td style="width: 20%;">1</td>
                        <td style="width: 20%;">
                            @if($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee )
                                {{\App\Helpers\RoomServiceHelper::getMonthlyRoomRate($currentCheckedInRoomStayHistroy , $settingService)}}
                            @else
                                {{number_format($currentCheckedInRoomStayHistroy->rental_fee,2) }}
                            @endif
                        </td>
                    @endif
                </tr>
            @endif
            @if($currentCheckedInRoomStayHistroy->electric_meter_fee > 0)
                <tr>
                    <td style="width: 60%;">{{__('shared.print_room_electric_fee',[], $lang)}}</td>
                    <td style="width: 20%;">{{ $currentCheckedInRoomStayHistroy->electric_meter_unit }}</td>
                    <td style="width: 20%;">{{  number_format($currentCheckedInRoomStayHistroy->electric_meter_fee,2) }}</td>
                </tr>
            @endif
            @if( $currentCheckedInRoomStayHistroy->water_meter_fee > 0)
                <tr>
                    <td style="width: 60%;">{{__('shared.print_room_water_fee',[], $lang)}}</td>
                    <td style="width: 20%;">{{ $currentCheckedInRoomStayHistroy->water_meter_unit }}</td>
                    <td style="width: 20%;">{{  number_format($currentCheckedInRoomStayHistroy->water_meter_fee,2) }}</td>
                </tr>
            @endif
            @if( sizeof($currentCheckedInRoomStayHistroy->roomUtilityHistory) > 0)
                @foreach($currentCheckedInRoomStayHistroy->roomUtilityHistory as $item)
                    @if($item->appSettingEloquentStorage->key != 'extra_2'
                        ||( $printAction != config('constants.print_checkout') && !$currentCheckedInRoomStayHistroy->is_use_custom_rental_fee)
                        ||( $printAction != config('constants.print_checkout') && $currentCheckedInRoomStayHistroy->is_use_custom_rental_fee && $totalPayTax == 0 ))
                        <tr>
                            <td style="width: 60%;">{{ $lang == 'en'? \App\Helpers\RoomServiceHelper::getExtraNameEng($item->appSettingEloquentStorage->key) : $item->appSettingEloquentStorage->extra_key }}</td>
                            <td style="width: 20%;">1</td>
                            <td style="width: 20%;">{{  number_format($item->appSettingEloquentStorage->value,2) }}</td>
                        </tr>
                    @endif
                @endforeach
            @endif

            @if($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee && $totalPayTax > 0)
                <tr>
                    <td style="width: 60%;">{{__('shared.print_vat',[], $lang)}}</td>
                    <td style="width: 20%;">1</td>
                    <td style="width: 20%;">{{ number_format($totalPayTax  ,2)}}</td>
                </tr>
            @endif

            </tbody>
        </table>

        <table class="print-table-6">
            <tbody>
            @if($currentCheckedInRoomStayHistroy->discount > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_discount',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentCheckedInRoomStayHistroy->discount ,2)}}</td>
                </tr>
                <?php $totalRowPerpage += 1; ?>
            @endif

            <tr style="background-color:white !important;">
                <td style="width: 80%;">{{__('shared.print_total_price',[], $lang)}}</td>
                <td style="width: 20%;">{{ number_format( $sumTotal ,2)}} </td>
            </tr>
            </tbody>
        </table>
        @if($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee  || \App\Helpers\RoomServiceHelper::isRoomTypeDaily($currentCheckedInRoomStayHistroy))
            <br>
            <table class="print-table-7">
                <tbody>
                <tr>
                    <td style="width:50%" style="">{{__('shared.print_renter_1',[], $lang)}}</td>
                    <td style="width:50%">{{__('shared.print_receiver_date_1',[], $lang)}}</td>
                </tr>
                <tr>
                    <td>{{__('shared.print_receiver_1',[], $lang)}}</td>
                </tr>
                </tbody>
            </table>
        @else
            ** กรุณาชำระค่าห้องภายในวันที่ 5 ของทุกเดือน
            <br/>
            <table class="print-table-7">
                <tbody>
                <tr>
                    <td style="width:50%">{{__('shared.print_receiver_2',[], $lang)}}</td>
                    <td style="width:50%">{{__('shared.print_approval',[], $lang)}}</td>
                </tr>
                </tbody>
            </table>
        @endif
    </div>
</div>

{{--Script--}}
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ mix('js/libraries/library.js') }}"></script>
<script>
  function showPrint() {
    var token = $('meta[name="csrf-token"]').attr('content');
    var updatePrintUrl = '{{url("room/print/")}}'
    var printStatus = '{{( $printAction == config('constants.print') ? "print" : ($printAction == config('constants.print_checkout') ? "print_checkout": "print_checkin" ) )}}'
    window.print();
    $.ajax({
      url: updatePrintUrl,
      type: 'POST',
      data: {
        _method: 'POST',
        _token: token,
        room_id: '{{$currentCheckedInRoomStayHistroy->room->id}}',
        status: printStatus
      },
      success: function (result) {
        setTimeout(function () {
          parent.$('#print-preview-modal').modal('hide');
          parent.$('#check-out-room-modal').modal('hide');
        }, 100);
        clearStatusApprove(printStatus);
      }
    });
  }

  function clearStatusApprove(printStatus) {
    var token = $('meta[name="csrf-token"]').attr('content');
    var targetUrl = '{{url("admin/approval/reset-status")}}' + '/' + '{{$currentCheckedInRoomStayHistroy->room->id}}';
    var checking_type = (printStatus === 'print' || printStatus === 'print_checkin') ? 'check_in' : 'check_out';
    $.ajax({
      url: targetUrl,
      type: 'POST',
      data: {
        _token: token,
        checking_type: checking_type
      },
      success: function (result) {
        location.reload();
      }
    });
  }

  $(document).ready(function () {
    var printContainer = $('#print-container');
    if (/firefox/.test(navigator.userAgent.toLowerCase())) {
      printContainer.addClass("firefox-container");
    } else if (/msie/.test(navigator.userAgent.toLowerCase()) || /edge/.test(navigator.userAgent.toLowerCase())) {
      printContainer.addClass("edge-container");
    } else {
      printContainer.addClass("chrome-container");
    }
  });
</script>
</body>

</html>
