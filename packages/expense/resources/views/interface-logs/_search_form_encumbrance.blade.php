<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-3">
        <div class=" text-left" style="margin-bottom: .5em;">
            <strong> Status </strong>
        </div>
        <div class="input-group">
            <span class="input-group-prepend">
                <span class="input-group-text">
                    <i class="far fa-carrot"></i>
                </span>
            </span>
            {{ Form::select('encumbrance_status', ['all' => 'All', 'C' => 'Success', 'E' => 'Error'],  Request::input('encumbrance_status') ?: 'Y', ['class' => 'form-control']) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class=" text-left" style="margin-bottom: .5em;">
            <strong> BR Number </strong>
        </div>
        <div class="input-group">
            <span class="input-group-prepend">
                <span class="input-group-text">
                    <i class="far fa-user"></i>
                </span>
            </span>
            <input type="text" name="br_number" class="form-control" value="{{ Request::input('br_number') }}" autocomplete="off">
        </div>
    </div>
    <div class="col-md-3">
        <div class=" text-left" style="margin-bottom: .5em;">
            <strong> Date </strong>
        </div>
        <el-date-picker
            class="w-100"
            :name="['start_encumbrance_date', 'end_encumbrance_date']"
            v-model="encumbrance_date"
            type="daterange"
            align="right"
            size="medium"
            start-placeholder="Start Date"
            end-placeholder="End Date"
            :default-value="defaultDate">
        </el-date-picker>
    </div>
    <div class="col-md-2">
        <div class="col-md-12 text-right" style="margin-top: 36px">
            <a href="{{ route('e-expense.interface-logs.index', [
                    'type' => 'encumbrance',
                    'p1' => $encumbrances->currentPage(), 
                    'p2' => $interfaces->currentPage(),
                    'interface_status' => $request->interface_status,
                    'inv_number' => $request->inv_number,
                    'start_interface_date' => $request->start_interface_date,
                    'end_interface_date' => $request->end_interface_date,
                ]) }}" 
            class="btn btn-light btn-sm waves-effect waves-light"> Clear </a>
            <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light" style="background-color: #769aff; border-color: #769aff;"> Search </button>
        </div>
    </div>
</div>
