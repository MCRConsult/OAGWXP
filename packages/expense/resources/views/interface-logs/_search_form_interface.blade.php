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
            {{ Form::select('interface_status', ['all' => 'All', 'C' => 'Success', 'E' => 'Error'],  Request::input('interface_status') ?: 'Y', ['class' => 'form-control']) }}
        </div>
    </div>
    <div class="col-md-3">
        <div class=" text-left" style="margin-bottom: .5em;">
            <strong> Invoice Number </strong>
        </div>
        <div class="input-group">
            <span class="input-group-prepend">
                <span class="input-group-text">
                    <i class="far fa-user"></i>
                </span>
            </span>
            <input type="text" name="inv_number" class="form-control" value="{{ Request::input('inv_number') }}" autocomplete="off">
        </div>
    </div>
    <div class="col-md-3">
        <div class=" text-left" style="margin-bottom: .5em;">
            <strong> Date </strong>
        </div>
        <el-date-picker
            class="w-100"
            :name="['start_interface_date', 'end_interface_date']"
            v-model="interface_date"
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
                    'type' => 'interface', 
                    'p1' => $encumbrances->currentPage(), 
                    'p2' => $interfaces->currentPage(),
                    'encumbrance_status' => $request->encumbrance_status,
                    'br_number' => $request->br_number,
                    'start_encumbrance_date' => $request->start_encumbrance_date,
                    'end_encumbrance_date' => $request->end_encumbrance_date,
                ]) }}" 
            class="btn btn-light btn-sm waves-effect waves-light"> Clear </a>
            <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light" style="background-color: #769aff; border-color: #769aff;"> Search </button>
        </div>
    </div>
</div>

