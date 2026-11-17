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

</head>

<body>
<?php 
    $priceBeforeVat = 0 ; 
    $totalRowPerpage =0;
?>
<div id="print-container">

    <div class="hide-print"> 
        <div class="pull-right">
            <a onClick="showPrint();" href="javascript:void(0)" class="btn btn-success btn-print">
                <i class="fa fa-print" aria-hidden="true"></i> {{__('shared.print',[], $lang)}}
            </a>
        </div>
    </div>

    <div class="print-receipt">
        <table class="print-table-1">
            <tbody>
                <tr>
                    <th style="width: 75%">
                        <img src="{{ asset('images/tonkla-logo.png') }}" class="logo" alt="Tonkla-Logo">
                    </th>
                    <th style="width: 25%" class="print-title">
                        {{__('shared.print_title_receipt',[], $lang)}}
                        <img src="{{ asset('images/tonkla-watermark.png') }}" class="watermark-1" alt="Tonkla-Watermark" width="125px" height="110px">
                    </th>
                </tr>
            </tbody>
        </table>

        <table class="print-table-2">
            <tr>
                <td style="width: 75%">
                    <b>{{__('shared.print_tonkla',[], $lang)}}</b>
                    <br>{{__('shared.print_address',[], $lang)}}
                    <br>{{__('shared.print_tel',[], $lang)}}</td>
                <td style="width: 25%">{{__('shared.print_no',[], $lang).' '.$currentRoomHistory->currentCheckedInRoomStayHistroy->id}}</td>
            </tr>
            </tbody>
        </table>

        <table class="print-table-3">
            <tr>
                <td style="width: 50%">{{__('shared.print_name',[], $lang).' '.(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->renter_firstname) ? $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_firstname : '').' '.(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->renter_lastname) ? $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_lastname : '')}}</td>
                <td style="width: 30%">{{__('shared.print_paid_date',[], $lang).(  $createdDate->format('d/m/Y') )}}</td>
                <td style="width: 20%">{{__('shared.print_room',[], $lang).' '.$currentRoomHistory->room->room_no}}</td>
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
                @if((!$isIncludedCheckOut && ($currentRoomHistory->room->status != 'daily' && $currentRoomHistory->room->status != 'available')) || $currentRoomHistory->room->status == 'daily')
                <tr>
                    <td style="width: 60%;">{{__('shared.print_room_fee',[], $lang)}}</td>
                    @if($currentRoomHistory->room->status == 'daily')
                        @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->check_out) && isset($currentRoomHistory->currentCheckedInRoomStayHistroy->check_in ) && $currentRoomHistory->currentCheckedInRoomStayHistroy->check_out != null && $currentRoomHistory->currentCheckedInRoomStayHistroy->check_in != null )
                            <?php $totalDate  =Carbon\Carbon::createFromFormat('d/m/Y H:i:s',$currentRoomHistory->currentCheckedInRoomStayHistroy->check_out)->startOfDay()->diffInDays(Carbon\Carbon::createFromFormat('d/m/Y H:i:s',$currentRoomHistory->currentCheckedInRoomStayHistroy->check_in)->startOfDay() );?>
                            <td style="width: 20%;"> {{ $totalDate}}  </td>
                            <td style="width: 20%;"> 
                                {{ isset($currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ) && $currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee  ? number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->custom_rental_fee * $totalDate,2) : number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->rental_fee * $totalDate,2) }}
                            </td>
                            <?php $priceBeforeVat +=$currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee  ? $currentRoomHistory->currentCheckedInRoomStayHistroy->custom_rental_fee * $totalDate   :  $currentRoomHistory->currentCheckedInRoomStayHistroy->rental_fee * $totalDate; ?>
                            <?php $totalRowPerpage +=1; ?>
                        @else
                            <td style="width: 20%;"> 1 </td>
                            <td style="width: 20%;">{{ isset($currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ) && $currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee  ? number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->custom_rental_fee,2) : number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->rental_fee,2) }} </td>
                            <?php $priceBeforeVat += isset($currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ) && $currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee  ? $currentRoomHistory->currentCheckedInRoomStayHistroy->custom_rental_fee   :  $currentRoomHistory->currentCheckedInRoomStayHistroy->rental_fee; ?>
                            <?php $totalRowPerpage +=1; ?>
                        @endif
                        
                    @else 
                        <td style="width: 20%;">1</td>
                        <td style="width: 20%;">{{ isset($currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ) && $currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee  ? number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->custom_rental_fee,2) : number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->rental_fee,2) }}</td>
                        <?php $priceBeforeVat +=$currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee  ? $currentRoomHistory->currentCheckedInRoomStayHistroy->custom_rental_fee : $currentRoomHistory->currentCheckedInRoomStayHistroy->rental_fee ; ?>
                        <?php $totalRowPerpage +=1; ?>
                    @endif 
                </tr> 
                @endif
                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->electric_meter_fee) && $currentRoomHistory->currentCheckedInRoomStayHistroy->electric_meter_fee > 0)  
                    <tr>
                        <td style="width: 60%;">{{__('shared.print_room_electric_fee',[], $lang)}}</td>
                        <td style="width: 20%;">{{ $currentRoomHistory->currentCheckedInRoomStayHistroy->electric_meter_unit }}</td>
                        <td style="width: 20%;">{{  number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->electric_meter_fee,2) }}</td>
                    </tr>
                    <?php $priceBeforeVat += $currentRoomHistory->currentCheckedInRoomStayHistroy->electric_meter_fee;?>
                    <?php $totalRowPerpage +=1; ?>
                @endif
                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->water_meter_fee) && $currentRoomHistory->currentCheckedInRoomStayHistroy->water_meter_fee > 0)  
                    <tr>
                        <td style="width: 60%;">{{__('shared.print_room_water_fee',[], $lang)}}</td>
                        <td style="width: 20%;">{{ $currentRoomHistory->currentCheckedInRoomStayHistroy->water_meter_unit }}</td>
                        <td style="width: 20%;">{{  number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->water_meter_fee,2) }}</td>
                    </tr>
                    <?php $priceBeforeVat += $currentRoomHistory->currentCheckedInRoomStayHistroy->water_meter_fee;?>
                    <?php $totalRowPerpage +=1; ?>
                @endif

                @if($isIncludedCheckIn && isset($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit) && $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit > 0)   
                    <tr>
                        <td style="width: 60%;">{{__('shared.print_deposit',[], $lang)}}</td>
                        <td style="width: 20%;">1</td>
                        <td style="width: 20%;">{{  number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit,2) }}</td>
                    </tr> 
                    <?php //$priceBeforeVat += $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit;?>
                    <?php $totalRowPerpage +=1; ?>
                @endif
                @if($isIncludedCheckIn && isset($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_keycard) && $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_keycard > 0)   
                    <tr>
                        <td style="width: 60%;">{{__('shared.print_deposit_keycard',[], $lang)}}</td>
                        <td style="width: 20%;">{{ isset($currentRoomHistory->room->keycards) && is_array($currentRoomHistory->room->keycards) ? count($currentRoomHistory->room->keycards) : 1 }}</td>
                        <td style="width: 20%;">{{  number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_keycard,2) }}</td>
                    </tr> 
                    <?php //$priceBeforeVat += $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_keycard;?>
                    <?php $totalRowPerpage +=1; ?>
                @endif
                @if($isIncludedCheckIn && isset($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_other) && $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_other > 0)  
                    <tr>
                        <td style="width: 60%;">{{__('shared.print_deposit_other',[], $lang)}}</td>
                        <td style="width: 20%;">1</td>
                        <td style="width: 20%;">{{  number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_other,2) }}</td>
                    </tr> 
                    <?php //$priceBeforeVat += $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_other;?>
                    <?php $totalRowPerpage +=1; ?>
                @endif

                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->room_utility_history) && sizeof($currentRoomHistory->currentCheckedInRoomStayHistroy->room_utility_history) > 0)
                    @foreach($currentRoomHistory->currentCheckedInRoomStayHistroy->room_utility_history as $item)
                        @if(isset($item->app_setting_eloquent_storage))
                            <tr>
                                <td style="width: 60%;">{{ $item->app_setting_eloquent_storage->extra_key }}</td>
                                <td style="width: 20%;">1</td>
                                <td style="width: 20%;">{{  number_format($item->app_setting_eloquent_storage->value,2) }}</td>
                            </tr>
                             <?php $priceBeforeVat += $item->app_setting_eloquent_storage->value  ; ?>
                            <?php $totalRowPerpage +=1; ?>
                        @endif
                    @endforeach
                @endif

                {{--  @for($i =0; $i < $totalRowPerpage; $i++)
                    <tr>
                        <td style="width: 60%;"></td>
                        <td style="width: 20%;"></td>
                        <td style="width: 20%;"></td>
                    </tr>
                @endfor  --}}
                
            </tbody>
        </table> 
        <table class="print-table-6">
            <tbody>
                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee) && $currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_vat',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->total_price - $priceBeforeVat  ,2)}}</td>
                </tr>
                @endif
                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->discount) && $isIncludedCheckOut && $currentRoomHistory->currentCheckedInRoomStayHistroy->discount > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_discount',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->discount ,2)}}</td>
                </tr>
                @endif
                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit) && $isIncludedCheckOut && $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_deposit',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit ,2)}}</td>
                </tr>
                @endif
                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_keycard) && $isIncludedCheckOut && $currentRoomHistory->room->status == 'daily' && $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_keycard > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_deposit_keycard',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_keycard ,2)}}</td>
                </tr>
                @endif
                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_other) && $isIncludedCheckOut && $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_other > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_deposit_other',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_other ,2)}}</td>
                </tr>
                @endif
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_total_price',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->calculated_total ,2)}}</td>
                </tr>
            </tbody>
        </table>

        <br>
        <table class="print-table-7">
            <tbody>
                <tr>
                    <td style="width:50%">{{__('shared.print_receiver_1',[], $lang)}}</td>
                    <td style="width:50%">{{__('shared.print_receiver_date_1',[], $lang)}}</td>
                </tr>
                <tr>
                    <td>{{__('shared.print_renter_1',[], $lang)}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="print-invoice">
        <table class="print-table-1">
            <tbody>
                <tr>
                    <th style="width: 75%">
                        <img src="{{ asset('images/tonkla-logo.png') }}" class="logo" alt="Tonkla-Logo">
                    </th>
                    <th style="width: 25%" class="print-title">
                        {{__('shared.print_title_invoice',[], $lang)}}
                        <img src="{{ asset('images/tonkla-watermark.png') }}" class="watermark-2" alt="Tonkla-Watermark" width="125px" height="110px">
                    </th>
                </tr>
            </tbody>
        </table>

        <table class="print-table-2">
            <tr>
                <td style="width: 75%">
                    <b>{{__('shared.print_tonkla',[], $lang)}}</b>
                    <br>{{__('shared.print_address',[], $lang)}}
                    <br>{{__('shared.print_tel',[], $lang)}}</td>
                <td style="width: 25%">{{__('shared.print_no',[], $lang).' '.$currentRoomHistory->currentCheckedInRoomStayHistroy->id}}</td>
            </tr>
            </tbody>
        </table>

        <table class="print-table-3">
            <tr>
                <td style="width: 50%">{{__('shared.print_name',[], $lang).' '.(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->renter_firstname) ? $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_firstname : '').' '.(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->renter_lastname) ? $currentRoomHistory->currentCheckedInRoomStayHistroy->renter_lastname : '')}}</td>
                <td style="width: 30%">{{__('shared.print_paid_date',[], $lang).(  $createdDate->format('d/m/Y') )}}</td>
                <td style="width: 20%">{{__('shared.print_room',[], $lang).' '.$currentRoomHistory->room->room_no}}</td>
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
                @if((!$isIncludedCheckOut && ($currentRoomHistory->room->status != 'daily' && $currentRoomHistory->room->status != 'available')) || $currentRoomHistory->room->status == 'daily')
                <tr>
                    <td style="width: 60%;">{{__('shared.print_room_fee',[], $lang)}}</td>
                    @if($currentRoomHistory->room->status == 'daily')
                        @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->check_out) && isset($currentRoomHistory->currentCheckedInRoomStayHistroy->check_in ) && $currentRoomHistory->currentCheckedInRoomStayHistroy->check_out != null && $currentRoomHistory->currentCheckedInRoomStayHistroy->check_in != null )
                            <?php $totalDate  =Carbon\Carbon::createFromFormat('d/m/Y H:i:s',$currentRoomHistory->currentCheckedInRoomStayHistroy->check_out)->startOfDay()->diffInDays(Carbon\Carbon::createFromFormat('d/m/Y H:i:s',$currentRoomHistory->currentCheckedInRoomStayHistroy->check_in)->startOfDay() );?>
                            <td style="width: 20%;"> {{ $totalDate}}  </td>
                            <td style="width: 20%;"> 
                                {{ isset($currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ) && $currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee  ? number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->custom_rental_fee * $totalDate,2) : number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->rental_fee * $totalDate,2) }}
                            </td>
                        @else
                            <td style="width: 20%;"> 1 </td>
                            <td style="width: 20%;">{{ isset($currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ) && $currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee  ? number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->custom_rental_fee,2) : number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->rental_fee,2) }} </td>
                        @endif
                    @else
                        <td style="width: 20%;">1</td>
                        <td style="width: 20%;">{{ isset($currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee ) && $currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee  ? number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->custom_rental_fee,2) : number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->rental_fee,2) }}</td>
                    @endif 
                </tr>
                @endif 
                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->electric_meter_fee) && $currentRoomHistory->currentCheckedInRoomStayHistroy->electric_meter_fee > 0)  
                    <tr>
                        <td style="width: 60%;">{{__('shared.print_room_electric_fee',[], $lang)}}</td>
                        <td style="width: 20%;">{{ $currentRoomHistory->currentCheckedInRoomStayHistroy->electric_meter_unit }}</td>
                        <td style="width: 20%;">{{  number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->electric_meter_fee,2) }}</td>
                    </tr>
                @endif
                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->water_meter_fee) && $currentRoomHistory->currentCheckedInRoomStayHistroy->water_meter_fee > 0)  
                    <tr>
                        <td style="width: 60%;">{{__('shared.print_room_water_fee',[], $lang)}}</td>
                        <td style="width: 20%;">{{ $currentRoomHistory->currentCheckedInRoomStayHistroy->water_meter_unit }}</td>
                        <td style="width: 20%;">{{  number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->water_meter_fee,2) }}</td>
                    </tr>
                @endif

                @if($isIncludedCheckIn && isset($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit) && $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit > 0)   
                    <tr>
                        <td style="width: 60%;">{{__('shared.print_deposit',[], $lang)}}</td>
                        <td style="width: 20%;">1</td>
                        <td style="width: 20%;">{{  number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit,2) }}</td>
                    </tr> 
                @endif
                @if($isIncludedCheckIn && isset($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_keycard) && $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_keycard > 0)   
                    <tr>
                        <td style="width: 60%;">{{__('shared.print_deposit_keycard',[], $lang)}}</td>
                        <td style="width: 20%;">{{ isset($currentRoomHistory->room->keycards) && is_array($currentRoomHistory->room->keycards) ? count($currentRoomHistory->room->keycards) : 1 }}</td>
                        <td style="width: 20%;">{{  number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_keycard,2) }}</td>
                    </tr> 
                @endif
                @if($isIncludedCheckIn && isset($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_other) && $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_other > 0)  
                    <tr>
                        <td style="width: 60%;">{{__('shared.print_deposit_other',[], $lang)}}</td>
                        <td style="width: 20%;">1</td>
                        <td style="width: 20%;">{{  number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_other,2) }}</td>
                    </tr> 
                @endif

                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->room_utility_history) && sizeof($currentRoomHistory->currentCheckedInRoomStayHistroy->room_utility_history) > 0)
                    @foreach($currentRoomHistory->currentCheckedInRoomStayHistroy->room_utility_history as $item)
                        @if(isset($item->app_setting_eloquent_storage))
                            <tr>
                                <td style="width: 60%;">{{ $item->app_setting_eloquent_storage->extra_key }}</td>
                                <td style="width: 20%;">1</td>
                                <td style="width: 20%;">{{  number_format($item->app_setting_eloquent_storage->value,2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endif

                {{--  @for($i =0; $i < $totalRowPerpage; $i++)
                    <tr>
                        <td style="width: 60%;"></td>
                        <td style="width: 20%;"></td>
                        <td style="width: 20%;"></td>
                    </tr>
                @endfor  --}}

            </tbody>
        </table>
        
        <table class="print-table-6">
            <tbody> 
                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee) && $currentRoomHistory->currentCheckedInRoomStayHistroy->is_use_custom_rental_fee)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_vat',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->total_price - $priceBeforeVat  ,2)}}</td>
                </tr>
                @endif
                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->discount) && $isIncludedCheckOut && $currentRoomHistory->currentCheckedInRoomStayHistroy->discount > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_discount',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->discount ,2)}}</td>
                </tr>
                @endif
                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit) && $isIncludedCheckOut && $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_deposit',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit ,2)}}</td>
                </tr>
                @endif
                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_keycard) && $currentRoomHistory->room->status == 'daily' && $isIncludedCheckOut && $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_keycard > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_deposit_keycard',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_keycard ,2)}}</td>
                </tr>
                @endif
                @if(isset($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_other) && $isIncludedCheckOut && $currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_other > 0)
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_deposit_other',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->deposit_other ,2)}}</td>
                </tr>
                @endif
                <tr style="background-color:white !important;">
                    <td style="width: 80%;">{{__('shared.print_total_price',[], $lang)}}</td>
                    <td style="width: 20%;">{{ number_format($currentRoomHistory->currentCheckedInRoomStayHistroy->calculated_total ,2)}}</td>
                </tr>
            </tbody>
        </table>

        <br>
        <table class="print-table-7">
            <tbody>
                <tr>
                    <td style="width:50%">{{__('shared.print_receiver_2',[], $lang)}}</td>
                    <td style="width:50%">{{__('shared.print_approval',[], $lang)}}</td>
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
            window.print();
            setTimeout(function(){ 
                parent.$('#print-preview-modal').modal('hide');
                parent.$('#check-out-room-modal').modal('hide');
            }, 100);
        }
        $(document).ready(function(){ 
            var printContainer = $('#print-container');
            if (/firefox/.test(navigator.userAgent.toLowerCase()))
            {
                printContainer.addClass("firefox-container");
            }
            else if (/msie/.test(navigator.userAgent.toLowerCase()) || /edge/.test(navigator.userAgent.toLowerCase()))
            {
                printContainer.addClass("edge-container");
            }
            else 
            {
                printContainer.addClass("chrome-container");
            }
        });
    </script>
</body>

</html>