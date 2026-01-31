<div class="container">
    <div class="row">
        <div class="col-md-12">
            <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top m-auto ps-3 pe-3">
                <a class="navbar-brand" href="#">BPSC - CADRE POST ALLOCATION SYSTEM</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                    
                </div>

                <form class="form-inline my-2 my-lg-0">
                    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ url('/') }}">Post Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/candidates') }}">Candidates</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/allocations') }}">Allocation Result</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/allocation/run') }}">Run General Allocation</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/allocation/run-nm-fill') }}">Run NM Allocation</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/reports') }}">Reports</a>
                            </li>
                        </ul>
                    </div>
                </form>
            </nav>
        </div>
    </div>
</div>