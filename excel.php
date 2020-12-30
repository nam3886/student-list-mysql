<?php
require_once 'config.php';

function exportSql()
{
    global $db;
    $sql = "SHOW columns FROM post";
    $result = $db->query($sql);
    $all_data_post = $db?->table('post')?->get_all();

    if (!count($all_data_post)) {
        http_response_code(404);
        exit('Don\'t have any data!!!');
    }

    // tên của các cột
    $columns_name = [];
    foreach ($result as $value) {
        $columns_name[] = $value->Field;
    }

    function array2csv(array $columns_name, array $array)
    {
        if (count($array) === 0) {
            return null;
        }

        ob_start(); //Create an output buffer
        $df = fopen("php://output", 'w');

        fputcsv($df, $columns_name);

        foreach ($array as $row) {
            fputcsv($df, array_values((array) $row));
        }

        fclose($df);

        return ob_get_clean();
    }

    function download_send_headers($filename)
    {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-type: text/csv; charset=UTF-8');

        // disposition / encoding on response body
        header('Content-Encoding: UTF-8');
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

    // $data = array2csv($columns_name, $all_data_post);

    // download_send_headers("data_export_" . date("Y-m-d") . ".csv");

    // echo "\xEF\xBB\xBF"; // UTF-8 BOM
    // echo $data;

    // exit();

}

function importSql()
{

    if (!count($_FILES)) {
        http_response_code(422);
        exit('vui lòng nhập file csv');
    }

    global $db;

    $is_excel_file = '/.csv/';

    foreach ($_FILES as $file) {

        if (!is_file($file['tmp_name']) || !preg_match($is_excel_file, $file['name'])) {
            http_response_code(422);
            exit('vui lòng nhập file csv');
        }

        $handle = fopen($file['tmp_name'], "r");
        $c = 0;

        while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {

            if (!$c) {
                $c = $c + 1;
                continue;
            }

            $fname = $filesop[1];
            $lname = $filesop[2];
            $sql = "insert into post(title,content) values ('$fname','$lname')";
            $db->query($sql);
            $c = $c + 1;
        }
    }

    exit('imported data!!!');
}

$path = $_SERVER['REQUEST_URI'];

if (preg_match('/export=true/i', $path)) {
    exportSql();
} else if (preg_match('/import=true/i', $path)) {
    importSql($_POST);
}
