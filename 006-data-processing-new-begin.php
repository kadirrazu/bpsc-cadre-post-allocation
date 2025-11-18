<?php 

// ----------------- ASSUMES THESE ARE INCLUDED/DEFINED -----------------
// $general_cadres, $technical_cadres, $post_available, $candidates
// (You provided these in earlier messages)

// ----------------- PREP -----------------

// Index posts by short cadre name for O(1) lookup
$postsByCadre = [];
foreach ($post_available as $code => $p) {
    $postsByCadre[$p['cadre']] = $code;
}

// Normalize candidate choices and ensure structures exist
foreach ($candidates as &$candidate) {
    // ensure quota array exists
    if (!isset($candidate['quota']) || !is_array($candidate['quota'])) {
        $candidate['quota'] = ['CFF' => false, 'EM' => false, 'PHC' => false];
    }
    // ensure technical merit structure exists
    if (!isset($candidate['technical_merit_position']) || !is_array($candidate['technical_merit_position'])) {
        $candidate['technical_merit_position'] = [];
    }
    // normalize choices as uppercase tokens
    if (isset($candidate['choice_list'])) {
        $candidate['choices'] = array_values(array_filter(array_map('strtoupper', array_map('trim', explode(' ', $candidate['choice_list'])))));
    } else {
        $candidate['choices'] = [];
    }
}
unset($candidate);

// Copy working posts remaining
$post_remaining = $post_available;

// ----------------- 1) GENERAL ALLOCATION (GG + GT's general choices) -----------------

// Build list of candidates to consider for GENERAL allocation
$gen_candidates = [];

foreach ($candidates as $cand) {
    // Only GG and GT candidates are considered in general flow
    if ($cand['cadre_category'] === 'GG' || $cand['cadre_category'] === 'GT') {
        // collect only general choices (preserve original order)
        $gen_choices = [];
        foreach ($cand['choices'] as $ch) {
            if (isset($general_cadres['GENERAL'][$ch])) $gen_choices[] = $ch;
        }
        // keep candidates who have at least one general choice
        if (!empty($gen_choices)) {
            $copy = $cand;
            $copy['gen_choices'] = $gen_choices;
            $gen_candidates[] = $copy;
        }
    }
}

// Sort general candidates by general merit (ascending: 1 best)
usort($gen_candidates, function($a, $b) {
    $aPos = $a['general_merit_position'] ?? PHP_INT_MAX;
    $bPos = $b['general_merit_position'] ?? PHP_INT_MAX;
    return $aPos <=> $bPos;
});

// ----------------- 1) TECHNICAL ALLOCATION (TT + GT's technical choices) -----------------

// Build list of candidates to consider for GENERAL allocation
$tec_candidates = [];

foreach ($candidates as $cand) {
    // Only GG and GT candidates are considered in general flow
    if ($cand['cadre_category'] === 'GT' || $cand['cadre_category'] === 'TT') {
        // collect only general choices (preserve original order)
        $tec_choices = [];
        foreach ($cand['choices'] as $ch) {
            if (isset($technical_cadres['TECHNICAL'][$ch])) $tec_choices[] = $ch;
        }
        // keep candidates who have at least one general choice
        if (!empty($tec_choices)) {
            $copy = $cand;
            $copy['tec_choices'] = $tec_choices;
            $tec_candidates[] = $copy;
        }
    }
}

// Sort general candidates by general merit (ascending: 1 best)
usort($tec_candidates, function($a, $b) {
    $aPos = $a['global_tech_merit'] ?? PHP_INT_MAX;
    $bPos = $b['global_tech_merit'] ?? PHP_INT_MAX;
    return $aPos <=> $bPos;
});

$final_allocation_general = [];            // holds all allocations
$assigned_regno = [];              // map reg_no => true when someone gets a post (to prevent double-assign)
$not_assigned_after_general = [];  // to track GT/TT who still eligible for tech flow

$general_reg = [];

// Process general candidates
foreach ($gen_candidates as $cand) {

    $assigned = false;

    foreach ($cand['gen_choices'] as $choice) {

        if (!isset($postsByCadre[$choice])) continue;

        $postCode = $postsByCadre[$choice];

        // MERIT seat: only if candidate has a general_merit_position (GG or GT will have it)
        $canUseMerit = (!empty($cand['general_merit_position'])) && (($post_remaining[$postCode]['MQ'] ?? 0) > 0);

        if ($canUseMerit) {
            $post_remaining[$postCode]['MQ']--;
            $final_allocation_general[] = ['candidate'=>$cand, 'cadre'=>$choice, 'quota'=>'MERIT', 'type'=>'GENERAL'];
            $assigned = true;
            $assigned_regno[$cand['reg_no']] = true;
            $general_reg[] = $cand['reg_no'];
            break;
        }

        // QUOTA (CFF -> EM -> PHC) - candidate may be eligible for one or multiple flags; check in order
        foreach (['CFF','EM','PHC'] as $q) {
            if (!empty($cand['quota'][$q]) && (($post_remaining[$postCode][$q] ?? 0) > 0)) {
                $post_remaining[$postCode][$q]--;
                $final_allocation_general[] = ['candidate'=>$cand, 'cadre'=>$choice, 'quota'=>$q, 'type'=>'GENERAL'];
                $assigned = true;
                $assigned_regno[$cand['reg_no']] = true;
                $general_reg[] = $cand['reg_no'];
                break 2; // assigned via quota, stop candidate's choice loop
            }
        }
        // else try next general choice
    }

    if (!$assigned) {
        // Keep candidate for possible technical allocation later (only if TT/GT had technical choices)
        $final_allocation_general[] = ['candidate'=>$cand, 'cadre'=>null, 'quota'=>null, 'type'=>null];
        // We'll let the tech flow decide if the candidate (GT) can be assigned later
    }
}


$final_allocation_technical = [];            // holds all allocations
$assigned_regno = [];              // map reg_no => true when someone gets a post (to prevent double-assign)
$not_assigned_after_technical = [];  // to track GT/TT who still eligible for tech flow

$technical_reg = [];

// Process general candidates
foreach ($tec_candidates as $cand) {

    $assigned = false;

    foreach ($cand['tec_choices'] as $choice) {

        if (!isset($postsByCadre[$choice])) continue;

        $postCode = $postsByCadre[$choice];

        // MERIT seat: only if candidate has a general_merit_position (GG or GT will have it)
        $canUseMerit = (!empty($cand['global_tech_merit'])) && (($post_remaining[$postCode]['MQ'] ?? 0) > 0);

        if ($canUseMerit) {
            $post_remaining[$postCode]['MQ']--;
            $final_allocation_technical[] = ['candidate'=>$cand, 'cadre'=>$choice, 'quota'=>'MERIT', 'type'=>'TECHNICAL'];
            $assigned = true;
            $assigned_regno[$cand['reg_no']] = true;
            $technical_reg[] = $cand['reg_no'];
            break;
        }

        // QUOTA (CFF -> EM -> PHC) - candidate may be eligible for one or multiple flags; check in order
        foreach (['CFF','EM','PHC'] as $q) {
            if (!empty($cand['quota'][$q]) && (($post_remaining[$postCode][$q] ?? 0) > 0)) {
                $post_remaining[$postCode][$q]--;
                $final_allocation_technical[] = ['candidate'=>$cand, 'cadre'=>$choice, 'quota'=>$q, 'type'=>'TECHNICAL'];
                $assigned = true;
                $assigned_regno[$cand['reg_no']] = true;
                $technical_reg[] = $cand['reg_no'];
                break 2; // assigned via quota, stop candidate's choice loop
            }
        }
        // else try next general choice
    }

    if (!$assigned) {
        // Keep candidate for possible technical allocation later (only if TT/GT had technical choices)
        $final_allocation_technical[] = ['candidate'=>$cand, 'cadre'=>null, 'quota'=>null, 'type'=>null];
        // We'll let the tech flow decide if the candidate (GT) can be assigned later
    }
}

// ---------------------
// 3) RESOLVE GENERAL–TECHNICAL CONFLICTS
// ---------------------

$resolved_general = [];
$resolved_technical = [];

// Index tech allocations by reg_no for quick lookup
$tech_by_reg = [];
foreach ($final_allocation_technical as $t) {
    if (!empty($t['candidate']['reg_no']) && !empty($t['cadre'])) {
        $tech_by_reg[$t['candidate']['reg_no']] = $t;
    }
}

foreach ($final_allocation_general as $g) {

    $reg = $g['candidate']['reg_no'];
    $generalCadre = $g['cadre'];

    // If candidate also exists in TECHNICAL allocated list
    if (isset($tech_by_reg[$reg])) {

        $t = $tech_by_reg[$reg];
        $techCadre = $t['cadre'];

        // Find the order index in original global choice list
        $choices = $g['candidate']['choices'];

        $gen_index = array_search($generalCadre, $choices);
        $tec_index = array_search($techCadre, $choices);

        // SAFETY: Handle cases where a cadre is not found in choices
        if ($gen_index === false) $gen_index = 9999;
        if ($tec_index === false) $tec_index = 9999;

        // KEEP THE ONE WITH EARLIER CHOICE INDEX
        if ($gen_index < $tec_index) {
            // Keep GENERAL → remove TECHNICAL version
            $resolved_general[] = $g; 
            // do not include technical version
            unset($tech_by_reg[$reg]);
        } else {
            // Keep TECHNICAL → remove GENERAL version
            // Only add technical later
            unset($tech_by_reg[$reg]);  // to prevent duplicate push
            // DO NOT add general version
        }

    } else {
        // Candidate only in GENERAL, keep as is
        $resolved_general[] = $g;
    }
}

// Add the remaining technical candidates (those not removed)
foreach ($final_allocation_technical as $t) {
    $reg = $t['candidate']['reg_no'];
    if (!empty($t['cadre']) && !isset($assigned_general[$reg])) {
        $resolved_technical[] = $t;
    }
}

// Remove empty entries caused by unset
$resolved_general = array_values($resolved_general);
$resolved_technical = array_values($resolved_technical);

// Final corrected sets:
$final_general = $resolved_general;
$final_technical = $resolved_technical;

// Unassigned = those with cadre == null
$final_unassigned = array_values(array_filter(
    array_merge($final_general, $final_technical),
    fn($x) => empty($x['cadre'])
));

echo '<h4>General: </h4>';

$i = 1;

foreach($final_general as $gen){
    echo $i . ' - ' . $gen['candidate']['reg_no'] . ' - ' . $gen['cadre'] . ' - ' . $gen['quota'] . ' - ' . $gen['type'] . '<br>';
    $i++;
}

echo '<h4>Technical: </h4>';

$i = 1;

foreach($final_technical as $gen){
    echo $i . ' - ' . $gen['candidate']['reg_no'] . ' - ' . $gen['cadre'] . ' - ' . $gen['quota'] . ' - ' . $gen['type'] . '<br>';
    $i++;
}

var_dump( $final_unassigned  );

/*

echo '<h3>All Candidates: </h3>';

$i = 1;
foreach($candidates as $candidate){
 print( sprintf("%02d", $i) . ' - ' . $candidate['reg_no'] .' - '. $candidate['cadre_category'] .' - '. $candidate['choice_list'] . '<br>');
 $i++;
}

echo '<br>';
echo '<br>';

echo '<h3>General Candidates: </h3>';

$i = 1;

foreach($gen_candidates as $candidate)
{

 echo sprintf("%02d", $i) . ' - ' . $candidate['reg_no'] . ' - ' . $candidate['cadre_category'];

 if(is_array($candidate['gen_choices'])){
    echo ' - ';
    foreach( $candidate['gen_choices'] as $key => $val )
    {
        echo $val . ' ';
    }
    echo '<br>';
 }

 $i++;

}


echo '<br>';
echo '<br>';

echo '<h3>Technical Candidates: </h3>';

$i = 1;

foreach($tec_candidates as $candidate)
{

 echo sprintf("%02d", $i) . ' - ' . $candidate['reg_no'] . ' - ' . $candidate['cadre_category'];

 if(is_array($candidate['tec_choices'])){
    echo ' - ';
    foreach( $candidate['tec_choices'] as $key => $val )
    {
        echo $val . ' ';
    }
    echo '<br>';
 }

 $i++;
 
}

echo '<h3>General Allocation:</h3>';

foreach($final_allocation_general as $allocated){
    echo $allocated['candidate']['reg_no'] . ' - ' . $allocated['candidate']['cadre_category'] . ' - ' . (isset($allocated['cadre']) ? $allocated['cadre'] : 'NON ALLOCATED') . ' - '  . (isset($allocated['quota']) ? $allocated['quota'] : '') . '<br>';
}

echo '<h3>Technical Allocation:</h3>';

foreach($final_allocation_technical as $allocated){
    echo $allocated['candidate']['reg_no'] . ' - ' . $allocated['candidate']['cadre_category'] . ' - ' . (isset($allocated['cadre']) ? $allocated['cadre'] : 'NON ALLOCATED') . ' - '  . (isset($allocated['quota']) ? $allocated['quota'] : '') . '<br>';
}

echo '<h3>Exists in both allocation:</h3>';

foreach($candidates as $candidate){

    if( (in_array($candidate['reg_no'], $general_reg)) && (in_array($candidate['reg_no'], $technical_reg)) )
    {
        echo $candidate['reg_no'] . '<br>';
    }

    // Find the key of the first occurrence of the value
    $key = array_search($candidate['reg_no'], $final_allocation_technical);

    var_dump( $key );

    // If the value is found, remove it using unset()
    if ($key !== false) {
        unset($array[$key]);
    }


}*/

//var_dump( $final_allocation_technical );


die();
