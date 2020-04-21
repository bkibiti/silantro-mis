<script>
    var NeedDateFilter = ["1","4"];
    var NoDateFilter = ["2","3","5","6"];

    var reportType = $('#report_type').val();

    if( NeedDateFilter.includes(reportType)){
        $('#fromDateDiv').show();
        $('#toDateDiv').show();
    }
    if( NoDateFilter.includes(reportType)){
        $('#fromDateDiv').hide();
        $('#toDateDiv').hide();
    }

    $('#report_type').change(function () {
        var value = $(this).val();
     
        if( NeedDateFilter.includes(value)){
            $('#fromDateDiv').show();
            $('#toDateDiv').show();
        }
        if( NoDateFilter.includes(value)){
            $('#fromDateDiv').hide();
            $('#toDateDiv').hide();
        }
    
    });


</script>