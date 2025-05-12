<div class="modal fade" id="modalHold{{$invoice->id}}" role="dialog">
    <div class="modal-dialog modal-lg">
        <form method="post" action="{{ route('invoice.hold-waiting-clear', [$invoice->id]) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hold</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group col-12">
                                    <label class="w-100">Reason</label>
                                    <div class="input-group">
                                        <textarea class="form-control" placeholder="เหตุผล" name="reason" autocomplete="off">
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" onclick="this.disabled=true; this.form.submit();">Confirm</button>
                </div>
            </div>
        </form>
    </div>
</div>