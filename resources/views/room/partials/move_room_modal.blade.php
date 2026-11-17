<div class="modal fade" id="move-room-modal"  data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{url('room/move')}}" method="post">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">{{__('shared.room_move_title')}}</h4>
                </div>
                <div class="modal-body"> 
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-md-5 col-sm-6 control-label">{{__('shared.room_choose_move')}}</label>
                            <div class="col-md-7 col-sm-6">
                                <select class="form-control" name="selectedRoomId">
                                    @if(sizeof($availableRoom) > 0)
                                        @foreach($availableRoom as $room)
                                            <option value="{{$room->id}}">{{$room->room_no}}</option>
                                        @endforeach
                                    @endif
                                </select>  
                            </div>
                        </div>
                        <input type="hidden" name="currentRoomId" value="{{$currentRoom->id}}" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left cancel" data-dismiss="modal">{{__('shared.button_cancel')}}</button>
                    <button type="submit" ng-if="status!='available'" class="btn btn-primary confirm">{{__('shared.button_move')}}</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>