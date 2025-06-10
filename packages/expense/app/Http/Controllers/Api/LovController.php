<?php

namespace Packages\expense\app\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use PDF;
use Auth;
use Carbon\Carbon;

use Packages\expense\app\Models\RequisitionHeader;
use Packages\expense\app\Models\DocumentCategory;
use Packages\expense\app\Models\Supplier;
use Packages\expense\app\Models\SupplierBank;
use Packages\expense\app\Models\Currency;
use Packages\expense\app\Models\FlexValueV;
use Packages\expense\app\Models\LookupV;
use Packages\expense\app\Models\MTLCategoriesV;
use Packages\expense\app\Models\ARBudgetReceiptV;
use Packages\expense\app\Models\PaymentMethod;
use Packages\expense\app\Models\PaymentTerm;
use Packages\expense\app\Models\ARReceiptNumberAllV;
use Packages\expense\app\Models\Tax;
use Packages\expense\app\Models\WHT;
use Packages\expense\app\Models\BankAccount;
use Packages\expense\app\Models\COAListV;
use Packages\expense\app\Models\GLSubAccountV;

class LovController extends Controller
{
    // LOV
    public function getDocumentType(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $docCategory = DocumentCategory::selectRaw('distinct doc_category_code')
                        ->whereNotNull('attribute1')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(doc_category_code) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('doc_category_code')
                        ->limit(50)
                        ->get();

        return response()->json(['data' => $docCategory]);
    }

    public function getSupplier(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $suppliers = Supplier::selectRaw('distinct vendor_id, vendor_name')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(vendor_name) like ?', ['%'.strtoupper($keyword).'%'])
                                    ->orWhereRaw('vendor_id like ?', [$keyword.'%']);
                            });
                        })
                        ->orderBy('vendor_name')
                        ->limit(50)
                        ->get();

        return response()->json(['data' => $suppliers]);
    }

    public function getSupplierBank(Request $request)
    {
        $supplier = $request->parent;
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        if (!$supplier) {
            $supplierBanks = [];
        }else{
            $supplierBanks = SupplierBank::selectRaw('distinct org_id, bank_name, bank_account_num, order_of_preference, vendor_site_id')
                            ->when($keyword, function ($query, $keyword) {
                                return $query->where(function($r) use ($keyword) {
                                        $r->whereRaw('UPPER(bank_account_num) like ?', ['%'.$keyword.'%'])
                                        ->orWhereRaw('UPPER(bank_name) like ?', ['%'.$keyword.'%']);
                                });
                            })
                            ->when($supplier, function ($query, $supplier) {
                                return $query->where('vendor_id', $supplier)
                                            ->where('org_id', auth()->user()->org_id);
                            })
                            ->whereNull('end_date')
                            ->orderBy('order_of_preference')
                            ->limit(50)
                            ->get();
        }

        return response()->json(['data' => $supplierBanks]);
    }

    public function getPaymentType(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $paymentTypes = LookupV::selectRaw('distinct lookup_code, meaning, description')
                        ->where('lookup_type', 'OAG_AP_PAYMENT_TYPE')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                    ->orWhereRaw('UPPER(lookup_code) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('description')
                        ->limit(50)
                        ->get();

        return response()->json(['data' => $paymentTypes]);
    }

    public function getCurrency(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $currency = Currency::selectRaw('distinct currency_code')
                        ->enabled()
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(currency_code) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('currency_code')
                        ->limit(50)
                        ->get();

        return response()->json(['data' => $currency]);
    }

    public function getYesNoType(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $yesnoTypes = FlexValueV::selectRaw('distinct flex_value, description')
                        ->where('flex_value_set_name', 'OAG_VALUE_SET_Y_N')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('description')
                        ->get();

        return response()->json(['data' => $yesnoTypes]);
    }

    public function getVehicleOilType(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $oilTypes = FlexValueV::selectRaw('distinct flex_value, description')
                        ->where('flex_value_set_name', 'OAG_VEH_OIL_TYPE')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('description')
                        ->get();

        return response()->json(['data' => $oilTypes]);
    }

    public function getUtilityType(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $utilityTypes = FlexValueV::selectRaw('distinct flex_value, description')
                        ->where('flex_value_set_name', 'OAG_AP_PUBLIC_UTILITIES')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('description')
                        ->get();

        return response()->json(['data' => $utilityTypes]);
    }

    public function getUtilityDetail(Request $request)
    {
        $parent = $request->parent;
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $utilityDetails = FlexValueV::selectRaw('distinct flex_value, description, parent_flex_value_low')
                        ->where('flex_value_set_name', 'OAG_AP_BUILDING/CODE/DAD')
                        ->where('parent_flex_value_low', $parent)
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('description')
                        ->get();

        return response()->json(['data' => $utilityDetails]);
    }

    public function getBudgetSource(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $budgetSource = FlexValueV::selectRaw('distinct flex_value, description')
                        ->where('flex_value_set_name', 'OAG_GL_BUDGET_SOURCE')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                    ->orWhereRaw('UPPER(flex_value) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('flex_value')
                        ->get();

        return response()->json(['data' => $budgetSource]);
    }

    public function getBudgetPlan(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $budgetSource = MTLCategoriesV::where('structure_name', 'OAG Item Category Set')
                            ->where('attribute1', 'Yes')
                            ->when($keyword, function ($query, $keyword) {
                                return $query->where(function($r) use ($keyword) {
                                    $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                        ->orWhereRaw('UPPER(category_concat_segs) like ?', ['%'.strtoupper($keyword).'%']);
                                });
                            })
                            ->orderBy('category_concat_segs')
                            ->get();

        return response()->json(['data' => $budgetSource]);
    }

    public function getBudgetType(Request $request)
    {
        $budgetPlan = $request->parent;
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        if (!$budgetPlan) {
            $budgetType = [];
        }else{
                $budgetType = MTLCategoriesV::where('structure_name', 'OAG Item Category Set')
                            ->where('attribute2', 'Yes')
                            ->when($keyword, function ($query, $keyword) {
                                return $query->where(function($r) use ($keyword) {
                                    $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                        ->orWhereRaw('UPPER(category_concat_segs) like ?', ['%'.strtoupper($keyword).'%']);
                                });
                            })
                            ->when($budgetPlan, function ($query, $budgetPlan) {
                                return $query->where('attribute3', $budgetPlan);
                            })
                            ->orderBy('category_concat_segs')
                            ->get();
        }

        return response()->json(['data' => $budgetType]);
    }

    public function getExpenseType(Request $request)
    {
        $budgetType = $request->parent;
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        if (!$budgetType) {
            $expeseType = [];
        }else{
            $expeseType = MTLCategoriesV::where('structure_name', 'OAG Item Category Set')
                            ->where('segment1', 'EXP')
                            ->when($keyword, function ($query, $keyword) {
                                return $query->where(function($r) use ($keyword) {
                                    $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                        ->orWhereRaw('UPPER(category_concat_segs) like ?', ['%'.strtoupper($keyword).'%']);
                                });
                            })
                            ->when($budgetType, function ($query, $budgetType) {
                                return $query->where('attribute4', $budgetType);
                            })
                            ->orderBy('category_concat_segs')
                            ->get();
        }

        return response()->json(['data' => $expeseType]);
    }

    public function getRemainingReceipt(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $budgetSource = ARBudgetReceiptV::where('remaining_amount', '>', 0)
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(receipt_number) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('receipt_number')
                        ->get();

        return response()->json(['data' => $budgetSource]);
    }

    public function getPaymentMethod(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $paymentMethods = PaymentMethod::selectRaw('distinct description, payment_method_code')
                        ->whereNull('inactive_date')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                ->orWhereRaw('UPPER(payment_method_code) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('payment_method_code')
                        ->limit(50)
                        ->get();

        return response()->json(['data' => $paymentMethods]);
    }

    public function getPaymentTerm(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $paymentTerms = PaymentTerm::selectRaw('term_id, description')
                        ->whereNull('end_date_active')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                ->orWhereRaw('UPPER(term_id) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('term_id')
                        ->get();

        return response()->json(['data' => $paymentTerms]);
    }

    public function getReceipt(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $budgetSource = ARReceiptNumberAllV::where('org_id', 81)
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(receipt_number) like ?', ['%'.strtoupper($keyword).'%'])
                                ->orWhereRaw('UPPER(cash_receipt_id) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('receipt_number')
                        ->get();

        return response()->json(['data' => $budgetSource]);
    }

    public function getTaxes(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $taxes = Tax::selectRaw('tax_id, tax')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->WhereRaw('UPPER(tax) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('tax')
                        ->get();

        return response()->json(['data' => $taxes]);
    }

    public function getWht(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $wht = WHT::selectRaw('tax_id, name, description')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->WhereRaw('UPPER(name) like ?', [strtoupper($keyword).'%'])
                                ->orWhereRaw('UPPER(description) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('name')
                        ->get();

        return response()->json(['data' => $wht]);
    }

    public function getBankAccount(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $bankAccount = BankAccount::selectRaw('bank_account_id, bank_account_name, bank_account_num')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->WhereRaw('UPPER(bank_account_name) like ?', [strtoupper($keyword).'%'])
                                ->orWhereRaw('UPPER(bank_account_num) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('bank_account_name')
                        ->get();

        return response()->json(['data' => $bankAccount]);
    }

    public function getExpenseAccount(Request $request)
    {
        $setName = $request->flex_value_set_name;
        $parent = $request->flex_value_set_parent;
        $setValue = $request->flex_value_set_data;
        $text  = $request->get('query');
        $flexValue = [];
        if ($setName == 'OAG_GL_DEPARTMENT') {
            $flexValue = (new COAListV)->LOVResult($setName, $setValue, $text);
        }
        if ($setName == 'OAG_GL_COST_CENTER') {
            $flexValue = (new COAListV)->LOVResult($setName, $setValue, $text);
        }
        if ($setName == 'OAG_GL_BUDGET_YEAR') {
            $flexValue = (new COAListV)->LOVResult($setName, $setValue, $text);
        }
        if ($setName == 'OAG_GL_BUDGET_SOURCE') {
            $flexValue = (new COAListV)->LOVResult($setName, $setValue, $text);
        }
        if ($setName == 'OAG_GL_BUDGET_PLAN') {
            $flexValue = (new COAListV)->LOVResult($setName, $setValue, $text);
        }
        if ($setName == 'OAG_GL_BUDGET_PRODUCT') {
            $flexValue = (new COAListV)->LOVResult($setName, $setValue, $text);
        }
        if ($setName == 'OAG_GL_BUDGET_ACTIVITY') {
            $flexValue = (new COAListV)->LOVResult($setName, $setValue, $text);
        }
        if ($setName == 'OAG_GL_BUDGET_TYPE') {
            $flexValue = (new COAListV)->LOVResult($setName, $setValue, $text);
        }
        if ($setName == 'OAG_GL_BUDGET_CODE') {
            $flexValue = (new COAListV)->LOVResult($setName, $setValue, $text);
        }
        if ($setName == 'OAG_GL_ACCOUNT') {
            $flexValue = (new COAListV)->LOVResult($setName, $setValue, $text);
        }
        if ($setName == 'OAG_GL_SUB_ACCOUNT') {
            $flexValue = (new GLSubAccountV)->LOVResult($setName, $parent, $setValue, $text);
        }
        if ($setName == 'OAG_GL_RESERVE_1') {
            $flexValue = (new COAListV)->LOVResult($setName, $setValue, $text);
        }
        if ($setName == 'OAG_GL_RESERVE_2') {
            $flexValue = (new COAListV)->LOVResult($setName, $setValue, $text);
        }

        return $flexValue;
    }
}
