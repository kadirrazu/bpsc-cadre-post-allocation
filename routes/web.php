<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\ContentController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\ShiftingAllocationController;
use App\Http\Controllers\NMAllocationController;

//Helper Function
function get_cadre_abbr($cadre_code)
{
    return DB::table('cadres')->where('cadre_code', $cadre_code)->first()->cadre_abbr ?? null;
}

function get_cadre_code($cadre_abbr)
{
    return DB::table('cadres')->where('cadre_abbr', $cadre_abbr)->first()->cadre_code ?? null;
}

function get_cadre_name($cadre_code)
{
    return DB::table('cadres')->where('cadre_code', $cadre_code)->first()->cadre_name ?? null;
}

function get_cadre_type($cadre_code)
{
    return DB::table('cadres')->where('cadre_code', $cadre_code)->first()->cadre_type ?? null;
}

function get_cadre_details_by_code($cadre_code)
{
    $cadre_abbr = DB::table('cadres')->where('cadre_code', $cadre_code)->first()->cadre_abbr;

    $cadre_name = DB::table('cadres')->where('cadre_code', $cadre_code)->first()->cadre_name;

    return $cadre_abbr . ' - ' . $cadre_name;
}


Route::get('/', [ContentController::class, 'index']);
Route::get('/candidates', [ContentController::class, 'candidates']);
Route::get('/allocations', [ContentController::class, 'allocations']);
Route::get('/print-allocation', [ContentController::class, 'allocations_print']);
Route::get('/allocation/run', [AllocationController::class, 'runAllocation']);
Route::get('/allocation/run-shifting', [ShiftingAllocationController::class, 'runShiftingAllocation']);
Route::get('/allocation/run-nm-fill', [NMAllocationController::class, 'runNMAllocation']);

Route::get('/reports', [ContentController::class, 'reports']);
Route::get('/processing-menu', [ContentController::class, 'processingMenu']);
Route::get('/report-text-file', [ContentController::class, 'report_download_txt']);
Route::get('/report-text-file-quota', [ContentController::class, 'report_download_txt_quota']);
Route::get('/report-text-file-with-all-status', [ContentController::class, 'report_download_txt_with_all_status']);
Route::get('/report-text-file-non-cadre', [ContentController::class, 'report_download_txt_non_cadre']);
Route::get('/report-text-file-by-merit-position', [ContentController::class, 'report_download_txt_only_merit_position']);
Route::get('/report-pdf-file-cadre-wise', [ContentController::class, 'download_allocation_pdf']);

Route::get('/report-pdf-file-merit-wise-all-cadre-without-tt', [ContentController::class, 'download_allocation_pdf_all_cadre_without_tt']);
Route::get('/report-pdf-file-merit-wise-all-cadre-only-tt', [ContentController::class, 'download_allocation_pdf_all_cadre_only_tt']);

Route::get('/report-pdf-file-merit-wise-all-cadre-without-tt-only-quota', [ContentController::class, 'download_allocation_pdf_all_cadre_without_tt_only_quota']);
Route::get('/report-pdf-file-merit-wise-all-cadre-only-tt-only-quota', [ContentController::class, 'download_allocation_pdf_all_cadre_only_tt_only_quota']);

Route::get('/report-pdf-file-merit-wise-all-cadre-without-tt-tabulation', [ContentController::class, 'download_allocation_pdf_all_cadre_without_tt_tabulation']);
Route::get('/report-pdf-file-merit-wise-all-cadre-only-tt-tabulation', [ContentController::class, 'download_allocation_pdf_all_cadre_only_tt_tabulation']);

Route::get('/report-pdf-file-cadre-wise-all-tech-passed', [ContentController::class, 'report_pdf_file_cadre_wise_all_tech_passed']);

Route::get('/print_post_table', [ContentController::class, 'printPostTable']);
