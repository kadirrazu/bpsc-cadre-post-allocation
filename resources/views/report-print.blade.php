<x-layout-dashboard>

<!-- Contents Starts Here -->

    <main role="main" class="container">

      <div class="starter-template">
      
        <h2>Reports:</h2>

        <hr> 

        <ul class="list-group">
          <li class="list-group-item">
            <a href="{{ url('/report-text-file') }}">Download Text File - Cadre Wise</a>
          </li>
          <li class="list-group-item">
            <a href="{{ url('/report-text-file-quota') }}">Download Text File - Cadre Wise - Only Quota</a>
          </li>
        </ul>

      </div>

    </main><!-- /.container -->

<!-- Contents Ends Here -->

</x-layout-dashboard>