<div id="report-wrapper">
    <div id="report-toolbar">
        <ul id="report-export"><li id="report-toolbar-pdf">Export PDF</li><li id="report-toolbar-xls">Export Excel</li></ul><ul id="report-options"><li id="report-options">Report Options</li></ul>
    </div>
    <div id="report-body">
    </div>
    <div id="report-filter">{$filters}</div>
</div>
<script type="text/javascript">
    $(function(){
        $('#report-body').load("{$path}/generate/?report_format=html&title=no&logo=no");
    });
</script>