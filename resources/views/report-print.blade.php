<x-layout-dashboard>

<!-- Contents Starts Here -->

    <main role="main" class="container">

      <div class="starter-template">
      
        <h2>Reports:</h2>

        <hr> 

        <ul class="list-group">
          <li class="list-group-item">
            <a href="{{ url('/report-text-file') }}" class="text-decoration-none">Download Text File - Cadre Wise</a>
          </li>
          <li class="list-group-item">
            <a href="{{ url('/report-text-file-quota') }}" class="text-decoration-none">Download Text File - Cadre Wise - Only Quota (CFF, EM & PHC)</a>
          </li>
          <li class="list-group-item">
            <a href="{{ url('/report-text-file-with-all-status') }}" class="text-decoration-none">Download Text File - Cadre Wise - All Status</a>
          </li>
          <li class="list-group-item">
            <a href="{{ url('/report-text-file-non-cadre') }}" class="text-decoration-none">Download Text File - Non Cadre</a>
          </li>
          <li class="list-group-item">
            <a href="{{ url('/report-text-file-by-merit-position') }}" class="text-decoration-none">Download Text File - Cadre Wise - Only Merit Position</a>
          </li>
          <li class="list-group-item">
            <a href="{{ url('/report-pdf-file-cadre-wise') }}" class="text-decoration-none" style="color: navy;">
              Download Allocation PDF - Cadre Wise - All Cadre
            </a>
          </li>
          <li class="list-group-item">
            <a href="{{ url('/report-pdf-file-merit-wise-all-cadre-without-tt') }}" class="text-decoration-none" style="color: maroon;">
              *Download Allocation List - Without TT - All Cadre - Order by Merit
            </a>
          </li>
          <li class="list-group-item">
            <a href="{{ url('/report-pdf-file-merit-wise-all-cadre-only-tt') }}" class="text-decoration-none" style="color: maroon;">
              *Download Allocation List - Only TT - All Technical Cadre - Order by Merit
            </a>
          </li>
          <li class="list-group-item">
            <a href="{{ url('/report-pdf-file-merit-wise-all-cadre-without-tt-only-quota') }}" class="text-decoration-none" style="color: teal;">
              *Download Allocation List - Without TT - All Cadre - Order by Merit - Only Quota
            </a>
          </li>
          <li class="list-group-item">
            <a href="{{ url('/report-pdf-file-merit-wise-all-cadre-only-tt-only-quota') }}" class="text-decoration-none" style="color: teal;">
              *Download Allocation List - Only TT - All Technical Cadre - Order by Merit - Only Quota
            </a>
          </li>

          <li class="list-group-item">
            <a href="{{ url('/report-pdf-file-merit-wise-all-cadre-without-tt-tabulation') }}" class="text-decoration-none" style="color: red;">
              *Download Tabulation List - Without TT - All Cadre - Order by Merit
            </a>
          </li>

          <li class="list-group-item">
            <a href="{{ url('/report-pdf-file-merit-wise-all-cadre-only-tt-tabulation') }}" class="text-decoration-none" style="color: red;">
              *Download Tabulation List - Only TT - All Technical Cadre - Order by Merit
            </a>
          </li>

          <li class="list-group-item">
            <a href="{{ url('/report-pdf-file-cadre-wise-all-tech-passed') }}" class="text-decoration-none" style="color: green;">
              *Download Tabulation List - ALL TECHNICAL MERIT CANDIDATE - CADRE WISE - Order by Tech Merit
            </a>
          </li>

        </ul>

      </div>

    </main><!-- /.container -->

<!-- Contents Ends Here -->

</x-layout-dashboard>