<x-layout-dashboard-print>

    <style>
        .container{
            font-size: 10.5pt;
        }

        table {
            width: 100%;
            border-collapse: collapse; /* Combines adjacent borders into a single border */
            margin-top: 10px;
            page-break-inside: auto; /* Allows table to break across pages if needed */
        }
        th, td {
            border: 1px solid #000; /* Sets a solid border for cells */
            padding: 8px; /* Adds some spacing within cells */
            text-align: left;
        }
        thead {
            display: table-header-group; /* Ensures the header repeats on each new page */
        }
        tr {
            page-break-inside: avoid; /* Tries to keep a row from splitting across pages */
        }
    </style>

    <div class="container">

        <div class="row">
            <div class="col-md-12">

                    @php

                        $serial = 1;

                    @endphp

                    <h3 class="mt-3 mb-3 text-info">
                        General & Both Cadre Candidates: [ALL]
                    </h3>

                    <table class="table table-bordered">
                        <tr>
                            <th class="text-center">Serial</th>
                            <!--<th class="text-center">Reg</th>-->
                            <th class="text-center">Cadre</th>
                            <th class="text-center">Quota Applied</th>
                            <th class="text-center">Gen Merit</th>
                            <th class="text-center">Tec Merit</th>
                            <th class="text-center" style="min-width: 110px;">Tech Passed</th>
                            <th class="text-center">Caregory</th>
                            <th class="text-center">Quota Availability</th>
                            <th class="text-center">Choice List</th>
                            <th class="text-center" style="min-width: 150px;">Remarks</th>
                        </tr>

                    @foreach( $allocations as $candidate )

                        @php 
                            $cadre_abbr = get_cadre_abbr( $candidate->assigned_cadre );

                            // The substring to search for and wrap
                            $substring = $cadre_abbr;

                            // The HTML tag to use for wrapping
                            $tag = '<span class="fw-bold text-danger">' . $substring . '</span>';

                            $pattern = "/\b" . htmlspecialchars($substring) . "\b/i";

                        @endphp

                        <tr>
                            <td class="text-center">{{ $serial }}</td>
                            <!--<td class="text-center">{{ $candidate->reg }}</td>-->
                            <td class="text-center">{{ $cadre_abbr }}</td>
                            <td class="text-center">{{ $candidate->assigned_status }}</td>
                            <td class="text-center">
                                {{ !(($candidate->general_merit_position == '') || ($candidate->general_merit_position == 0)) ? $candidate->general_merit_position : '-' }}
                            <td class="text-center">
                                {{ !(($candidate->technical_merit_position == '') || ($candidate->technical_merit_position == 0)) ? $candidate->technical_merit_position : '-' }}
                            </td>
                            <td class="text-start">
                                @php

                                    if( $candidate->technical_passed_cadres != '' && $candidate->technical_passed_cadres != '{}' )
                                    {

                                        $tech_passed = json_decode( $candidate->technical_passed_cadres );

                                        foreach( $tech_passed as $k => $v ){
                                            echo $k .' - '. $v . '<br>';
                                        }

                                    }
                                    else
                                    {
                                        echo '-';
                                    }
                                    

                                @endphp
                            </td>
                            <td class="text-center">{{ $candidate->cadre_category }}</td>
                            <td class="text-center">
                                @php
                                
                                    $quota = json_decode( $candidate->quota_info );

                                    foreach( $quota as $k => $v ){
                                        if( $k == 'CFF' && $v == 1 ){
                                            echo '<span class="quota-disp">*CFF <span>';
                                        }

                                        if( $k == 'EM' && $v == 1 ){
                                            echo '<span class="quota-disp">*EM <span>';
                                        }

                                        if( $k == 'PHC' && $v == 1 ){
                                            echo '<span class="quota-disp">*PHC <span>';
                                        }
                                    }

                                @endphp
                            </td>
                            <td class="text-start">
                                {!! preg_replace( $pattern, $tag, $candidate->choice_list ) !!}
                            </td>

                            <td>{{ $candidate->general_status ?? '' }}</td>
                            
                        </tr>

                        @php 
                        
                            $serial++;

                        @endphp

                    @endforeach

                    </table>

                    <br>

                    <hr>

            </div>
        </div>

    </div>

</x-layout-dashboard-print>