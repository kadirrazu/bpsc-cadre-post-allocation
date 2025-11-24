<x-layout-dashboard>

<!-- Contents Starts Here -->

    <main role="main" class="container">

      <div class="starter-template">

        <h3>Allocation in defferent Cadres:</h3>

        @if( $candidates->count() > 0 )
      
        <table class="table table-striped table-bordered datatable">
            <thead>
              <tr>
                  <th class="text-center">Sr.</th>
                  <th class="text-center">Reg</th>
                  <th class="text-center">Category</th>
                  <th class="text-center">Gen Merit</th>
                  <th class="text-center">Tech Merit</th>
                  <th class="text-center">Technical Passed</th>
                  <th>Choice List</th>
                  <th class="text-center">Quota</th>
              </tr>
            </thead>
            <tbody>

              @foreach( $candidates as $candidate )
              
              <tr>
                  <td class="text-center">{{ $loop->index + 1 }}</td>
                  <td class="text-center">{{ $candidate->reg }}</td>
                  <td class="text-center">{{ $candidate->cadre_category }}</td>
                  <td class="text-center">{{ $candidate->general_merit_position }}</td>
                  <td class="text-center">{{ $candidate->technical_merit_position }}</td>
                  <td class="text-center">
                    @php $tech = json_decode($candidate->technical_passed_cadres ?? '[]') @endphp
                    @foreach($tech as $key => $value )
                    <span class="text-info">{{ $key }} - {{ $value }}</span><br>
                    @endforeach
                  </td>
                  <td>{{ $candidate->choice_list }}</td>
                  <td class="text-center">
                    @php $tech = json_decode($candidate->quota_info ?? '[]') @endphp
                    @foreach($tech as $key => $value )
                      @if( $value == 1 )
                        <span class="text-secondary">{{ $key }}</span><br>
                      @endif
                    @endforeach
                  </td>
              </tr>

              @endforeach

            </tbody>

        </table>

        @else

        <p class="alert alert-danger">
          No candidates were allocated till now! Run the allocation command first then navigate this page again.
        </p>

        @endif

      </div>

    </main><!-- /.container -->

<!-- Contents Ends Here -->

</x-layout-dashboard>