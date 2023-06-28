<?php

namespace Modules\Wallet\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Wallet\Actions\Transaction\ApplyTransactionQueryFilters;
use Modules\Wallet\Entities\Transaction;
use Modules\Wallet\Exports\TransactionsExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TransactionExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param Request $request
     * @param ApplyTransactionQueryFilters $applyTransactionQueryFilters
     * @return JsonResponse
     */
    public function exportExcel(Request $request, ApplyTransactionQueryFilters $applyTransactionQueryFilters): JsonResponse
    {
        $query = $applyTransactionQueryFilters(Transaction::query(), $request->all());

        Excel::store(new TransactionsExport($query), $path = $request->user()->id.'/transactions.xlsx');

        return api()->success(null, [
            'item' => Storage::url($path),
        ]);
    }
}
