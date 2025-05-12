<div class="modal fade" id="line{{$line->id}}" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('requisition-line.update', [$requisition->id, $line->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h4 class="modal-title">Edit Line</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <requisition-form-line
                        old-quantity="{{ $line->quantity }}"
                        old-price="{{ $line->price }}" inline-template>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <label class="required">Price</label>
                                            <div class="input-group">
                                                <vue-numeric v-model="form.price" name="price" thousand-separator=""
                                                    :empty-value="{{ old('price', 0) }}" :minus="false" :precision="2" :min="0"
                                                    :max="9999999999.99" autocomplete="off" class="form-control" required></vue-numeric>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">Baht</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-row">
                                        <div class="form-group col-12">
                                            <label class="required" class="w-100">
                                                Quantity
                                            </label>
                                            <div class="input-group">
                                                <vue-numeric v-model="form.quantity" name="quantity" thousand-separator=""
                                                    :empty-value="{{ old('quantity', 0) }}" :minus="false" :precision="2" :min="1"
                                                    :max="999999.99" autocomplete="off" class="form-control" required></vue-numeric>
                                            </div>
                                        </div>
                                        <div class="form-group col-12">
                                            <label class="d-block text-right">Total Amount</label>
                                            <div class="text-right h5 pt-1">@{{ totalAmount }} Baht</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </requisition-form-line>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>