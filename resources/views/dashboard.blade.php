<x-layout-dashboard>

<!-- Contents Starts Here -->

    <main role="main" class="container">

      <div class="starter-template">
      
        <table class="table table-striped table-bordered">

            <tr class="text-center">
                <th>Sr.</th>
                <th>Cadre Code</th>
                <th>Cadre Abbr</th>
                <th>Cadre</th>
                <th>Cadre Type</th>
                <th>Total Post</th>
                <th>MQ</th>
                <th>CFF</th>
                <th>EM</th>
                <th>PHC</th>
                <th>SHIFTED</th>
                <th>NM</th>
                <th>Allocated Post</th>
            </tr>

            @php

              $total_sum = 0; 
              $total_left_sum = 0; 
              $mq_sum = 0; 
              $mq_left_sum = 0; 
              $cff_sum = 0; 
              $cff_left_sum = 0; 
              $em_sum = 0; 
              $em_left_sum = 0; 
              $phc_sum = 0; 
              $phc_left_sum = 0; 
              $allocated_sum = 0; 

            @endphp

            @foreach( $posts as $post )

            <tr class="text-center">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $post->cadre_code }}</td>
                <td class="text-center">
                  {{ $cadres->where('cadre_code', $post->cadre_code)->first()->cadre_abbr }}
                </td>
                <td class="text-start">
                  {{ $cadres->where('cadre_code', $post->cadre_code)->first()->cadre_name }}
                </td>
                <td>
                  {{ $cadres->where('cadre_code', $post->cadre_code)->first()->cadre_type }}
                </td>
                <td>
                  {{ $post->total_post }}<br>
                  <span class="text-danger"> [ {{ $post->total_post_left }} ]</span>
                  @php $total_sum += $post->total_post @endphp
                  @php $total_left_sum += $post->total_post_left @endphp
                </td>
                <td>
                  {{ $post->mq_post }}<br>
                  <span class="text-danger"> [ {{ $post->mq_post_left }} ]</span>
                  @php $mq_sum += $post->mq_post @endphp
                  @php $mq_left_sum += $post->mq_post_left @endphp
                </td>
                <td>
                  {{ $post->cff_post }}<br>
                  <span class="text-danger"> [ {{ $post->cff_post_left }} ]</span>
                  @php $cff_sum += $post->cff_post @endphp
                  @php $cff_left_sum += $post->cff_post_left @endphp
                </td>
                <td>
                  {{ $post->em_post }}<br>
                  <span class="text-danger"> [ {{ $post->em_post_left }} ]</span>
                  @php $em_sum += $post->em_post @endphp
                  @php $em_left_sum += $post->em_post_left @endphp
                </td>
                <td>
                  {{ $post->phc_post }}<br>
                  <span class="text-danger"> [ {{ $post->phc_post_left }} ]</span>
                  @php $phc_sum += $post->phc_post @endphp
                  @php $phc_left_sum += $post->phc_post_left @endphp
                </td>
                <td>
                  <?php 
                    $shifted = \App\Models\Candidate::whereNotNull('assigned_cadre')->where('assigned_cadre', '=', $post->cadre_code)->where('general_status', 'LIKE', '%SHIFT%')->count(); 
                    echo $shifted;
                  ?>
                </td>
                <td>
                  <?php 
                    $nm =  \App\Models\Candidate::whereNotNull('assigned_cadre')->where('assigned_cadre', '=', $post->cadre_code)->where('assigned_status', '=', 'NM')->count(); 
                    echo $nm;
                  ?>
                </td>
                <td>
                  {{ $post->allocated_post_count ?? '0' }}
                  @php $allocated_sum += $post->allocated_post_count @endphp
                </td>
            </tr>

            @endforeach


            <tr class="text-center">
                <th>-</th>
                <th>-</th>
                <th>-</th>
                <th>-</th>
                <th>-</th>
                <th>
                  {{ $total_sum }}<br>
                  <span class="text-danger"> [ {{ $total_left_sum }} ]</span>
                </th>
                <th>
                  {{ $mq_sum }}<br>
                  <span class="text-danger"> [ {{ $mq_left_sum }} ]</span>
                </th>
                <th>
                  {{ $cff_sum }}<br>
                  <span class="text-danger"> [ {{ $cff_left_sum }} ]</span>
                </th>
                <th>
                  {{ $em_sum }}<br>
                  <span class="text-danger"> [ {{ $em_left_sum }} ]</span>
                </th>
                <th>
                  {{ $phc_sum }}<br>
                  <span class="text-danger"> [ {{ $phc_left_sum }} ]</span>
                </th>
                <th>
                  -
                </th>
                <th>
                  -
                </th>
                <th>
                  {{ $allocated_sum }}
                </th>
            </tr>
            
        </table>

        <hr>

        <a href="{{ url('/print_post_table') }}" class="btn btn-secondary" target="_blank">Print this Table</a>

      </div>

    </main><!-- /.container -->

<!-- Contents Ends Here -->

</x-layout-dashboard>