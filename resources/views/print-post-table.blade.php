<x-layout-dashboard-print>

<!-- Contents Starts Here -->

    <main role="main" class="container">

      <div class="starter-template">
      
        <table class="table table-striped table-bordered post-count-table">

            <tr class="text-center">
                <th>Sr.</th>
                <th>Cadre Code</th>
                <th>Cadre Abbr</th>
                <th style="max-width: 350px;">Cadre Name</th>
                <th>Cadre Type</th>
                <th>
                  Total Post<br>
                  <span class="text-danger"> [EMPTY]</span>
                </th>
                <th>MQ Post<br>
                  <span class="text-danger"> [EMPTY]</span>
                </th>
                <th>CFF Post<br>
                  <span class="text-danger"> [EMPTY]</span>
                </th>
                <th>EM Post<br>
                  <span class="text-danger"> [EMPTY]</span>
                </th>
                <th>PHC Post</th>
                <th>SHIFT & NM</th>
                <th>
                  Allocated Post <br>
                  <span class="text-info"> [EMPTY]</span>
                </th>
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

              $shiftingSum = 0;
              $nmSum = 0;

            @endphp

            @foreach( $posts as $post )

            <tr class="text-center">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $post->cadre_code }}</td>
                <td class="text-center">
                  {{ $cadres->where('cadre_code', $post->cadre_code)->first()->cadre_abbr }}
                </td>
                <td class="text-start" style="max-width: 350px;">
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
                    $shiftingSum += $shifted;
                  ?>
                </td>
                <td>
                  {{ $post->allocated_post_count ?? '0' }}<br>
                  <span class="text-info"> [ {{ (int)$post->total_post - (int)$post->allocated_post_count }} ]</span>
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
                  {{ $shiftingSum }}
                </th>
                <th>
                  {{ $allocated_sum }}
                </th>
            </tr>
            
        </table>

      </div>

    </main><!-- /.container -->

<!-- Contents Ends Here -->

</x-layout-dashboard-print>