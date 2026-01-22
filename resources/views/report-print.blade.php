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
            <a href="{{ url('/report-text-file-quota') }}" class="text-decoration-none">Download Text File - Cadre Wise - Only Quota</a>
          </li>
          <li class="list-group-item">
            <a href="{{ url('/report-pdf-file-cadre-wise') }}" class="text-decoration-none">Download Allocation PDF - Cadre Wise</a>
          </li>
        </ul>

      </div>

    </main><!-- /.container -->

<!-- Contents Ends Here -->

</x-layout-dashboard>