<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Cadre;
use App\Models\Candidate;

use Carbon\Carbon;

class AllocationController extends Controller
{
    public function runAllocation()
    {
        echo 'You are here to allocate.';

        $this->generateAllocationQueue();
        //$this->sortAllocationQueues();
        //$this->getPostAvaiability();
        //$this->allocateCadre();
        //$this->solveMultipleAllocations();
    }

    private function generateAllocationQueue()
    {
        $queues = [];

        $candidates = Candidate::orderBy('general_merit_position', 'ASC')->get();

        foreach( $candidates as $candidate )
        {
            if( $candidate->allocation_status != 'final' )
            {
                $choices = $this->parse_choices_list( $candidate->choice_list );

                foreach( $choices as $choice )
                {
                    $cadre_code = $this->get_cadre_code_by_abbr( $choice );
                    echo $cadre_code;
                }
            }
        }
        
        return $queues;
    }



    //Utilities : Helper Functions
    private function get_cadre_code_by_abbr( $abbr )
    {
        return Cadre::where('cadre_abbr', $abbr)->first()->cadre_code;
    }

    private function parse_choices_list(string $s)
    {
        if (trim($s) === '') return [];
        // split on one-or-more whitespace
        $parts = preg_split('/\s+/', trim($s));
        return array_values(array_filter(array_map('trim', $parts), fn($v) => $v !== ''));
    }

    //Convert technical_merit_position representation into a subject => position map
    private function technical_map( $candidate )
    {
        // if already an assoc map like ['ROME' => 11], return as-is
        if( empty($candidate['technical_merit_position']) ) return [];

        $t = $candidate['technical_merit_position'];

        // else try to sanitize
        $out = [];

        foreach ($t as $k => $v) $out[strtoupper($k)] = intval($v);

        return $out;
    }

    /**
     * Convert technical merit to readable format:
     *   [ 'EEE' => 54, 'CIV' => 83 ]
     */
    private function formatTechnicalMerit($candidate) {
        if (!isset($candidate['technical_merit_position'])) return [];

        $out = [];
        foreach ($candidate['technical_merit_position'] as $key => $val) {
            $out[$key] = $val;
        }
        return $out;
    }

    //Get the best possible cadre's position or abbr
    private function get_best_choice($foundIn, $rawChoiceList, $abbr = true)
    {
        $best = PHP_INT_MAX;
        $bestAbbr = null;
        foreach ($foundIn as $found) {
            $position = array_search($found, $rawChoiceList, true);
            if ($position === false) continue;
            if ($position < $best) {
                $best = $position;
                $bestAbbr = $rawChoiceList[$position];
            }
        }

        if ($abbr) {
            return $bestAbbr;
        }

        return ($best === PHP_INT_MAX) ? null : $best;
    }

    //Helper: to check if reg already allocated in $allocated for any cadre
    private function is_already_allocated($allocated, $reg) {
        foreach ($allocated as $cad => $list) {
            foreach ($list as $a) {
                if ($a['reg_no'] === $reg) return true;
            }
        }
        return false;
    }


    private function is_already_allocated_in_this_cadre($allocated, $reg, $cadre) {
        foreach ($allocated as $cad => $list) {
            if( $cad == $cadre ){
                foreach ($list as $a) {
                    if ($a['reg_no'] === $reg) return true;
                }
            }
        }
        return false;
    }

    private function get_choice_comparison_result($foundIn, $rawChoiceList)
    {
        $choiceAbbr = null;
        $index = 99999;

        foreach( $foundIn as $found )
        {

            $position = array_search($found, $rawChoiceList);

            if( $position < $index )
            {
                $index = $position;
            }

        }
        
        return $rawChoiceList[$index] ?? null;
    }

    private function get_choice_comparison_result_index($foundIn, $rawChoiceList)
    {
        $choiceAbbr = null;
        $index = 99999;

        foreach( $foundIn as $found )
        {

            $position = array_search($found, $rawChoiceList);

            if( $position < $index )
            {
                $index = $position;
            }

        }
        
        return $index ?? null;
    }

    //End of Helper Functions

} //End of the class
