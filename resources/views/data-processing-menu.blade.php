<x-layout-dashboard>

<!-- Contents Starts Here -->

    <main role="main" class="container">

      <div class="starter-template">
      
        <h2>Data Processing Steps:</h2>

        <hr> 

        <ul class="list-group list-unstyled">
          <li class="nav-item list-group-item">
              <a class="nav-link list-unstyled" href="{{ url('/allocation/run') }}" target="_blank">1. Run General Allocation</a>
          </li>
          <li class="nav-item list-group-item">
              <a class="nav-link" href="{{ url('/allocation/run-shifting') }}" target="_blank">2. Run Shifting Allocation</a>
          </li>
          <li class="nav-item list-group-item">
              <a class="nav-link" href="{{ url('/allocation/run-nm-fill') }}" target="_blank">3. Run NM Allocation</a>
          </li>
        </ul>

      </div>

    </main><!-- /.container -->

<!-- Contents Ends Here -->

</x-layout-dashboard>