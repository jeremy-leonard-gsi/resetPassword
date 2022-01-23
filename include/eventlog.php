<?php

include("include/header.php");
include("include/menubar.php");

if($_SERVER['REQUEST_METHOD']=='POST'){
    error_log("Custom Filter");
    $limit = filter_input(INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT) ?? 10;
    $page = filter_input(INPUT_POST, 'page', FILTER_SANITIZE_NUMBER_INT ) ?? 1;
    $start = filter_input(INPUT_POST, 'from-date', FILTER_SANITIZE_STRING) ?? $appLog->getEventStart();
    $end = filter_input(INPUT_POST, 'to-date', FILTER_SANITIZE_STRING) ?? $appLog->getEventEnd();
    $filter = '%'.filter_input(INPUT_POST, 'filter', FILTER_SANITIZE_STRING ).'%' ?? '%';
    $logEntries = $appLog->getEvents($limit, $page, $start, $end, $filter);
    $total = ceil($appLog->getEventCount($start,$end,$filter)/$limit);
}else{
    $limit=1;
    $page=1;
    $start=$appLog->getEventStart();
    $end=$appLog->getEventEnd();
    $filter='%';
    $logEntries = $appLog->getEvents();
    $total = ceil($appLog->getEventCount()/10);
}

if(is_array($logEntries)){
$headers = '<tr><th>';
$headers .= convertCammelToSpace(implode('</th><th>', array_keys($logEntries[0])));
$headers .= '</th></tr>';
$rows = '';
foreach($logEntries as $row){
    $rows .= '<tr><td>' . implode('</td><td>',$row)."</td></tr>\n";
}
}else{
    $headers='';
    $rows='';
}
?>
<div class="container-fluid">
    <form id="filterForm" method="post">
        <div class="row g-4 align-items-center ms-3">
            <div class="col-auto">
                <label class="form-label" for="from-date">From Date</label>
            </div>
            <div class="col-auto">
                <input id="from-date" name="from-date" value="<?=$start?>" class="form-control form-control-sm" type="date" placeholder="From-Date" onchange="document.getElementById('filterForm').submit();">
            </div>
            <div class="col-auto">
                <label class="to-label" for="to-date">To Date</label>
            </div>
            <div class="col-auto">
                <input id="to-date" name="to-date" value="<?=$end?>" class="form-control form-control-sm" type="date" placeholder="To-Date" onchange="document.getElementById('filterForm').submit();">
            </div>
            <div class="col-auto">Entries per Page</div>
            <div class="col-auto">
                <select name="limit" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit();" aria-label=".form-select-sm example">
                  <option value="10" <?=$limit == 10 ? 'selected' : '' ?> >10</option>
                  <option value="20" <?=$limit == 20 ? 'selected' : '' ?> >20</option>
                  <option value="50" <?=$limit == 50 ? 'selected' : '' ?> >50</option>
                  <option value="100" <?=$limit == 100 ? 'selected' : '' ?> >100</option>
                  <option value="500" <?=$limit == 500 ? 'selected' : '' ?> >500</option>
                </select>
            </div>
            <div class="col-auto">
                <span>Total Pages: <?=$total ?></span>
            </div>
            <div class="col-auto">
                <input class="form-control form-control-sm" type="search" name="filter" placeholder="Search" value="<?=filter_input(INPUT_POST,'filter', FILTER_SANITIZE_STRING) ?>" onkeyup="setTimeout(function () {document.getElementById('filterForm').submit() }, 1500);"> 
            </div>
            <div class="col-auto">
                <input value="<?=$page?>" id="pageId" name="page" type="hidden">
                <nav aria-label="Page navigation example">
                  <ul class="pagination justify-content-end mt-md-3">
                    <li class="page-item"><a class="page-link" href="#" onclick="document.getElementById('pageId').value='1';document.getElementById('filterForm').submit();">&lt;&lt;</a></li>
                    <li class="page-item <?=$page==1 ? 'disabled' : '' ?>"><a class="page-link" href="#" onclick="document.getElementById('pageId').value='<?=$page-1?>';document.getElementById('filterForm').submit();">&lt;</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item <?=$page==$total ? 'disabled' : '' ?>"><a class="page-link" href="#" onclick="document.getElementById('pageId').value='<?=$page+1?>';document.getElementById('filterForm').submit();">&gt;</a></li>
                    <li class="page-item"><a class="page-link" href="#" onclick="document.getElementById('pageId').value='<?=$total?>';document.getElementById('filterForm').submit();">&gt;&gt;</a></li>
                  </ul>
                </nav>
            </div>
        </div>
    </form>
    <div class="table-responsive-lg">
        <table class="table table-striped table-sm">
            <thead><?=$headers ?></thead>
            <tbody><?=$rows ?></tbody>
        </table>
    </div>
</div>

<?php
include("include/footer.php");
