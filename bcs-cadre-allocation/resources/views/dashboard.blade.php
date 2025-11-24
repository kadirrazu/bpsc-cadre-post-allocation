<x-layout-dashboard>

<!-- Contents Starts Here -->

    <main role="main" class="container">

      <div class="starter-template">
      
        <table class="table table-striped table-bordered">
            <tr class="text-center">
                <th>Sr.</th>
                <th>Post Code</th>
                <th>Cadre</th>
                <th>Total Post</th>
                <th>MQ</th>
                <th>CFF</th>
                <th>EM</th>
                <th>PHC</th>
                <th>Allocated Post</th>
            </tr>
            @foreach( $posts as $post )
            <tr class="text-center">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $post->cadre_code }}</td>
                <td>{{ $cadres->where('cadre_code', $post->cadre_code)->first()->cadre_abbr }}</td>
                <td>{{ $post->total_post }}</td>
                <td>{{ $post->mq_post }}</td>
                <td>{{ $post->cff_post }}</td>
                <td>{{ $post->em_post }}</td>
                <td>{{ $post->phc_post }}</td>
                <td>{{ $post->allocated_post }}</td>
            </tr>
            @endforeach
        </table>

      </div>

    </main><!-- /.container -->

<!-- Contents Ends Here -->

</x-layout-dashboard>