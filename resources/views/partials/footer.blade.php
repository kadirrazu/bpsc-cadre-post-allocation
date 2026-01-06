<script defer src="{{ asset('assets/js/alpine.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.min.js') }}"></script>

<script>

    $(document).ready(function() {
        $('.datatable').DataTable({
            "pageLength": 20,
            "columnDefs": [
                { "searchable": false, "targets": [0, 2, 3, 4, 5, 6, 7, 9, 10, 11] } 
            ],
        });

        $('.preloader2').addClass('d-none');
        $('.content-wrapper').removeClass('d-none');

    });

</script>