<div class="modal modal-overide fade print-prev" id="check-out-room-modal"  tabindex="-1" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg text-xs-center" role="document">
        <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">{{__('shared.room_checkout_title')}}</h4>
                </div>
                <div class="modal-body">
                    @if($room->status != 'available')
                        <div id="check-out-print-preview-wrapper" class="print-preview-wrapper img-wrapper white-background">
                            <iframe id="check-out-print-preview-iframe" class="iframe-print-checkout-preview" src="{{url('room/printcheckout/'.$currentCheckedInRoomStayHistroy->id.'?lang='.Request::get('lang'))}}"  scrolling="yes"></iframe>
                        </div>
                    @endif
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('shared.button_cancel')}}</button>
                    <button type="submit" class="btn btn-primary room-check-out-btn">{{__('shared.room_checkout')}}</button>
                </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>