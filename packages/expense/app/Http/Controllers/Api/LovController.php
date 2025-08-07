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
use Packages\expense\app\Models\PaymentMethod;
use Packages\expense\app\Models\PaymentTerm;
use Packages\expense\app\Models\ARBudgetReceiptV;
use Packages\expense\app\Models\ARReceiptAccountV;
use Packages\expense\app\Models\ARReceiptNumberAllV;
use Packages\expense\app\Models\Tax;
use Packages\expense\app\Models\WHT;
use Packages\expense\app\Models\BankAccount;
use Packages\expense\app\Models\COAListV;
use Packages\expense\app\Models\GLCostCenterV;
use Packages\expense\app\Models\GLBudgetProductV;
use Packages\expense\app\Models\GLBudgetActivityV;
use Packages\expense\app\Models\GLSubAccountV;
use Packages\expense\app\Models\ARPOATT1V;
use Packages\expense\app\Models\GuaranteeReceiptV;

class LovController extends Controller
{
    // LOV
    public function getDocumentType(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $docCategories = DocumentCategory::selectRaw('distinct doc_category_code')
                        ->whereNotNull('attribute1')
                        ->where('doc_category_code', 'like', '%ขบ.%')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(doc_category_code) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('doc_category_code')
                        ->limit(50)
                        ->get();

        $docCategory = DocumentCategory::selectRaw('distinct doc_category_code')
                        ->whereNotNull('attribute1')
                        ->where('doc_category_code', 'like', '%ขบ.%')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(doc_category_code) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->first();

        if ($docCategory) {
            $docCategories = $docCategories->push($docCategory)->unique('doc_category_code');
        }

        return response()->json(['data' => $docCategories]);
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

        $supplier = Supplier::selectRaw('distinct vendor_id, vendor_name')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(vendor_name) like ?', ['%'.strtoupper($keyword).'%'])
                                    ->orWhereRaw('vendor_id like ?', [$keyword.'%']);
                            });
                        })
                        ->first();
        if ($supplier) {
            $suppliers = $suppliers->push($supplier)->unique('vendor_id');
        }

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

        $paymentType = LookupV::selectRaw('distinct lookup_code, meaning, description')
                        ->where('lookup_type', 'OAG_AP_PAYMENT_TYPE')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                    ->orWhereRaw('UPPER(lookup_code) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->first();
        if ($paymentType) {
            $paymentTypes = $paymentTypes->push($paymentType)->unique('lookup_code');
        }

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
                        ->limit(50)
                        ->get();

        $oilType = FlexValueV::selectRaw('distinct flex_value, description')
                        ->where('flex_value_set_name', 'OAG_VEH_OIL_TYPE')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->first();
        if ($oilType) {
            $oilTypes = $oilTypes->push($oilType)->unique('flex_value');
        }

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
                        ->limit(50)
                        ->get();

        $utilityType = FlexValueV::selectRaw('distinct flex_value, description')
                        ->where('flex_value_set_name', 'OAG_AP_PUBLIC_UTILITIES')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->first();
        if ($utilityType) {
            $utilityTypes = $utilityTypes->push($utilityType)->unique('flex_value');
        }

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
                        ->limit(50)
                        ->get();

        $utilityDetail = FlexValueV::selectRaw('distinct flex_value, description, parent_flex_value_low')
                        ->where('flex_value_set_name', 'OAG_AP_BUILDING/CODE/DAD')
                        ->where('parent_flex_value_low', $parent)
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->first();
        if ($utilityDetail) {
            $utilityDetails = $utilityDetails->push($utilityDetail)->unique('flex_value');
        }

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
                        ->limit(50)
                        ->get();

        return response()->json(['data' => $budgetSource]);
    }

    public function getBudgetPlan(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $budgetPlans = MTLCategoriesV::where('structure_name', 'OAG Item Category Set')
                        ->where('attribute1', 'Yes')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                    ->orWhereRaw('UPPER(category_concat_segs) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('category_concat_segs')
                        ->limit(50)
                        ->get();

        $budgetPlan = MTLCategoriesV::where('structure_name', 'OAG Item Category Set')
                        ->where('attribute1', 'Yes')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                    ->orWhereRaw('UPPER(category_concat_segs) like ?', ['%'.strtoupper($keyword).'%']);
                            });
                        })
                        ->first();
        if ($budgetPlan) {
            $budgetPlans = $budgetPlans->push($budgetPlan)->unique('category_concat_segs');
        }

        return response()->json(['data' => $budgetPlans]);
    }

    public function getBudgetType(Request $request)
    {
        $budgetPlan = $request->parent;
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        if (!$budgetPlan) {
            $budgetTypes = [];
        }else{
            $budgetTypes = MTLCategoriesV::where('structure_name', 'OAG Item Category Set')
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
                            ->first();
            if ($budgetType) {
                $budgetTypes = $budgetTypes->push($budgetType)->unique('category_concat_segs');
            }
        }

        return response()->json(['data' => $budgetTypes]);
    }

    public function getExpenseType(Request $request)
    {
        $budgetType = $request->parent;
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $sourceDefault = ['500', '510', '520', '530', '540', '550'];
        if (!$budgetType) {
            $expeseTypes = [];
        }else{
            if (in_array($request->budget_source, $sourceDefault)) {
                // OAG_AP_WEB_MAPPING_EXP RULE
                $expeseTypes = LookupV::selectRaw('meaning category_concat_segs, description')
                            ->where('lookup_type', 'OAG_AP_WEB_MAPPING_EXP RULE')
                            ->where('tag', $request->budget_source)
                            ->when($keyword, function ($query, $keyword) {
                                return $query->where(function($r) use ($keyword) {
                                    $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                        ->orWhereRaw('UPPER(meaning) like ?', ['%'.strtoupper($keyword).'%']);
                                });
                            })
                            ->orderBy('description')
                            ->get();

                $expeseType = LookupV::selectRaw('meaning category_concat_segs, description')
                            ->where('lookup_type', 'OAG_AP_WEB_MAPPING_EXP RULE')
                            ->where('tag', $request->budget_source)
                            ->when($keyword, function ($query, $keyword) {
                                return $query->where(function($r) use ($keyword) {
                                    $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                        ->orWhereRaw('UPPER(meaning) like ?', ['%'.strtoupper($keyword).'%']);
                                });
                            })
                            ->first();
                if ($expeseType) {
                    $expeseTypes = $expeseTypes->push($expeseType)->unique('category_concat_segs');
                }
            }else{
                $expeseTypes = MTLCategoriesV::where('structure_name', 'OAG Item Category Set')
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

                $expeseType =  MTLCategoriesV::where('structure_name', 'OAG Item Category Set')
                                ->when($keyword, function ($query, $keyword) {
                                    return $query->where(function($r) use ($keyword) {
                                        $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                            ->orWhereRaw('UPPER(category_concat_segs) like ?', ['%'.strtoupper($keyword).'%']);
                                    });
                                })
                                ->when($budgetType, function ($query, $budgetType) {
                                    return $query->where('attribute4', $budgetType);
                                })
                            ->first();
                if ($expeseType) {
                    $expeseTypes = $expeseTypes->push($expeseType)->unique('category_concat_segs');
                }
            }
        }

        return response()->json(['data' => $expeseTypes]);
    }

    public function getRemainingReceipt(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $remainingReceipts = ARBudgetReceiptV::where('remaining_amount', '>', 0)
                            ->when($keyword, function ($query, $keyword) {
                                return $query->where(function($r) use ($keyword) {
                                    $r->whereRaw('UPPER(receipt_number) like ?', ['%'.strtoupper($keyword).'%'])
                                        ->orWhereRaw('cash_receipt_id like ?', ['%'.strtoupper($keyword).'%']);
                                });
                            })
                            ->orderBy('receipt_number')
                            ->limit(50)
                            ->get();

        $remainingReceipt = ARBudgetReceiptV::where('remaining_amount', '>', 0)
                            ->when($keyword, function ($query, $keyword) {
                                return $query->where(function($r) use ($keyword) {
                                    $r->whereRaw('UPPER(receipt_number) like ?', ['%'.strtoupper($keyword).'%'])
                                        ->orWhereRaw('cash_receipt_id like ?', ['%'.strtoupper($keyword).'%']);
                                });
                            })
                            ->first();
        if ($remainingReceipt) {
            $remainingReceipts = $remainingReceipts->push($remainingReceipt)->unique('cash_receipt_id');
        }

        return response()->json(['data' => $remainingReceipts]);
    }

    public function getGuaranteeReceipt(Request $request)
    {
        $refContract = $request->refContract;
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $guaranteeReceipts = GuaranteeReceiptV::where('receipt_amount', '>', 0)
                            // ->when($refContract, function ($query, $refContract) {
                            //     return $query->where('doc_no', $refContract);
                            // })
                            ->when($keyword, function ($query, $keyword) {
                                return $query->where(function($r) use ($keyword) {
                                    $r->whereRaw('UPPER(receipt_number) like ?', [$keyword])
                                        ->orWhereRaw('cash_receipt_id like ?', [$keyword]);
                                });
                            })
                            ->orderBy('receipt_number')
                            ->limit(50)
                            ->get();

        $guaranteeReceipt = GuaranteeReceiptV::where('receipt_amount', '>', 0)
                            ->when($keyword, function ($query, $keyword) {
                                return $query->where(function($r) use ($keyword) {
                                    $r->whereRaw('UPPER(receipt_number) like ?', [$keyword])
                                        ->orWhereRaw('cash_receipt_id like ?', [$keyword]);
                                });
                            })
                            ->first();
        if ($guaranteeReceipt) {
            $guaranteeReceipts = $guaranteeReceipts->push($guaranteeReceipt)->unique('cash_receipt_id');
        }

        return response()->json(['data' => $guaranteeReceipts]);
    }

    public function getReceiptAccount(Request $request)
    {
        $budgetSource = $request->budgetSource;
        $receiptId = $request->parent;
        $keyword = isset($request->keyword)? $request->keyword: '';
        if ($budgetSource == '510') {
            $receiptAccounts = ARReceiptAccountV::where('amount', '>', 0)
                            ->when($keyword, function ($query, $keyword) {
                                return $query->where(function($r) use ($keyword) {
                                    $r->where('account_code', $keyword);
                                });
                            })
                            ->when($receiptId, function ($query, $receiptId) {
                                return $query->where('cash_receipt_id', $receiptId);
                            })
                            ->orderBy('code_combination_id')
                            ->get();
        }else{
            $receiptAccounts = GuaranteeReceiptV::selectRaw('cash_receipt_id, receipt_amount amount, concatenated_segments account_code')
                            ->where('receipt_amount', '>', 0)
                            ->when($keyword, function ($query, $keyword) {
                                return $query->where(function($r) use ($keyword) {
                                    $r->where('concatenated_segments', $keyword);
                                });
                            })
                            ->when($receiptId, function ($query, $receiptId) {
                                return $query->where('cash_receipt_id', $receiptId);
                            })
                            ->orderBy('code_combination_id')
                            ->get();
        }

        return response()->json(['data' => $receiptAccounts]);
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

        $paymentMethod = PaymentMethod::selectRaw('distinct description, payment_method_code')
                        ->whereNull('inactive_date')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                ->orWhereRaw('UPPER(payment_method_code) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->first();
        if ($paymentMethod) {
            $paymentMethods = $paymentMethods->push($paymentMethod)->unique('payment_method_code');
        }

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
                        ->limit(50)
                        ->get();

        $paymentTerm = PaymentTerm::selectRaw('term_id, description')
                        ->whereNull('end_date_active')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(description) like ?', ['%'.strtoupper($keyword).'%'])
                                ->orWhereRaw('UPPER(term_id) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->first();
        if ($paymentTerm) {
            $paymentTerms = $paymentTerms->push($paymentTerm)->unique('term_id');
        }

        return response()->json(['data' => $paymentTerms]);
    }

    public function getReceipt(Request $request)
    {
        $user = auth()->user();
        $supplier = $request->supplier;
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $receipts = ARReceiptNumberAllV::where('org_id', $user->org_id)
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->whereRaw('UPPER(receipt_number) like ?', ['%'.strtoupper($keyword).'%'])
                                ->orWhereRaw('UPPER(cash_receipt_id) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->when($supplier, function ($query, $supplier) {
                            return $query->where(function($r) use ($supplier) {
                                $r->whereRaw('UPPER(customer_name) like ?', ['%'.strtoupper($supplier).'%']);
                            });
                        })
                        ->orderByRaw('receipt_date desc')
                        ->limit(50)
                        ->get();

        $receipt = ARReceiptNumberAllV::where('org_id', $user->org_id)
                    ->when($keyword, function ($query, $keyword) {
                        return $query->where(function($r) use ($keyword) {
                            $r->whereRaw('UPPER(receipt_number) like ?', ['%'.strtoupper($keyword).'%'])
                            ->orWhereRaw('UPPER(cash_receipt_id) like ?', [strtoupper($keyword).'%']);
                        });
                    })
                    ->first();
        if ($receipt) {
            $receipts = $receipts->push($receipt)->unique('cash_receipt_id');
        }

        return response()->json(['data' => $receipts]);
    }

    public function getTaxes(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $taxes = Tax::selectRaw('tax_rate_id, tax, tax_rate_code, percentage_rate')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->WhereRaw('UPPER(tax) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->where('rate_type_code', '!=', 'RECOVERY')
                        ->orderBy('tax')
                        ->limit(50)
                        ->get();

        $tax = Tax::selectRaw('tax_rate_id, tax, tax_rate_code, percentage_rate')
                    ->when($keyword, function ($query, $keyword) {
                        return $query->where(function($r) use ($keyword) {
                            $r->WhereRaw('UPPER(tax) like ?', [strtoupper($keyword).'%']);
                        });
                    })
                    ->where('rate_type_code', '!=', 'RECOVERY')
                    ->first();
        if ($tax) {
            $taxes = $taxes->push($tax)->unique('tax_rate_id');
        }

        return response()->json(['data' => $taxes]);
    }

    public function getWht(Request $request)
    {
        $user = auth()->user();
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $whts = WHT::selectRaw('tax_id, name, description, tax_rates')
                ->where('org_id', $user->org_id)
                ->when($keyword, function ($query, $keyword) {
                    return $query->where(function($r) use ($keyword) {
                        $r->WhereRaw('UPPER(name) like ?', [strtoupper($keyword).'%'])
                        ->orWhereRaw('UPPER(description) like ?', [strtoupper($keyword).'%']);
                    });
                })
                ->orderBy('name')
                ->limit(50)
                ->get();

        $wht = WHT::selectRaw('tax_id, name, description, tax_rates')
                ->where('org_id', $user->org_id)
                ->when($keyword, function ($query, $keyword) {
                    return $query->where(function($r) use ($keyword) {
                        $r->WhereRaw('UPPER(name) like ?', [strtoupper($keyword).'%'])
                        ->orWhereRaw('UPPER(description) like ?', [strtoupper($keyword).'%']);
                    });
                })
                ->first();
        if ($wht) {
            $whts = $whts->push($wht)->unique('tax_id');
        }

        return response()->json(['data' => $whts]);
    }

    public function getBankAccount(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $bankAccounts = BankAccount::selectRaw('bank_account_id, bank_account_name, bank_account_num')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->WhereRaw('UPPER(bank_account_name) like ?', [strtoupper($keyword).'%'])
                                ->orWhereRaw('UPPER(bank_account_num) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('bank_account_name')
                        ->limit(50)
                        ->get();

        $bankAccount = BankAccount::selectRaw('bank_account_id, bank_account_name, bank_account_num')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->WhereRaw('UPPER(bank_account_name) like ?', [strtoupper($keyword).'%'])
                                ->orWhereRaw('UPPER(bank_account_num) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->first();
        if ($bankAccount) {
            $bankAccounts = $bankAccounts->push($bankAccount)->unique('bank_account_id');
        }

        return response()->json(['data' => $bankAccounts]);
    }

    public function getContract(Request $request)
    {
        $keyword = isset($request->keyword) ? '%'.strtoupper($request->keyword).'%' : '%';
        $contracts = ARPOATT1V::selectRaw('attribute1, meaning')
                        ->when($keyword, function ($query, $keyword) {
                            return $query->where(function($r) use ($keyword) {
                                $r->WhereRaw('UPPER(attribute1) like ?', [strtoupper($keyword).'%'])
                                ->orWhereRaw('UPPER(meaning) like ?', [strtoupper($keyword).'%']);
                            });
                        })
                        ->orderBy('attribute1')
                        ->limit(50)
                        ->get();

        return response()->json(['data' => $contracts]);
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
            $flexValue = (new GLCostCenterV)->LOVResult($setName, $parent, $setValue, $text);
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
            $flexValue = (new GLBudgetProductV)->LOVResult($setName, $parent, $setValue, $text);
        }
        if ($setName == 'OAG_GL_BUDGET_ACTIVITY') {
            $flexValue = (new GLBudgetActivityV)->LOVResult($setName, $parent, $setValue, $text);
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
