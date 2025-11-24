<script defer src="{{ asset('assets/js/alpine.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.min.js') }}"></script>

<script>

    $(document).ready(function() {
        $('.datatable').DataTable({
            "pageLength": 20,
        });
    });

</script>