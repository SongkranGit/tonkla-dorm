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

<body style="margin: 10mm 5mm 10mm 5mm">
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
                    <img src="{{ asset('images/tonkla-hotel-logo.png') }}" class="logo" alt="Tonkla-Logo">
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
                        <b>{{__('shared.print_hotel',[], $lang)}}</b>
                        <br> {{__('shared.print_address_hotel_for_tax',[], $lang)}}
                        <br> {{__('shared.print_tel_hotel_for_tax',[], $lang)}}
                    @else
                        {{__('shared.print_address_hotel',[], $lang)}}
                        <br>{{__('shared.print_tel_hotel',[], $lang)}}
                @endif
                <td style="width: 25%">{{__('shared.print_no',[], $lang)}}<span class="print_receipt_number"></span></td>
            </tr>
            </tbody>
        </table>

        <table class="print-table-3">
            <tr>
                <td style="width: 50%">{{__('shared.print_name',[], $lang).' '.$currentCheckedInRoomStayHistroy->renter_firstname.' '.$currentCheckedInRoomStayHistroy->renter_lastname}}</td>
                <td style="width: 30%">{{__('shared.print_paid_date',[], $lang) }} {{$currentCheckedInRoomStayHistroy->check_in}}
                    - {{$currentCheckedInRoomStayHistroy->check_out}}</td>
                <td style="width: 20% ; text-align: center">{{__('shared.print_room',[], $lang).' '.$currentCheckedInRoomStayHistroy->room->room_no}}</td>
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
            @if($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee )
                <tr>
                    <td style="width: 60%;">{{__('shared.print_room_fee',[], $lang)}}</td>
                    @if(isset($currentCheckedInRoomStayHistroy->check_out) && isset($currentCheckedInRoomStayHistroy->check_in ) && $currentCheckedInRoomStayHistroy->check_out != null && $currentCheckedInRoomStayHistroy->check_in != null)
                        <?php $totalDate = \App\Helpers\RoomServiceHelper::calculateCheckinDates($currentCheckedInRoomStayHistroy)?>
                        <td style="width: 20%;"> {{ $totalDate}}  </td>
                        <td style="width: 20%;">
                            {{number_format($currentCheckedInRoomStayHistroy->custom_rental_fee * $totalDate,2) }}
                        </td>
                    @else
                        <td style="width: 20%;"> 1</td>
                        <td style="width: 20%;">{{ number_format($currentCheckedInRoomStayHistroy->custom_rental_fee,2) }}</td>
                    @endif
                </tr>
            @endif
            </tbody>
        </table>

        <table class="print-table-6">
            <tbody>
            @if($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_tax',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($totalPayTax  ,2)}}</td>
                </tr>
                <?php $totalRowPerpage += 1; ?>
            @endif
            @if( $currentCheckedInRoomStayHistroy->discount > 0 )
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_discount',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentCheckedInRoomStayHistroy->discount ,2)}}</td>
                </tr>
                <?php $totalRowPerpage += 1; ?>
            @endif
            @if(!$currentCheckedInRoomStayHistroy->is_use_custom_rental_fee)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_deposit_keycard',[], $lang)}}</td>
                    <td style="width: 20%;">- {{ number_format($currentCheckedInRoomStayHistroy->deposit_keycard ,2)}}</td>
                </tr>
                <?php $totalRowPerpage += 1; ?>
            @endif

            <tr style="background-color:white !important;">
                <td style="width: 80%;">{{__('shared.print_total_price',[], $lang)}}</td>
                @if($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee)
                    <td style="width: 20%;">{{\App\Helpers\RoomServiceHelper::calculateTotalWithTaxForHotelBranch($currentCheckedInRoomStayHistroy , $totalPayTax)}}  </td>
                @else
                    <td style="width: 20%;">{{\App\Helpers\RoomServiceHelper::calculateTotalForHotelBranch($currentCheckedInRoomStayHistroy)}}  </td>
                @endif
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
                    <img src="{{ asset('images/tonkla-hotel-logo.png') }}" class="logo" alt="Tonkla-Logo">
                </th>
                <th style="width: 25%" class="print-title">
                    @if($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee)
                        <div style="font-size: 18px">{{__('shared.room_is_use_custom_rental_fee_with_invoice',[], $lang)}}</div>
                    @else
                        <div style="font-size: 18px"> {{__('shared.print_title_receipt',[], $lang)}}</div>
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
                        <b>{{__('shared.print_hotel',[], $lang)}}</b>
                        <br> {{__('shared.print_address_hotel_for_tax',[], $lang)}}
                        <br> {{__('shared.print_tel_hotel_for_tax',[], $lang)}}
                    @else
                        {{__('shared.print_address_hotel',[], $lang)}}
                        <br>{{__('shared.print_tel_hotel',[], $lang)}}
                    @endif
                </td>
                <td style="width: 25%">{{__('shared.print_no',[], $lang)}}<span class="print_receipt_number"></span></td>
            </tr>
            </tbody>
        </table>

        <table class="print-table-3">
            <tr>
                <td style="width: 50%">{{__('shared.print_name',[], $lang).' '.$currentCheckedInRoomStayHistroy->renter_firstname.' '.$currentCheckedInRoomStayHistroy->renter_lastname}}</td>
                <td style="width: 30%">{{__('shared.print_paid_date',[], $lang) }} {{$currentCheckedInRoomStayHistroy->check_in}}
                    - {{$currentCheckedInRoomStayHistroy->check_out}}</td>
                <td style="width: 20%; text-align: center">{{__('shared.print_room',[], $lang).' '.$currentCheckedInRoomStayHistroy->room->room_no}}</td>
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
            @if($currentCheckedInRoomStayHistroy->room->status != 'available')
                <tr>
                    <td style="width: 60%;">{{__('shared.print_room_fee',[], $lang)}}</td>
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
                </tr>
            @endif
            @if(sizeof($currentCheckedInRoomStayHistroy->roomUtilityHistory) > 0)
                @foreach($currentCheckedInRoomStayHistroy->roomUtilityHistory as $item)
                    @if(!$item->appSettingEloquentStorage->is_use_calculate_vat)
                        <tr>
                            <td style="width: 60%;">{{ $lang == 'en'? \App\Helpers\RoomServiceHelper::getExtraNameEng($item->appSettingEloquentStorage->key) : $item->appSettingEloquentStorage->extra_key }}</td>
                            <td style="width: 20%;">1</td>
                            <td style="width: 20%;">{{  number_format($item->appSettingEloquentStorage->value,2) }}</td>
                        </tr>
                    @endif
                @endforeach
            @endif
            @if($currentCheckedInRoomStayHistroy->room_maintenance_cost > 0)
                <tr>
                    <td style="width: 60%;">{{__('shared.room_maintenance_cost',[], $lang)}}</td>
                    <td style="width: 20%;">1</td>
                    <td style="width: 20%;">{{  number_format($currentCheckedInRoomStayHistroy->room_maintenance_cost,2) }}</td>
                </tr>
            @endif

            </tbody>
        </table>

        <table class="print-table-6">
            <tbody>
            @if($currentCheckedInRoomStayHistroy->is_use_custom_rental_fee)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_tax',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($totalPayTax  ,2)}}</td>
                </tr>
            @endif
            @if($currentCheckedInRoomStayHistroy->discount > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_discount',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentCheckedInRoomStayHistroy->discount ,2)}}</td>
                </tr>
            @endif
            @if($currentCheckedInRoomStayHistroy->deposit > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_room_deposit',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentCheckedInRoomStayHistroy->deposit ,2)}}</td>
                </tr>
            @endif
            @if($currentCheckedInRoomStayHistroy->deposit_keycard > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_deposit_keycard',[], $lang)}}</td>
                    <td style="width: 20%;"> {{ number_format($currentCheckedInRoomStayHistroy->deposit_keycard ,2)}}</td>
                </tr>
            @endif
            @if( $currentCheckedInRoomStayHistroy->deposit_other > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_deposit_other',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentCheckedInRoomStayHistroy->deposit_other ,2)}}</td>
                </tr>
            @endif
            <tr style="background-color:white !important;">
                <td style="width: 80%;">{{__('shared.print_total_price',[], $lang)}}</td>
                <td style="width: 20%;">{{ number_format( $sumTotal ,2)}} </td>
            </tr>
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
</div>

{{--Script--}}
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ mix('js/libraries/library.js') }}"></script>
<script>
  function showPrint() {
    var token = $('meta[name="csrf-token"]').attr('content');
    var updatePrintUrl = '{{url("room/print/")}}'
    var printStatus = '{{($printAction == config('constants.print') ? "print" : "print_checkin" ) }}'
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