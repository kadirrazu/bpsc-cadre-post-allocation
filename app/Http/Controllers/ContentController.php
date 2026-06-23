<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

use App\Models\Post;
use App\Models\Cadre;
use App\Models\Candidate;

use Barryvdh\DomPDF\Facade\Pdf;

class ContentController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('cadre_code', 'ASC')->get();
        $cadres = Cadre::orderBy('cadre_code', 'ASC')->get();

        return view('dashboard', [
            'posts' => $posts,
            'cadres' => $cadres,
        ]);
    }

    public function candidates()
    {
        $candidates = Candidate::all();

        return view('candidates', [
            'candidates' => $candidates,
        ]);
    }

    public function allocations()
    {
        $candidates = Candidate::where('assigned_cadre', '!=', '')->orderBy('assigned_cadre', 'ASC')->get();

        return view('allocations', [
            'candidates' => $candidates,
        ]);
    }

    public function allocations_print()
    {
        $candidates = Candidate::where('assigned_cadre', '!=', '')->orderBy('assigned_cadre', 'ASC')->get();

        return view('allocations-print', [
            'candidates' => $candidates,
        ]);
    }


    public function reports()
    {
        return view('report-print');
    }


    public function processingMenu()
    {
        return view('data-processing-menu');
    }


    public function report_download_txt()
    {

        $cadres = DB::table('candidates')->join('cadres', 'cadres.cadre_code', '=', 'candidates.assigned_cadre')->orderBy('candidates.assigned_cadre', 'ASC')->get();

        $non_cadres = Candidate::whereNull('assigned_cadre')->orderBy('reg', 'ASC')->get();

        $cadreGroups = $cadres->groupBy('assigned_cadre');

        $txt = '';

        $txt .= '-------- BCS CADRE POST ALLOCATION SYSTEM --------' . "\r\n";
        $txt .= 'Report Generation Timestamp: ' . date('d-m-Y H:i:s') . "\r\n\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n";

        $txt .= '| Total Allocated Cadre Count                     | ' . str_pad($cadreGroups->count(), 7, " ", STR_PAD_RIGHT) . " |\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n";

        $txt .= '| Total Candidates Allocated in Cadre Posts       | ' . str_pad($cadres->count(), 7, " ", STR_PAD_RIGHT) . " |\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n";

        $txt .= '| Total Non Allocated Candidates                  | ' . str_pad($non_cadres->count(), 7, " ", STR_PAD_RIGHT). " |\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n";

        $txt .= '| Sum of Cadre + Non Allocated                    | ' . str_pad($cadres->count() + $non_cadres->count(), 7, " ", STR_PAD_RIGHT) . " |\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n\r\n";

        $txt .= 'Cadre Wise Allocations: ' . "\r\n\r\n";

        $serial = 1;

        foreach( $cadreGroups as $cadreCode => $candidates ){

            $txt .= $serial .': '. $cadreCode . ' - ' . get_cadre_details_by_code($cadreCode) . ' [Total = '.$candidates->count().']' . "\r\n\r\n";

            $cadreType = Cadre::where('cadre_code', $cadreCode)->first()->cadre_type;

            if( $cadreType == null ){
                $cadreType = 'NULL';
            }

            if( $cadreType == 'GG' ){
                $candidates = $candidates->sortBy('general_merit_position', SORT_NUMERIC);
            }
            else{
                $candidates = $candidates->sortBy('technical_merit_position', SORT_NUMERIC);
            }

            $count = 0;

            foreach( $candidates as $cand ){

                $txt .= $cand->reg;

                if($count !== $candidates->count() - 1){
                    $txt .= '  ';
                }

                $count++;

                if($count === $candidates->count()){
                    $txt .= '  ' . 'Total = ' . $count;
                }

            }

            $txt .= "\r\n\r\n\r\n\r\n";

            $serial++;

        }

        $content = $txt;

        $fileName = 'allocation-result-all-status-' . date('d-m-Y-H-i-s') . '.txt';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName, [
            'Content-Type' => 'text/plain',
        ]);

    }


    public function report_download_txt_only_merit_position()
    {

        $cadres = DB::table('candidates')->join('cadres', 'cadres.cadre_code', '=', 'candidates.assigned_cadre')->orderBy('candidates.assigned_cadre', 'ASC')->get();

        $non_cadres = Candidate::whereNull('assigned_cadre')->orderBy('reg', 'ASC')->get();

        $cadreGroups = $cadres->groupBy('assigned_cadre');

        $txt = '';

        $txt .= '-------- BCS CADRE POST ALLOCATION SYSTEM --------' . "\r\n";
        $txt .= 'Report Generation Timestamp: ' . date('d-m-Y H:i:s') . "\r\n\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n";

        $txt .= '| Total Allocated Cadre Count                     | ' . str_pad($cadreGroups->count(), 7, " ", STR_PAD_RIGHT) . " |\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n";

        $txt .= '| Total Candidates Allocated in Cadre Posts       | ' . str_pad($cadres->count(), 7, " ", STR_PAD_RIGHT) . " |\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n";

        $txt .= '| Total Non Allocated Candidates                  | ' . str_pad($non_cadres->count(), 7, " ", STR_PAD_RIGHT). " |\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n";

        $txt .= '| Sum of Cadre + Non Allocated                    | ' . str_pad($cadres->count() + $non_cadres->count(), 7, " ", STR_PAD_RIGHT) . " |\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n\r\n";

        $txt .= 'Cadre Wise Allocations: ' . "\r\n\r\n";

        $serial = 1;

        foreach( $cadreGroups as $cadreCode => $candidates ){

            $txt .= $serial .': '. $cadreCode . ' - ' . get_cadre_details_by_code($cadreCode) . ' [Total = '.$candidates->count().']' . "\r\n\r\n";

            $cadreType = Cadre::where('cadre_code', $cadreCode)->first()->cadre_type;

            if( $cadreType == null ){
                $cadreType = 'NULL';
            }

            if( $cadreType == 'GG' ){
                $candidates = $candidates->sortBy('general_merit_position', SORT_NUMERIC);
            }
            else{
                $candidates = $candidates->sortBy('technical_merit_position', SORT_NUMERIC);
            }

            $count = 0;

            foreach( $candidates as $cand ){

                
                if( $cadreType == 'GG' ){
                    $txt .= $cand->general_merit_position . ' ('. $cand->assigned_status .')';
                }
                else{
                    $txt .= !empty($cand->technical_merit_position) ? $cand->technical_merit_position : $cand->general_merit_position . ' ('. $cand->assigned_status .')';
                }

                if($count !== $candidates->count() - 1){
                    $txt .= '  ';
                }

                $count++;

                if($count === $candidates->count()){
                    $txt .= '  ' . 'Total = ' . $count;
                }

            }

            $txt .= "\r\n\r\n\r\n\r\n";

            $serial++;

        }

        $content = $txt;

        $fileName = 'allocation-result-all-status-' . date('d-m-Y-H-i-s') . '.txt';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName, [
            'Content-Type' => 'text/plain',
        ]);

    }


    public function report_download_txt_quota()
    {

        $cadres = DB::table('candidates')->join('cadres', 'cadres.cadre_code', '=', 'candidates.assigned_cadre')->orderBy('candidates.assigned_cadre', 'ASC')->get();

        $non_cadres = Candidate::whereNull('assigned_cadre')->orderBy('reg', 'ASC')->get();

        $cadreGroups = $cadres->groupBy('assigned_cadre');

        $txt = '';

        $txt .= '-------- BCS CADRE POST ALLOCATION SYSTEM --------' . "\r\n";
        $txt .= 'Report Generation Timestamp: ' . date('d-m-Y H:i:s') . "\r\n\r\n";

        $txt .= 'Quota Allocation in Differet Cadres: ' . "\r\n\r\n";

        $serial = 1;

        $totalQuotaCandidates = 0;

        foreach( $cadreGroups as $cadreCode => $candidates ){

            $txt .= $serial .': '. $cadreCode . ' - ' . get_cadre_details_by_code($cadreCode) . "\r\n\r\n";

            $cadreType = Cadre::where('cadre_code', $cadreCode)->first()->cadre_type;

            if( $cadreType == null ){
                $cadreType = 'NULL';
            }

            if( $cadreType == 'GG' ){
                $candidates = $candidates->sortBy('general_merit_position', SORT_NUMERIC);
            }
            else{
                $candidates = $candidates->sortBy('technical_merit_position', SORT_NUMERIC);
            }

            $count = 0;
            $outerLoop = 0;

            foreach( $candidates as $cand ){

                if( !($cand->assigned_status == 'MQ') )
                {

                    $txt .= $cand->reg . ' ('. $cand->assigned_status .')';

                    if($count !== $candidates->count() - 1){
                        $txt .= '  ';
                    }

                    $count++;

                }

                $outerLoop++;

                if($outerLoop === $candidates->count()){
                    $txt .= '  ' . 'Total = ' . $count;
                }

            }

            $txt .= "\r\n\r\n\r\n\r\n";

            $totalQuotaCandidates += $count;

            $serial++;

        }

        $txt .= 'Total Quota Candidates in different Cadres: ' . $totalQuotaCandidates . "\r\n\r\n";

        $content = $txt;

        $fileName = 'allocation-result-only-quota-status-' . date('d-m-Y-H-i-s') . '.txt';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName, [
            'Content-Type' => 'text/plain',
        ]);

    }


    public function report_download_txt_with_all_status()
    {

        $cadres = DB::table('candidates')->join('cadres', 'cadres.cadre_code', '=', 'candidates.assigned_cadre')->orderBy('candidates.assigned_cadre', 'ASC')->get();

        $non_cadres = Candidate::whereNull('assigned_cadre')->orderBy('reg', 'ASC')->get();

        $cadreGroups = $cadres->groupBy('assigned_cadre');

        $txt = '';

        $txt .= '-------- BCS CADRE POST ALLOCATION SYSTEM --------' . "\r\n";
        $txt .= 'Report Generation Timestamp: ' . date('d-m-Y H:i:s') . "\r\n\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n";

        $txt .= '| Total Allocated Cadre Count                     | ' . str_pad($cadreGroups->count(), 7, " ", STR_PAD_RIGHT) . " |\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n";

        $txt .= '| Total Candidates Allocated in Cadre Posts       | ' . str_pad($cadres->count(), 7, " ", STR_PAD_RIGHT) . " |\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n";

        $txt .= '| Total Non Allocated Candidates                  | ' . str_pad($non_cadres->count(), 7, " ", STR_PAD_RIGHT). " |\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n";

        $txt .= '| Sum of Cadre + Non Allocated                    | ' . str_pad($cadres->count() + $non_cadres->count(), 7, " ", STR_PAD_RIGHT) . " |\r\n";

        $txt .= ' -----------------------------------------------------------' . "\r\n\r\n";

        $txt .= 'Cadre Wise Allocations (with ASSIGNED STATUS): ' . "\r\n\r\n";

        $serial = 1;

        foreach( $cadreGroups as $cadreCode => $candidates ){

            $txt .= $serial .': '. $cadreCode . ' - ' . get_cadre_details_by_code($cadreCode) . ' [Total = '.$candidates->count().']' . "\r\n\r\n";

            $cadreType = Cadre::where('cadre_code', $cadreCode)->first()->cadre_type;

            if( $cadreType == null ){
                $cadreType = 'NULL';
            }

            if( $cadreType == 'GG' ){
                $candidates = $candidates->sortBy('general_merit_position', SORT_NUMERIC);
            }
            else{
                $candidates = $candidates->sortBy('technical_merit_position', SORT_NUMERIC);
            }

            $count = 0;

            foreach( $candidates as $cand ){

                $txt .= $cand->reg . ' (' . $cand->assigned_status . ')';

                if($count !== $candidates->count() - 1){
                    $txt .= '  ';
                }

                $count++;

                if($count === $candidates->count()){
                    $txt .= '  ' . 'Total = ' . $count;
                }

            }

            $txt .= "\r\n\r\n\r\n\r\n";

            $serial++;

        }

        $content = $txt;

        $fileName = 'allocation-result-all-status-' . date('d-m-Y-H-i-s') . '.txt';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName, [
            'Content-Type' => 'text/plain',
        ]);


    }

    public function report_download_txt_non_cadre()
    {

        $non_cadres = Candidate::whereNull('assigned_cadre')->orderBy('reg', 'ASC')->get();

        $txt = '';

        $txt .= '-------- BCS CADRE POST ALLOCATION SYSTEM --------' . "\r\n";
        $txt .= 'Report Generation Timestamp: ' . date('d-m-Y H:i:s') . "\r\n\r\n";

        $txt .= 'Un-allocated Candidate List [for Non Cadre]: ' . "\r\n\r\n";

        $serial = 1;

        $totalQuotaCandidates = 0;

        $count = 0;

        foreach( $non_cadres as $cand ){

            $txt .= $cand->reg;

            if($count !== $non_cadres->count() - 1){
                $txt .= '  ';
            }

            $count++;

            if($count === $non_cadres->count()){
                $txt .= '  ' . 'Total = ' . $count;
            }

        }

        $txt .= "\r\n\r\n\r\n\r\n";

        $txt .= 'Total Un-allocated Candidates [for Non Cadre]: ' . $count . "\r\n\r\n";

        $content = $txt;

        $fileName = 'allocation-result-only-non-cadre-status-' . date('d-m-Y-H-i-s') . '.txt';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName, [
            'Content-Type' => 'text/plain',
        ]);

    }


    public function download_allocation_pdf() {

        $allocations = DB::table('candidates')
                    ->join('cadres', 'cadres.cadre_code', '=', 'candidates.assigned_cadre')
                    ->whereNotNull('candidates.assigned_cadre')
                    ->orderBy('candidates.assigned_cadre', 'ASC')
                    ->orderBy('candidates.general_merit_position', 'ASC')
                    ->orderBy('candidates.technical_merit_position', 'ASC')
                    ->get();
    
        //$pdf = Pdf::loadView('reports.allocation-result-all-status', ['allocations' => $allocations]);

        //$pdf->setOptions(['isPhpEnabled' => true]);
    
        //return $pdf->setPaper('a4', 'landscape')->stream( 'allocation-result-all-status-' . date('d-m-Y-H-i-s'). '.pdf' );

        return view('reports.allocation-result-all-status', ['allocations' => $allocations]);

    }

    public function download_allocation_pdf_all_cadre_without_tt() {

        $allocations = DB::table('candidates')
                    ->join('cadres', 'cadres.cadre_code', '=', 'candidates.assigned_cadre')
                    ->whereNotNull('candidates.assigned_cadre')
                    ->where('candidates.cadre_category', '!=', 'TT')
                    ->orderBy('candidates.general_merit_position', 'ASC')
                    ->orderBy('candidates.technical_merit_position', 'ASC')
                    ->get();

        return view('reports.allocation-result-all-cadre-merit-wise', [
            'allocations' => $allocations,
        ]);

    }

    public function download_allocation_pdf_all_cadre_only_tt() {

        $allocations = DB::table('candidates')
                    ->join('cadres', 'cadres.cadre_code', '=', 'candidates.assigned_cadre')
                    ->where('candidates.cadre_category', '=', 'TT')
                    ->orWhere('candidates.cadre_category', '=', 'GT')
                    ->whereNotNull('candidates.assigned_cadre')
                    ->orderBy('candidates.technical_merit_position', 'ASC')
                    ->get();

        return view('reports.allocation-result-all-cadre-merit-wise-tech', [
            'allocations' => $allocations,
        ]);

    }

    /**Tabulation */
    public function download_allocation_pdf_all_cadre_without_tt_tabulation() {

        $allocations = DB::table('candidates')
                    //->join('cadres', 'cadres.cadre_code', '=', 'candidates.assigned_cadre')
                    ->where('candidates.general_merit_position', '!=', '')
                    ->where('candidates.cadre_category', '!=', 'TT')
                    ->orderBy('candidates.general_merit_position', 'ASC')
                    ->get();

        return view('reports.allocation-result-all-cadre-merit-wise-tabulation', [
            'allocations' => $allocations,
        ]);

    }

    public function download_allocation_pdf_all_cadre_only_tt_tabulation() {

        $allocations = DB::table('candidates')
                    //->join('cadres', 'cadres.cadre_code', '=', 'candidates.assigned_cadre')
                    ->where('candidates.technical_merit_position', '!=', 0)
                    ->where('candidates.cadre_category', '!=', 'GG')
                    ->orderBy('candidates.technical_merit_position', 'ASC')
                    ->get();

        return view('reports.allocation-result-all-cadre-merit-wise-tech-tabulation', [
            'allocations' => $allocations,
        ]);

    }

    /**Only Quota*/
    public function download_allocation_pdf_all_cadre_without_tt_only_quota() {

        $allocations = DB::table('candidates')
                    ->join('cadres', 'cadres.cadre_code', '=', 'candidates.assigned_cadre')
                    ->whereNotNull('candidates.assigned_cadre')
                    ->where('candidates.cadre_category', '!=', 'TT')
                    ->where('candidates.assigned_status', '!=', 'MQ')
                    ->orderBy('candidates.general_merit_position', 'ASC')
                    ->orderBy('candidates.technical_merit_position', 'ASC')
                    ->get();

        return view('reports.allocation-result-all-cadre-merit-wise-only-quota', [
            'allocations' => $allocations,
        ]);

    }

    public function download_allocation_pdf_all_cadre_only_tt_only_quota() {

        $allocations = DB::table('candidates')
                    ->join('cadres', 'cadres.cadre_code', '=', 'candidates.assigned_cadre')
                    ->whereNotNull('candidates.assigned_cadre')
                    ->where('candidates.cadre_category', '=', 'TT')
                    ->orWhere('candidates.cadre_category', '=', 'GT')
                    ->where('candidates.assigned_status', '!=', 'MQ')
                    ->orderBy('candidates.general_merit_position', 'ASC')
                    ->orderBy('candidates.technical_merit_position', 'ASC')
                    ->get();

        return view('reports.allocation-result-all-cadre-merit-wise-tech-only-quota', [
            'allocations' => $allocations,
        ]);

    }

    /**Cadre Wise All Tech Passed Candidate List */
    public function report_pdf_file_cadre_wise_all_tech_passed() {

        $candidates = DB::table('candidates')
                    ->Where('candidates.cadre_category', '!=', 'GG')
                    ->orderBy('candidates.technical_merit_position', 'ASC')
                    ->get();

        $cadres = DB::table('cadres')
                    ->Where('cadres.cadre_type', '!=', 'GG')
                    ->orderBy('cadres.cadre_code', 'ASC')
                    ->get();

        return view('reports.report-pdf-file-cadre-wise-all-tech-passed', [
            'candidates' => $candidates,
            'cadres' => $cadres,
        ]);

    }

    public function printPostTable()
    {

        $posts = Post::orderBy('cadre_code', 'ASC')->get();
        $cadres = Cadre::orderBy('cadre_code', 'ASC')->get();

        return view('print-post-table', [
            'posts' => $posts,
            'cadres' => $cadres,
        ]);

    }


}
