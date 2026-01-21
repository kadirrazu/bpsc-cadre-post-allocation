<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

use App\Models\Post;
use App\Models\Cadre;
use App\Models\Candidate;

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

            $candidates = $candidates->sortBy('general_merit_position', SORT_NUMERIC);

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

        $fileName = 'allocation-result-' . date('d-m-Y-H-i-s') . '.txt';

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

            $candidates = $candidates->sortBy('general_merit_position', SORT_NUMERIC);

            $count = 0;
            $outerLoop = 0;

            foreach( $candidates as $cand ){

                if( !($cand->assigned_status == 'MQ' || $cand->assigned_status == 'NM') )
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

        $fileName = 'allocation-result-' . date('d-m-Y-H-i-s') . '.txt';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName, [
            'Content-Type' => 'text/plain',
        ]);

    }


}
